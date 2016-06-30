<?php

namespace PegNu;

use Flight;
use PegNu\Api\Model\Location;
use PegNu\Api\TransportAPI;

class SlackHelper
{
    /**
     * Handles an interactive button call.
     *
     * @param string $interactionHash The 'callback_id' from slack which is used to identify the APCu value
     * @param string $name            Name of the button pressed
     * @param string $value           Value of the button pressed
     *
     * @return bool|array False if handling failed; else message in array form
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
                $locations_from = $data['from'];
                $locations_to = $data['to'];
                $choose_locations = $data['locations'];

                // correct location
                $chosen_location = array_filter($choose_locations, function ($item) use ($value) {
                    /* @var Location $item */
                    return $item->id == $value;
                });

                if (count($chosen_location) == 0) {
                    echo 'Error in passed location id. Location was not in selection';
                    exit;
                }

                if ($data['corrected'] == 'from') {
                    $locations_from = $chosen_location;
                } elseif ($data['corrected'] == 'to') {
                    $locations_to = $chosen_location;
                }

                // trigger asking for location again
                self::askForLocationIfUncertain($locations_from, $locations_to, $data['userFrom'], $data['userTo'], $data['time']);

                // if we get here, there is no uncertainty
                $location_from = $locations_from[0];
                $location_to = $locations_to[0];

                Flight::json(self::makeConnectionOverview($location_from, $location_to, $data['time']));
                break;
        }
    }

    /**
     * Checks if one of the locations the user entered is not specific enough. Will cancel the request if necessary.
     *
     * @param Location[] $locations_from All possible 'from' locations
     * @param Location[] $locations_to   All possible 'to' locations
     * @param string     $input_from     User input for 'from'
     * @param string     $input_to       User input for 'to'
     * @param string     $input_time     User input for 'time'
     *
     * @return bool|null True if the locations are certain
     */
    public static function askForLocationIfUncertain($locations_from, $locations_to, $input_from, $input_to, $input_time)
    {
        // Ask for exact location
        if (count($locations_from) > 1) {
            Flight::json(self::makeLocationConfirmMessage($locations_from, $input_from, $input_to, 'from', $locations_from, $locations_to, $input_time));
        } elseif (count($locations_from) == 0) {
            echo "Ich kann mit _{$input_from}_ keine Station finden";
            exit;
        }

        if (count($locations_to) > 1) {
            Flight::json(self::makeLocationConfirmMessage($locations_to, $input_to, $input_from, 'to', $locations_from, $locations_to, $input_time));
        } elseif (count($locations_to) == 0) {
            echo "Ich kann mit _{$input_to}_ keine Station finden";
            exit;
        }

        return true;
    }

    /**
     * Creates a connection overview from start and end locations with the next 3 connections.
     *
     * @param Location $from_location
     * @param Location $to_location
     * @param string   $time
     *
     * @return array
     */
    public static function makeConnectionOverview($from_location, $to_location, $time)
    {
        // TODO 'isArrivalTime'
        $connections = (new TransportApi())->getConnections($from_location, $to_location, $time, false, 3);

        $attachments = [];
        foreach ($connections as $index => $connection) {
            $attachments[] = [
                'color'     => '#000', // TODO better colors
                'thumb_url' => 'https://cdn.peg.nu/favicon.ico',
                'fields'    => [
                    [
                        'title' => 'Abfahrt',
                        'value' => $connection->from->departure,
                        'short' => true,
                    ],
                    [
                        'title' => 'Ankunft',
                        'value' => $connection->to->arrival,
                        'short' => true,
                    ],
                    [
                        'title' => 'Linie',
                        'value' => $connection->sections[0]->journey->name,
                        'short' => true,
                    ],
                    [
                        'title' => 'Dauer',
                        'value' => $connection->duration,
                        'short' => true,
                    ],
                ],
            ];
        }

        $actions = [];
        foreach ($connections as $index => $connection) {
            $actions[] = [
                'name'  => 'connection',
                'text'  => "{$connection->sections[0]->journey->name} nach {$connection->sections[0]->journey->to} um {$connection->from->departure}",
                'type'  => 'button',
                'value' => "{$index}",
            ];
        }

        $attachments[] = [
            'text'    => 'Bitte w�hle deine Verbindung:',
            'color'   => '#fff', // TODO better colors
            'actions' => $actions,
        ];

        $message = [
            'text'        => "Ihre Verbindungen von *{$from_location->name} nach {$to_location->name}*:",
            'attachments' => $attachments,
        ];

        $hash = hash('sha256', json_encode($message).(new \DateTime())->getTimestamp());

        $lastAttachmentIdx = count($message['attachments']) - 1;
        $message['attachments'][$lastAttachmentIdx]['callback_id'] = $hash;

        //TODO apcu_store();

        return $message;
    }

    /**
     * @param Location[] $selectableLocations Possible locations for user input
     * @param string     $unclearLocationStr  User input
     * @param string     $otherLocationStr    The other location passed by the user
     * @param string     $correctedLocation   The location that is getting corrected (from | to)
     * @param Location[] $from                All possible from locations
     * @param Location[] $to                  All possible to locations
     * @param string     $time                The time passed by the user
     *
     * @return array
     */
    public static function makeLocationConfirmMessage(array $selectableLocations, $unclearLocationStr, $otherLocationStr, $correctedLocation, $from, $to, $time)
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
                'corrected' => $correctedLocation,
                'from'      => $from,
                'to'        => $to,
                'userFrom'  => $correctedLocation == 'from' ? $unclearLocationStr : $otherLocationStr,
                'userTo'    => $correctedLocation == 'to' ? $unclearLocationStr : $otherLocationStr,
                'time'      => $time,
                'locations' => $selectableLocations,
            ],
        ];

        // TTL 300s = 60s * 5 = 5 min (User has 5 minutes to choose the station)
        apcu_store($hash, $apcu_store, 300);

        return $message;
    }
}
