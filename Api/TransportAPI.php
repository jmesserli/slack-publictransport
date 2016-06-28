<?php

namespace PegNu\Api;

use GuzzleHttp\Client;
use PegNu\Api\Model\Location;

class TransportAPI
{
    private $client, $baseUrl = "http://transport.opendata.ch/v1/";

    public function __construct()
    {
        $this->client = new Client([
            "base_uri" => $this->baseUrl
        ]);
    }

    /**
     * @param $query string
     * @return Location[]
     */
    public function queryLocations($query)
    {
        $response = $this->client->request("GET", "locations", [
            "query" => [
                "query" => $query
            ]
        ]);

        $responseData = json_decode((string)$response->getBody(), true);
        $stations = [];

        foreach ($responseData["stations"] as $station)
            $stations[] = Location::fromJson($station);

        return $stations;
    }
    /**
     * @param Location $from 
     * @param Location $to 
     * @param string $time 
     * @param bool $isArrivalTime 
     */
    public function getConnections($from, $to, $time, $isArrivalTime)
    {
        $response = $this->client->request("GET", "connections", [
            "query" => [
                "from" => $from["id"],
                "to" => $to["id"],
                "time" => $time,
                "isArrivalTime" => $isArrivalTime
            ]
        ]);


    }
}