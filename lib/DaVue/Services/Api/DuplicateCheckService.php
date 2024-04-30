<?php

namespace SuiteCRM\DaVue\Services\Api;
use Exception;

/**
 * Imitation of a duplicate search mechanism before saving a record as in the original system
 */
class DuplicateCheckService
{
    /**
     * Returns a list with duplicate field values if any are found
     * The search is performed based on field values from POST
     *
     * @throws Exception
     * @usage http://localhost/index.php?VueAjax=1&method=checkForDuplicates&arg[]...
     * @param $args
     * @return array|false
     */
    public function checkForDuplicates($args)
    {
        global $beanList;

        if (empty($args['module'])) {
            throw new Exception("Required parameter 'module' was not passed");
        }
        $moduleName = $args['module'];

        // Examples: AccountFormBase, ContactFormBase, LeadFormBase
        $moduleFormBaseName = $beanList[$moduleName] . 'FormBase';
        $moduleFormBaseFile = "modules/$moduleName/$moduleFormBaseName.php";

        if (!file_exists($moduleFormBaseFile)) {
            throw new Exception("There is no '$moduleFormBaseName' form handler for the '$moduleName' module");
        }

        $moduleFormBase = new $moduleFormBaseName();
        $duplicateRows = $moduleFormBase->checkForDuplicates('');
        if (null === $duplicateRows) {
            return false;
        }

        return $duplicateRows;
    }
}
