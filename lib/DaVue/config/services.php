<?php

return [
    '@ApiController' => SuiteCRM\DaVue\Controllers\ApiController::class,
    '@SmartyController' => SuiteCRM\DaVue\Controllers\SmartyController::class,
    '@SmartyData' => SuiteCRM\DaVue\Services\Smarty\SmartyData::class,
    '@SmartyHandler' => SuiteCRM\DaVue\Services\Smarty\SmartyHandler::class,
];
