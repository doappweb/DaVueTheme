<?php

namespace SuiteCRM\DaVue\Services\Api;

use BeanFactory;
use SuiteCRM\DaVue\Domain\Modules\CalendarHandler;


class CalendarService
{
    /** @var CalendarHandler */
    private $calendarHandler;

    public function __construct(CalendarHandler $calendarHandler)
    {
        $this->calendarHandler = $calendarHandler;
    }

    /**
     * @usage http://localhost:3000/index.php?module=Calls&VueAjax=1&method=saveActivity&arg[]...
     * @param $args
     * @return array|null
     */
    public function saveActivity($args): ?array
    {
        if (empty($args)) {
            return null;
        }

        $args = $this->setDateStartInDBFormat($args);
        $args = $this->setEmptyDuration($args);
        $args = $this->setDateEnd($args);

        $bean = BeanFactory::getBean($args['module'], $args['record']);
        $bean->fromArray($args);

        if ($bean->save()) {
            $this->setRelations($bean);
        } else {
            $GLOBALS['log']->fatal("Error creating a calendar entry");

            return null;
        }

        return $this->calendarHandler->buildActivityItems($bean);
    }

    /**
     * @param $bean
     * @return void
     */
    private function setRelations($bean): void
    {
        global $current_user;

        if ($bean->module_dir === 'Tasks' || $bean->module_dir === 'FP_events') {
            return;
        }

        $bean->load_relationship('users');
        $additional_fields = array(
            'accept_status' => 'accept'
        );

        $bean->users->add($current_user->id, $additional_fields);
    }

    /**
     * @param $args
     * @return array
     */
    private function setDateEnd($args): array
    {
        global $timedate;

        if ($args['module'] !== 'FP_events') {
            return $args;
        }

        if ($args['duration']) {
            $duration = $args['duration'];
        } else if (isset($args['duration_hours']) && isset($args['duration_minutes'])) {
            $duration = (int)$args['duration_hours'] * 3600 + (int)$args['duration_minutes'] * 60;
        } else {
            $duration = '0';
        }

        $args['end_date'] = $timedate->fromDb($args['start_date'])->modify('+' . $duration . ' seconds')->asDb();

        return $args;
    }

    /**
     * @param array $args
     * @return array
     */
    private function setEmptyDuration(array $args): array
    {
        if (isset($args['duration_hours']) && $args['duration_hours'] === '0') {
            $args['duration_hours'] = '00';
        }

        if (isset($args['duration_minutes']) && $args['duration_minutes'] === '0') {
            $args['duration_minutes'] = '00';
        }

        return $args;
    }

    /**
     * @param array $args
     * @return array
     */
    private function setDateStartInDBFormat(array $args): array
    {
        global $timedate;

        $startDateTime = $timedate->fromUser($args['date_start']);
        if ($args['module'] === 'FP_events') {
            $args['start_date'] = $timedate->asDb($startDateTime);
        } else {
            $args['date_start'] = $timedate->asDb($startDateTime);
        }

        return $args;
    }
}
