<?php

namespace PegNu\Api;

use GuzzleHttp\Client;
use PegNu\Api\Model\Connection;
use PegNu\Api\Model\Location;

class TransportAPI
{
    private $client, $baseUrl = 'http://transport.opendata.ch/v1/';

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
        ]);
    }

    /**
     * @param $query string
     *
     * @return Location[]
     */
    public function queryLocations($query)
    {
        $response = $this->client->request('GET', 'locations', [
            'query' => [
                'query' => $query,
            ],
        ]);

        $responseData = json_decode((string) $response->getBody(), true);
        $stations = [];

        foreach ($responseData['stations'] as $station) {
            $stations[] = Location::fromJson($station);
        }

        return $stations;
    }

    /**
     * @param Location $from          Where the connection starts
     * @param Location $to            Where the connection ends
     * @param string   $time          When the connection should depart / arrive
     * @param bool     $isArrivalTime If $time is the arrival time or the departure time
     * @param int      $limit         How many connections should be returned at most
     */
    public function getConnections($from, $to, $time, $isArrivalTime, $limit = 3)
    {
        $response = $this->client->request('GET', 'connections', [
            'query' => [
                'from'             => $from->id,
                'to'               => $to->id,
                'time'             => $time,
                'isArrivalTime'    => $isArrivalTime,
                'limit'            => $limit,
            ],
        ]);

        $responseData = json_decode((string) $response->getBody(), true);
        $connectionsRaw = $responseData['connections'];
        $connections = [];

        foreach ($connectionsRaw as $connectionRaw) {
            $connections[] = Connection::fromJson($connectionRaw);
        }

        return $connections;
    }
}
