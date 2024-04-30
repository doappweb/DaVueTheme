<?php

namespace SuiteCRM\DaVue;

use SuiteCRM\DaVue\Controllers\ControllerInterface;
use SuiteCRM\DaVue\Infrastructure\AutoLoader\AutoLoader;
use SuiteCRM\DaVue\Infrastructure\DI\Container;

class App
{
    /** @var Container */
    private $container;
    /** @var ControllerInterface  */
    private $controller;
    /** @var bool */
    public $auth = false;
    // ------- server info --------
    /** @var int */
    public $unixTime;  // timestamp now
    /** @var int */
    public $unixTimeUser;  // timestamp now as user
    /** @var int */
    public $userTZOffsetSeconds;

    public function __construct(Container $container)
    {
        AutoLoader::loadClass();
        $_SESSION['developerMode'] = true;

        $this->container = $container;
        $this->container->set(self::class, $this);

        $this->setServerInfo();
        $this->setAuth();
        $this->setController();
    }

    private function setController(): void
    {
        if ($_REQUEST['VueAjax']) {
            $controller = '@ApiController';
        } else {
            $controller = '@SmartyController';
        }

        $this->controller = $this->container->get($controller);
    }

    public function getController(): ControllerInterface
    {
        return $this->controller;
    }

    public function getConfig(string $context): array
    {
        return $this->container->getConf($context);
    }

    public function getService(string $serviceId, array $arguments = []): object
    {
        return $this->container->get($serviceId, $arguments);
    }

    private function setAuth(): void
    {
        global $current_user;

        if ($current_user->id) {
            $this->auth = true;
        }
    }

    /**
     * Server data that will be transmitted in the response of each request
     */
    private function setServerInfo(): void
    {
        global $current_user, $timedate;

        $nowDateTime = $timedate->getNow()->getTimestamp();

        $this->userTZOffsetSeconds = $timedate->getUserUTCOffset($current_user) * 60;
        $this->unixTime = $nowDateTime;
        $this->unixTimeUser = $nowDateTime + $this->userTZOffsetSeconds;
    }

    public function getServerInfo(): array
    {
        return [
            'unixTime' => $this->unixTime,
            'unixTimeUser' => $this->unixTimeUser,
        ];
    }
}
