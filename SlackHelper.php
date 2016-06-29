<?php

namespace PegNu;

use PegNu\Api\Model\Location;

class SlackHelper
{
    /**
     * @param Location[] $locations
     *
     * @return array
     */
    public static function makeLocationConfirmMessage(array $locations, $unclearLocation, $from, $to, $time)
    {
        /*
         * @var Location[]
         */
        $passedLocations = array_slice($locations, 0, 4, true);

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
            'text'        => "Für _{$unclearLocation}_ gibt es verschiedene Möglichkeiten",
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
                'locations' => $locations,
                'message'   => $message,
            ],
        ];

        // TTL 300s = 60s * 5 = 5 min
        apcu_store($hash, $apcu_store, 300);

        return $message;
    }
}
