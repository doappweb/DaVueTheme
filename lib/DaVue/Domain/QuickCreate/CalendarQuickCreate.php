<?php

namespace SuiteCRM\DaVue\Domain\QuickCreate;

use SubpanelQuickCreate;

class CalendarQuickCreate extends SubpanelQuickCreate
{
    public function process($module)
    {
        if ($_REQUEST['target_action'] == 'QuickCreate') {
            $this->ev->view = 'QuickCreate';
        }
        $this->setPanelMetadata();
        $form_name = 'form_Subpanel'.$this->ev->view .'_'.$module;
        $this->ev->formName = $form_name;
        $this->ev->process(true, $form_name);
        echo $this->ev->display(false, true);
    }

    private function setPanelMetadata()
    {
        $this->ev->defs['panels']['default'] = [
            ['name' => 'name'],
            ['name' => 'date_start'],
            ['name' => 'duration'],
            ['name' => 'fp_event_locations_fp_events_1_name'],
            ['name' => 'description'],
            ['name' => 'assigned_user_name'],
        ];
    }
}
