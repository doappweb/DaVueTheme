<?php

namespace SuiteCRM\DaVue\Services\Common;

use BeanFactory;

class Utils
{
    /**
     * Rebuild origin metadata, without taking into lines, instead of indexes - field names
     */
    public function resortSectionPanels($params, string $viewName, string $moduleName): array
    {
        if (!is_array($params)) {
            return array();
        }

        $result = array();

        foreach ($params as $panelLabel => $panel) {
            foreach ($panel as $rows) {
                foreach ($rows as $fields) {
                    $fieldName = $fields['field']['name'];
                    if (!empty($fieldName)) {
                        $result[$panelLabel][$fieldName] = $fields;

                        if (!empty($fields['field']['customCode'])) {
                            // type from metadata takes precedence over the type from vardefs
                            $result[$panelLabel][$fieldName]['field']['type'] = $moduleName . '-' . $viewName . '-' . $fieldName;
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * The displayed title of the record
     */
    public function getRecordName(array $params): ?string
    {
        $moduleName = $params['moduleName'];
        $recordId = $params['recordId'];
        if (empty($recordId)) {
            return null;
        }

        $bean = BeanFactory::getBean($moduleName);
        $bean = $bean->retrieve($recordId);
        if (null === $bean) {
            return null;
        }
        return $bean->get_summary_text();
    }

    /**
     * Get the content of a specific attribute from a html link
     */
    public function parseHtmlAttribute(string $htmlLink, string $attribute): ?string
    {
        // annotation:
        // - Only the first occurrence of the substring is taken into account.
        // - The substring you are looking for must start with a space. There can be any number of spaces around the '=' sign.
        // - $matches[1] will become the content of the attribute
        $quotes = '"';
        if (1 === preg_match("/ $attribute *= *$quotes([^$quotes]*)$quotes/", $htmlLink, $matches)) {
            return $matches[1];
        }

        $quotes = "'";
        if (1 === preg_match("/ $attribute *= *$quotes([^$quotes]*)$quotes/", $htmlLink, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
