<?php

namespace SuiteCRM\DaVue\Services\Common;

use BeanFactory;
use SubPanelDefinitions;

class SubPanel
{
    public function subPanelTiles($params): array
    {
        $moduleName = $params['layout_def_key'];
        $bean = BeanFactory::getBean($moduleName);

        if (empty($bean->module_dir)) {
            return array();
        }

        $spd = new SubPanelDefinitions($bean);
        $layout_defs = array();
        foreach ($params['subpanel_tabs'] as $order => $subpanel){
            $aSubPanelObject = $spd->load_subpanel($subpanel);
            $layout_defs[$subpanel] = $aSubPanelObject->_instance_properties;

            $btn = array();
            if (!empty($layout_defs[$subpanel]["top_buttons"])){
                $btn = $layout_defs[$subpanel]["top_buttons"];
            }elseif(!empty($aSubPanelObject->panel_definition["top_buttons"])){
                $btn = $aSubPanelObject->panel_definition["top_buttons"];
            }

            $layout_defs[$subpanel]['top_buttons'] = $btn;
            $layout_defs[$subpanel]['expanded_subpanels'] = $this->getSubpanelExpand($subpanel, $moduleName);
        }

        $lockDrag = false;
        if (!empty($params['sugar_config']['lock_subpanels'])) {
            $lockDrag = true;
        }

        $result = array(
            'tabs' => $params['subpanel_tabs'],
            'tabsProperties' => $layout_defs,
            'lock' => $lockDrag,
        );

        return $result;
    }

    private function getSubpanelExpand(string $subpanel, string $module): bool
    {
        global $current_user, $db;

        $category = $module . '_' . $subpanel . '_SP';
        $sql = "SELECT contents FROM user_preferences 
         WHERE category = '{$category}' 
           AND assigned_user_id = '{$current_user->id}' 
           AND deleted = 0";

        return $db->getOne($sql);
    }
}
