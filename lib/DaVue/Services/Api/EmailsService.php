<?php

namespace SuiteCRM\DaVue\Services\Api;

use BeanFactory;
use EmailsDataAddressCollector;
use EmailsViewCompose;
use EmailValidatorException;
use Exception;
use SuiteCRM\DaVue\Services\Common\Utils;

/**
 *
 */
class EmailsService
{
    /** @var Utils $utils */
    private $utils;

    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }

    /**
     * Receiving data for an email submission form
     * TODO: Should this method be able to accept multiple ids at once?
     *
     * @usage http://localhost/index.php?VueAjax=1&method=getSendEmailForm&arg[]...
     * @param $args
     * @return array
     * @throws Exception
     */
    public function getSendEmailForm($args)
    {
        $targetModule = $args['targetModule'];
        if (null === $targetModule) {
            throw new Exception("Target module was not passed");
        }

        $targetIds = $args['ids'];
        if (null === $targetIds) {
            throw new Exception("Target ids was not passed");
        }
        $targetIds = explode(',', $targetIds);

        $targetBean = BeanFactory::getBean($targetModule);
        if (false === $targetBean) {
            throw new Exception("Target module '$targetModule' is not exist");
        }

        $emailBean = BeanFactory::getBean('Emails');
        $emailsViewCompose = new EmailsViewCompose();
        $GLOBALS['module'] = 'Emails';  // Required to correctly fill the module property during the init action
        $emailsViewCompose->init($emailBean);
        $emailsViewCompose->preDisplay();
        $emailsViewCompose->ev->process();
        ob_start();
        $emailsViewCompose->display($emailsViewCompose->showTitle);
        ob_end_clean();

        foreach ($emailsViewCompose->ss->_tpl_vars['sectionPanels'] as $panelName => $panelParams) {
            $tabDefs[$panelName] = array(
                'newTab' => false,
                'panelDefault' => 'expanded',
            );
        }

        // In the original system, contextual action buttons are represented by custom code
        // @see modules/Emails/metadata/composeviewdefs.php
        $actionButtons = array(
            array(
                'name' => 'ID_BTN_SEND_EMAIL',
                'label' => 'LBL_SEND_BUTTON_TITLE',
                'icon' => 'glyphicon glyphicon-send',
                'originalCustomCode' => '<button class="btn btn-send-email" title="{$MOD.LBL_SEND_BUTTON_TITLE}"><span class="glyphicon glyphicon-send"></span></button>',
            ),
            array(
                'name' => 'ID_BTN_ATTACH_FILE',
                'label' => 'LBL_ATTACH_FILES',
                'icon' => 'glyphicon glyphicon-paperclip',
                'originalCustomCode' => '<button class="btn btn-attach-file" title="{$MOD.LBL_ATTACH_FILES}"><span class="glyphicon glyphicon-paperclip"></span></button>',
            ),
            array(
                'name' => 'ID_BTN_ATTACH_DOCUMENT',
                'label' => 'LBL_ATTACH_DOCUMENTS',
                'icon' => 'glyphicon suitepicon suitepicon-module-documents',
                'originalCustomCode' => '<button class="btn btn-attach-document" title="{$MOD.LBL_ATTACH_DOCUMENTS}"><span class="glyphicon suitepicon suitepicon-module-documents"></span></button>',
            ),
            array(
                'name' => 'ID_BTN_SAVE_DRAFT',
                'label' => 'LBL_SAVE_AS_DRAFT_BUTTON_TITLE',
                'icon' => 'glyphicon glyphicon-floppy-save',
                'originalCustomCode' => '<button class="btn btn-save-draft" title="{$MOD.LBL_SAVE_AS_DRAFT_BUTTON_TITLE}"><span class="glyphicon glyphicon-floppy-save"></span></button>',
            ),
            array(
                'name' => 'ID_BTN_DISREGARD_DRAFT',
                'label' => 'LBL_DISREGARD_DRAFT_BUTTON_TITLE',
                'icon' => 'glyphicon glyphicon-trash',
                'originalCustomCode' => '<button class="btn btn-disregard-draft" title="{$MOD.LBL_DISREGARD_DRAFT_BUTTON_TITLE}"><span class="glyphicon glyphicon-trash"></span></button>',
            ),
        );

        // This fields' metadata contain call_back_function, so change the field type to a custom
        $emailsViewCompose->ss->_tpl_vars['fields']['emails_email_templates_name']['type'] = 'Emails-ComposeView-emails_email_templates_name';
        $emailsViewCompose->ss->_tpl_vars['fields']['parent_name']['type'] = 'Emails-ComposeView-parent_name';
        $emailsViewCompose->ss->_tpl_vars['fields']['from_addr_name']['type'] = 'Emails-ComposeView-from_addr_name';
        $emailsViewCompose->ss->_tpl_vars['fields']['to_addrs_names']['type'] = 'Emails-ComposeView-to_addrs_names';
        $emailsViewCompose->ss->_tpl_vars['fields']['cc_addrs_names']['type'] = 'Emails-ComposeView-cc_addrs_names';
        $emailsViewCompose->ss->_tpl_vars['fields']['bcc_addrs_names']['type'] = 'Emails-ComposeView-bcc_addrs_names';
        $emailsViewCompose->ss->_tpl_vars['fields']['description']['type'] = 'Emails-ComposeView-description';
        $emailsViewCompose->ss->_tpl_vars['fields']['description_html']['type'] = 'Emails-ComposeView-description_html';
        $emailsViewCompose->ss->_tpl_vars['fields']['is_only_plain_text']['type'] = 'Emails-ComposeView-is_only_plain_text';

        // In the original system, values were taken from hidden input fields
        // @see modules/Emails/EmailsController.php :: action_ComposeView()
        $toAddrValues = array();
        foreach ($targetIds as $targetId) {
            $targetBean->retrieve($targetId);
            if (null === $targetBean) {
                throw new Exception("Target module '$targetModule' with id '$targetIds' is not exist");
            }

            $toAddrValues[] = $targetBean->name . ' <' . $targetBean->email1 . '>';
        }
        $emailsViewCompose->ss->_tpl_vars['fields']['to_addrs_names']['value'] = implode(', ', $toAddrValues);

        // It is not yet clear how many ids the method should accept: one or several. However, there is always one entry in the "Related to" field
        $emailsViewCompose->ss->_tpl_vars['fields']['parent_name']['value'] = $targetBean->name;
        $emailsViewCompose->ss->_tpl_vars['fields']['parent_type']['value'] = $targetModule;
        $emailsViewCompose->ss->_tpl_vars['fields']['parent_id']['value'] = $targetBean->id;

        $dataAddresses = $this->handleActionGetFromFields();
        $fromNameAddrOptions = array();
        foreach ($dataAddresses as $dataAddress) {
            $fromNameAddrOptions[$dataAddress['id']]['name'] = $dataAddress['attributes']['name'];
            $fromNameAddrOptions[$dataAddress['id']]['from'] = $dataAddress['attributes']['from'];
            $fromNameAddrOptions[$dataAddress['id']]['reply_to'] = $dataAddress['attributes']['reply_to'];
            $fromNameAddrOptions[$dataAddress['id']]['prepend'] = $dataAddress['prepend'];
            $fromNameAddrOptions[$dataAddress['id']]['emailSignatures'] = $dataAddress['emailSignatures'];
        }
        $emailsViewCompose->ss->_tpl_vars['fields']['from_addr_name']['options'] = $fromNameAddrOptions;
        $emailsViewCompose->ss->_tpl_vars['fields']['from_addr_name']['value'] = $dataAddresses[0]['id'];

        $result = array(
            'pageData' => array(
                'actionButtons' => $actionButtons,
                'bean' => array(
                    'objectName' => $emailsViewCompose->ss->_tpl_vars['bean']->object_name,
                    'moduleDir' => $emailsViewCompose->ss->_tpl_vars['bean']->module_name,
                    'moduleName' => $emailsViewCompose->ss->_tpl_vars['bean']->module_dir
                ),
            ),
            'viewData' => array(
                'panelsFields' => $this->utils->resortSectionPanels(
                    $emailsViewCompose->ss->_tpl_vars['sectionPanels'],
                    $emailsViewCompose->ss->_tpl_vars['view'],
                    $emailsViewCompose->ss->_tpl_vars['module']
                ),
                // panels Metadata: panel (or tab) display mode and default collapsed/expanded
                'panelsMetadata' => $tabDefs,
            ),
            // vardefs for module fields. If there is a filled value, it will be in the value attribute.
            'beanData' => $emailsViewCompose->ss->_tpl_vars['fields'],
        );

        //No SMTP server is set up Error.
        $admin = BeanFactory::newBean('Administration');
        $smtp_error = $admin->checkSmtpError();
        if($smtp_error){
            $result['pageData']['smtpError'] = translate('WARN_NO_SMTP_SERVER_AVAILABLE_ERROR', 'Administration');
        }

        return $result;
    }


    /**
     * Receiving original data to form the "From" field on the email sending form.
     *
     * In the original, in the email sending form, the “From” field is filled in using js with ajax request.
     * Below is the code copied from the original, because it is used either in non-public methods, or immediately sends
     * the result to the output stream.
     *
     * @return array
     * @throws EmailValidatorException
     */
    private function handleActionGetFromFields()
    {
        global $current_user, $sugar_config;

        $emailBean = BeanFactory::getBean('Emails');

        // @see modules/Emails/EmailsController.php :: action_getFromFields()
        $inboundEmailBean = BeanFactory::newBean('InboundEmail');
        $collector = new EmailsDataAddressCollector($current_user, $sugar_config);

        // @see modules/Emails/EmailsControllerActionGetFromFields.php :: handleActionGetFromFields()
        $emailBean->email2init();
        $inboundEmailBean->email = $emailBean;
        $ieAccounts = $inboundEmailBean->retrieveAllByGroupIdWithGroupAccounts($current_user->id);
        $accountSignatures = $current_user->getPreference('account_signatures', 'Emails');
        $showFolders = sugar_unserialize(base64_decode($current_user->getPreference('showFolders', 'Emails')));
        if ($accountSignatures != null) {
            $emailSignatures = sugar_unserialize(base64_decode($accountSignatures));
        } else {
            $GLOBALS['log']->warn('User ' . $current_user->name . ' does not have a signature');
            $emailSignatures = null;
        }
        $defaultEmailSignature = $current_user->getDefaultSignature();
        if (empty($defaultEmailSignature)) {
            $defaultEmailSignature = array(
                'html' => '<br>',
                'plain' => '\r\n',
            );
            $defaultEmailSignature['no_default_available'] = true;
        } else {
            $defaultEmailSignature['no_default_available'] = false;
        }
        $prependSignature = $current_user->getPreference('signature_prepend');
        $dataAddresses = $collector->collectDataAddressesFromIEAccounts(
            $ieAccounts,
            $showFolders,
            $prependSignature,
            $emailSignatures,
            $defaultEmailSignature
        );

        return $dataAddresses;
    }
}
