<?php

namespace SuiteCRM\DaVue\Domain\Modules;

class LeadsHandler
{
    /**
     * TODO: NONE FINAL IMPLEMENTATION
     *
     * @usage themes/DaVue/modules/Leads/tpls/ConvertLead.tpl
     * @usage themes/DaVue/modules/Leads/tpls/ConvertLeadHeader.tpl
     *
     * @param array $params
     * @return array
     */
    private function convertLead(array $params): array
    {
        // TODO: Is it use?
        if (false !== $params['tabDefs']) {
            $tabDefs = $params['tabDefs'];
        } else {
            foreach ($params['sectionPanels'] as $panelName => $panelParams) {
                $tabDefs[$panelName] = array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                );
            }
        }

        foreach ($params['fields'] as &$fieldDef) {
            if (isset($fieldDef['function'])) {
                $this->resolveFunctionAttributeField($fieldDef, $params['bean']);
            }
        }

        $result = array(
            'pageData' => array(
                // TODO: Is it use?
                'useTabs' => $params['useTabs'],
                // TODO: Some parameter from the config. Affects js
                'lead_conv_activity_opt' => $params['lead_conv_activity_opt'],
                // popup picker for those modules that have the option to select an existing record instead of creating a new one (Account, Contact)
                'initialFilter' => $params['initialFilter'],
                // TODO: An array of field names that are used to communicate with the corresponding module?
                'selectFields' => $params['selectFields'],
                // TODO: Is it use?
                // Panel metadata along with the location of fields
                'def' => $params['def'],
            ),
            'viewData' => array(
                'panelsFields' => $this->resortSectionPanels($params['sectionPanels'], $params['view'], $params['module']),
                'panelsMetadata' => $tabDefs,
            ),
            // The fields of the module are protected. If there is a filled value, it will be in the value attribute.
            'beanData' => $params['fields'],
        );

        return $result;
    }
}
