<?php

namespace SuiteCRM\DaVue\Services\Smarty\Views;

use BeanFactory;
use SuiteCRM\DaVue\Services\Common\Utils;

class ListView implements ViewHandlerInterface
{
    /** @var Utils $utils */
    private $utils;

    private $params;
    private $result = [];
    private $rowProperties = [];
    private $activitiesModule = ['Calls', 'Meetings', 'Tasks'];

    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }

    public function handle($params): array
    {
        if (!is_array($params)){
            return $this->result;
        }

        $this->params = $params;
        $this->preHandler();
        $this->generate();

        return $this->result;
    }

    private function preHandler()
    {
        global $current_user;

        // Some fields in the box are formed in a non-standard way. We bring it to the standard form
        foreach ($this->params['data'] as &$fields) {

            $fieldsParams = array();

            if (isset($this->params['displayColumns']['DATE_START']) && in_array($this->params['pageData']['bean']['moduleName'], $this->activitiesModule)) {
                $this->params['displayColumns']['DATE_START']['type'] = 'dateStart_widget';
                $fieldsParams['date_start']['class'] = $this->utils->parseHtmlAttribute($fields['DATE_START'], 'class');
                $fields['DATE_START'] = strip_tags($fields['DATE_START']);
            }
            if (isset($this->params['displayColumns']['SET_COMPLETE']) && in_array($this->params['pageData']['bean']['moduleName'], $this->activitiesModule)) {
                $this->params['displayColumns']['SET_COMPLETE']['type'] = 'setComplete_button';
                if (!empty($fields['SET_COMPLETE'])){
                    $fields['SET_COMPLETE'] = true;
                }
            }
            if (isset($this->params['displayColumns']['DATE_DUE']) && in_array($this->params['pageData']['bean']['moduleName'], $this->activitiesModule)) {
                $this->params['displayColumns']['DATE_DUE']['type'] = 'dateDue_widget';
                $fieldsParams['date_due']['class'] = $this->utils->parseHtmlAttribute($fields['DATE_DUE'], 'class');
                $fields['DATE_DUE'] = strip_tags($fields['DATE_DUE']);
            }
            if (isset($this->params['displayColumns']['AMOUNT_USDOLLAR']) && $this->params['displayColumns']['AMOUNT_USDOLLAR']['type'] === 'currency') {
                // Display value in the user's currency
                // In the box, this inlineEdit field working is not correct
                $userCurrencyBean = BeanFactory::newBean('Currencies');
                $userCurrencyBean->retrieve($current_user->getPreference('currency'));
                $fieldsParams = array(
                    'currencySymbol' => $userCurrencyBean->symbol,
                );
                $fields['AMOUNT_USDOLLAR'] = $userCurrencyBean->convertFromDollar($fields['AMOUNT_USDOLLAR']);
            }

            $rowProperties[$fields['ID']] = $fieldsParams;
        }

        $this->params['pageData']['rowProperties'] = $rowProperties;

        // In the box, link for the endPage button is always generated - is not correct
        if ($this->params['pageData']['offsets']['lastOffsetOnPage'] == $this->params['pageData']['offsets']['total']) {
            unset($this->params['pageData']['urls']['endPage']);
        }
    }

    private function generate()
    {
        $this->result = array(
            'editViewLinksEnable' => !empty($this->params['quickViewLinks']),
            "selectRecordsEnable" => (bool)$this->params['prerow'],
            'pageData' => $this->params['pageData'],
            "selectedRecordsActions" => $this->getSelectedRecordsActions($this->params['actionsLinkTop']['buttons']),
            'viewData' => array(
                'displayColumns' => $this->params['displayColumns'],
                'data' => $this->params['data'],
            ),
        );
    }

    /**
     * Get id from an array of html links, which can be used to determine exactly what action the link is responsible for.
     *
     * @param array $sourceActions - html-links <a>
     * @return array
     */
    private function getSelectedRecordsActions(array $sourceActions): array
    {
        $result = array();
        $resultIndex = 0;
        foreach ($sourceActions as $sourceIndex => $sourceElem) {
            if (0 === $sourceIndex || !$sourceElem ) {
                continue;
            }

            $htmlLinks = array();
            while (mb_substr_count($sourceElem, '</a>') > 1) {
                $indexCurrentLinkEnd = mb_strpos($sourceElem, '</a>') + 4;
                $htmlLinks[] = mb_substr($sourceElem, 0, $indexCurrentLinkEnd);
                $indexNextLinkBegin = mb_strpos($sourceElem, '<a ', $indexCurrentLinkEnd);
                $sourceElem = mb_substr($sourceElem, $indexNextLinkBegin);
            }
            $htmlLinks[] = $sourceElem;

            foreach ($htmlLinks as $htmlLink) {
                // innerHTML
                $result[$resultIndex]['innerHTML'] = strip_tags($htmlLink);

                // Next, we are trying to determine some kind of unique identifier
                $id = $this->utils->parseHtmlAttribute($htmlLink, 'id');
                if (!empty($id)) {
                    $result[$resultIndex]['id'] = trim($id);
                } else {
                    $result[$resultIndex]['id'] = null;
                    $onclickData = $this->utils->parseHtmlAttribute($htmlLink, 'onclick');

                    // We are looking for the name of the first encountered function inside the onclick content
                    // The regular finds the name of the function along with the passed arguments
                    if (1 === preg_match('/[A-Za-z_][A-Za-z0-9_]*\([^)]*\)/', $onclickData, $matches)) {
                        $indexBracket = mb_strpos($matches[0], '(');
                        $result[$resultIndex]['onclick'] = mb_substr($matches[0], 0, $indexBracket);
                    } else {
                        $result[$resultIndex]['onclick'] = null;
                    }
                }

                $resultIndex++;
            }
        }

        return $result;
    }
}
