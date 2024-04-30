<?php

namespace SuiteCRM\DaVue\Services\Smarty\Views;

interface ViewHandlerInterface
{
    public function handle($params): array;
}
