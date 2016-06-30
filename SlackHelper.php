<?php

namespace PegNu;

use PegNu\Api\Model\Location;

class SlackHelper
{
    /**
     * Handles an interactive button call.
     *
     * @param string $interactionHash The 'callback_id' from slack which is used to identify the APCu value
     * @param string $name            Name of the button pressed
     * @param string $value           Value of the button pressed
     *
     * @return bool|array
     */
    public static function handleInteractiveCall($interactionHash, $name, $value)
    {
        $success = false;
        $fetched = apcu_fetch($interactionHash, $success);

        if (!$success) {
            return false;
        }

        /*
         * @var string
        */
        $type = $fetched['type'];

        /*
         * @var array
         */
        $data = $fetched['data'];

        switch ($type) {
            case 'locationCorrection':


                break;
        }
    }

    /**
     * @param Location[] $selectableLocations Possible locations for user input
     * @param string     $unclearLocationStr  User input
     * @param Location[] $from                All possible from locations
     * @param Location[] $to                  All possible to locations
     * @param string     $time                The time passed by the user
     *
     * @return array
     */
    public static function makeLocationConfirmMessage(array $selectableLocations, $unclearLocationStr, $from, $to, $time)
    {
        /*
         * @var Location[]
         */
        $passedLocations = array_slice($selectableLocations, 0, 4, true);

        $actions = [];

        // Generate actions
        foreach ($passedLocations as $key => $location) {
            $actions[] = [
                'name'  => 'location',
                'text'  => $location->name,
                'type'  => 'button',
                'value' => $location->id,
                'style' => $key == 0 ? 'primary' : 'default',
            ];
        }

        // Generate message with actions
        $message = [
            'text'        => "Für _{$unclearLocationStr}_ gibt es verschiedene Möglichkeiten",
            'attachments' => [
                [
                    'text'    => 'Bitte wähle die gewünschte Station:',
                    'color'   => '#0af',
                    'actions' => $actions,
                ],
            ],
        ];

        // Create hash for temporary saving in apcu
        $hash = hash('sha256', json_encode($message).(new \DateTime())->getTimestamp());

        $message['attachments'][0]['callback_id'] = $hash;

        $apcu_store = [
            'type' => 'locationCorrection',
            'data' => [
                'from'      => $from,
                'to'        => $to,
                'time'      => $time,
                'locations' => $selectableLocations,
                'message'   => $message,
            ],
        ];

        // TTL 300s = 60s * 5 = 5 min (User has 5 minutes to choose the station)
        apcu_store($hash, $apcu_store, 300);

        return $message;
    }
}
