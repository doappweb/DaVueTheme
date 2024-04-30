<?php

namespace SuiteCRM\DaVue\Domain\Dashlets;

use Dashlet;

interface DashletHandlerInterface
{
    /**
     * @param Dashlet $focus
     * @return array
     */
    public function configure(Dashlet $focus): array;

    /**
     * @param string $dashletId
     * @param array $dashletData
     * @param array $dashletOptions
     * @param string $dashletType
     * @return array
     */
    public function display($dashletId, $dashletData, $dashletOptions, $dashletType): array;
}
