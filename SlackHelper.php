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

        foreach ($passedLocations as $key => $location) {
            $actions[] = [
                'name'  => 'location',
                'text'  => $location->name,
                'type'  => 'button',
                'value' => $location->id,
                'style' => $key == 0 ? 'primary' : 'default',
            ];
        }

        $message = [
            'text'        => "F�r _{$unclearLocation}_ gibt es verschiedene M�glichkeiten",
            'attachments' => [
                [
                    'text'    => 'Bitte w�hle die gew�nschte Station:',
                    'color'   => '#0af',
                    'actions' => $actions,
                ],
            ],
        ];

        $hash = hash('sha256', json_encode($message).(new \DateTime())->getTimestamp());

        $message['callback_id'] = $hash;

        $apc_store = [
            'type' => 'locationCorrection',
            'data' => [
                'from'      => $from,
                'to'        => $to,
                'time'      => $time,
                'locations' => $locations,
                'message'   => $message,
            ],
        ];

        apc_store($hash, $apc_store);

        return $message;
    }
}
