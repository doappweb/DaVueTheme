<?php

namespace SuiteCRM\DaVue\Domain\Modules;

use BeanFactory;
use Currency;
class PDF_TemplateHandler
{
    public function getInsertFieldOptions(): array
    {
        global $app_list_strings, $beanList;
        $modules = $app_list_strings['pdf_template_type_dom'];
        $moduleOptions = array();
        $regularOptions = array();

        foreach ($modules as $moduleName => $value) {
            $regularOptions[$moduleName] = array();
            $options_array = array(''=>'');
            $mod_options_array = array();

            //Getting Fields
            if (!$beanList[$moduleName]) {
                continue;
            }
            $module = new $beanList[$moduleName]();

            foreach ($module->field_defs as $name => $arr) {
                if (!((isset($arr['dbType']) && strtolower($arr['dbType']) == 'id') || (isset($arr['type']) && $arr['type'] == 'id') || (isset($arr['type']) && $arr['type'] == 'link'))) {
                    if (!isset($arr['reportable']) || $arr['reportable']) {
                        $options_array['$'.$module->table_name.'_'.$name] = translate($arr['vname'], $module->module_dir);
                    }
                }
            } //End loop.

            $mod_options_array[$module->module_dir] = translate('LBL_MODULE_NAME', $module->module_dir);
            $regularOptions[$moduleName][$moduleName] = $options_array;

            $fmod_options_array = array();
            foreach ($module->field_defs as $module_name => $module_arr) {
                if (isset($module_arr['type']) && $module_arr['type'] == 'relate' && isset($module_arr['source']) && $module_arr['source'] == 'non-db') {
                    $options_array = array(''=>'');
                    if (isset($module_arr['module']) &&  $module_arr['module'] != '' && $module_arr['module'] != 'EmailAddress') {
                        $relate_module_name = $beanList[$module_arr['module']];
                        $relate_module = new $relate_module_name();

                        foreach ($relate_module->field_defs as $relate_name => $relate_arr) {
                            if (!((isset($relate_arr['dbType']) && strtolower($relate_arr['dbType']) == 'id') || $relate_arr['type'] == 'id' || $relate_arr['type'] == 'link')) {
                                if ((!isset($relate_arr['reportable']) || $relate_arr['reportable']) && isset($relate_arr['vname'])) {
                                    $options_array['$'.$module_arr['name'].'_'.$relate_name] = translate($relate_arr['vname'], $relate_module->module_dir);
                                }
                            }
                        } //End loop.

                        if ($module_arr['vname'] != 'LBL_DELETED') {
                            $options_array['$'.$module->table_name.'_'.$name] = translate($module_arr['vname'], $module->module_dir);
                            $fmod_options_array[$module_arr['vname']] = translate($relate_module->module_dir).' : '.translate($module_arr['vname'], $module->module_dir);
                        }
                        $fieldName = $module_arr['vname'];
                        $regularOptions[$moduleName][$fieldName] = $options_array;
                    }
                }
            }

            //LINE ITEMS CODE!
            if (isset($module->lineItems) && $module->lineItems) {

                //add group fields
                $options_array = array(''=>'');
                $group_quote = BeanFactory::newBean('AOS_Line_Item_Groups');
                foreach ($group_quote->field_defs as $line_name => $line_arr) {
                    if (!((isset($line_arr['dbType']) && strtolower($line_arr['dbType']) == 'id') || $line_arr['type'] == 'id' || $line_arr['type'] == 'link')) {
                        if ((!isset($line_arr['reportable']) || $line_arr['reportable'])) {//&& $line_arr['vname']  != 'LBL_NAME'
                            $options_array['$'.$group_quote->table_name.'_'.$line_name] = translate($line_arr['vname'], $group_quote->module_dir);
                        }
                    }
                }

                $line_module_name = $beanList['AOS_Line_Item_Groups'];
                $fmod_options_array[$line_module_name] = translate('LBL_LINE_ITEMS', 'AOS_Quotes').' : '.translate('LBL_MODULE_NAME', 'AOS_Line_Item_Groups');
                $regularOptions[$moduleName][$line_module_name] = $options_array;

                //PRODUCTS
                $options_array = array(''=>'');

                $product_quote = BeanFactory::newBean('AOS_Products_Quotes');
                foreach ($product_quote->field_defs as $line_name => $line_arr) {
                    if (!((isset($line_arr['dbType']) && strtolower($line_arr['dbType']) == 'id') || $line_arr['type'] == 'id' || $line_arr['type'] == 'link')) {
                        if (!isset($line_arr['reportable']) || $line_arr['reportable']) {
                            $options_array['$'.$product_quote->table_name.'_'.$line_name] = translate($line_arr['vname'], $product_quote->module_dir);
                        }
                    }
                }

                $product_quote = BeanFactory::newBean('AOS_Products');
                foreach ($product_quote->field_defs as $line_name => $line_arr) {
                    if (!((isset($line_arr['dbType']) && strtolower($line_arr['dbType']) == 'id') || $line_arr['type'] == 'id' || $line_arr['type'] == 'link')) {
                        if ((!isset($line_arr['reportable']) || $line_arr['reportable']) && $line_arr['vname']  != 'LBL_NAME') {
                            $options_array['$'.$product_quote->table_name.'_'.$line_name] = translate($line_arr['vname'], $product_quote->module_dir);
                        }
                    }
                }

                $line_module_name = $beanList['AOS_Products_Quotes'];
                $fmod_options_array[$line_module_name] = translate('LBL_LINE_ITEMS', 'AOS_Quotes').' : '.translate('LBL_MODULE_NAME', 'AOS_Products');
                $regularOptions[$moduleName][$line_module_name] = $options_array;

                //Services
                $options_array = array(''=>'');
                $options_array['$aos_services_quotes_name'] = translate('LBL_SERVICE_NAME', 'AOS_Quotes');
                $options_array['$aos_services_quotes_number'] = translate('LBL_LIST_NUM', 'AOS_Products_Quotes');
                $options_array['$aos_services_quotes_service_list_price'] = translate('LBL_SERVICE_LIST_PRICE', 'AOS_Quotes');
                $options_array['$aos_services_quotes_service_discount'] = translate('LBL_SERVICE_DISCOUNT', 'AOS_Quotes');
                $options_array['$aos_services_quotes_service_unit_price'] = translate('LBL_SERVICE_PRICE', 'AOS_Quotes');
                $options_array['$aos_services_quotes_vat_amt'] = translate('LBL_VAT_AMT', 'AOS_Quotes');
                $options_array['$aos_services_quotes_vat'] = translate('LBL_VAT', 'AOS_Quotes');
                $options_array['$aos_services_quotes_service_total_price'] = translate('LBL_TOTAL_PRICE', 'AOS_Quotes');

                $s_line_module_name = 'AOS_Service_Quotes';
                $fmod_options_array[$s_line_module_name] = translate('LBL_LINE_ITEMS', 'AOS_Quotes').' : '.translate('LBL_SERVICE_MODULE_NAME', 'AOS_Products_Quotes');
                $regularOptions[$moduleName][$s_line_module_name] = $options_array;

                $options_array = array(''=>'');
                $currencies = new Currency();
                foreach ($currencies->field_defs as $name => $arr) {
                    if (!((isset($arr['dbType']) && strtolower($arr['dbType']) == 'id') || $arr['type'] == 'id' || $arr['type'] == 'link' || $arr['type'] == 'bool' || $arr['type'] == 'datetime' || (isset($arr['link_type']) && $arr['link_type'] == 'relationship_info'))) {
                        if (isset($arr['vname']) && $arr['vname'] != 'LBL_DELETED' && $arr['vname'] != 'LBL_CURRENCIES_HASH' && $arr['vname'] != 'LBL_LIST_ACCEPT_STATUS' && $arr['vname'] != 'LBL_AUTHENTICATE_ID' && $arr['vname'] != 'LBL_MODIFIED_BY' && $arr['name'] != 'created_by_name') {
                            $options_array['$currencies_'.$name] = translate($arr['vname'], 'Currencies');
                        }
                    }
                }

                $line_module_name = $beanList['Currencies'];
                $fmod_options_array[$line_module_name] = translate('LBL_MODULE_NAME', 'Currencies').' : '.translate('LBL_MODULE_NAME', 'Currencies');
                $regularOptions[$moduleName][$line_module_name] = $options_array;
            }
            array_multisort($fmod_options_array, SORT_ASC, $fmod_options_array);
            $mod_options_array = array_merge($mod_options_array, $fmod_options_array);
            $module_options = $mod_options_array;

            $moduleOptions[$moduleName]=$module_options;
        } //End loop.

        return array('regularOptions' => $regularOptions, 'moduleOptions' => $moduleOptions);
    }

    public function getSampleFieldOptions(){
        global $mod_strings;

        //Loading Sample Files
        $samples = array();
        $options = array('' => '');
        $sampleData = array();
        if ($handle = opendir('modules/AOS_PDF_Templates/samples')) {
            while (false !== ($file = readdir($handle))) {
                if ($value = ltrim(rtrim($file, '.php'), 'smpl_')) {
                    require_once('modules/AOS_PDF_Templates/samples/'.$file);
                    $file = rtrim($file, '.php');
                    $file = new $file();
                    $fileArray =
                        array(
                            $file->getType(),
                            $file->getBody(),
                            $file->getHeader(),
                            $file->getFooter()
                        );
                    $options[$value] = $mod_strings['LBL_'.strtoupper($value)];
                    $sampleData[$value] = $fileArray;
                }
            }
            closedir($handle);
        }
        return array('options'=>$options,'sampleData'=>$sampleData);
    }
}