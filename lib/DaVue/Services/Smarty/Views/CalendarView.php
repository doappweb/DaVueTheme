<?php

namespace SuiteCRM\DaVue\Services\Smarty\Views;

use SuiteCRM\DaVue\App;
use SuiteCRM\DaVue\Domain\Modules\CalendarHandler;

class CalendarView implements ViewHandlerInterface
{
    /** @var App */
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function handle($params): array
    {
        /** @var CalendarHandler $handler */
        $handler = $this->app->getService(CalendarHandler::class);
        return $handler->calendar($params);
    }
}
