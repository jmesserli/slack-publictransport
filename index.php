<?php

require __DIR__.'/vendor/autoload.php';

use GuzzleHttp\Client;
use PegNu\Api\TransportAPI;

$config = require 'config.php';

// Sentry Error Reporting
$ravenClient = new Raven_Client($config['sentry_dsn']);
$errorHandler = new Raven_ErrorHandler($ravenClient);

Flight::route('GET /api/v1/oauth', function () use ($config) {
    $request = Flight::request();

    $code = $request->query->code;
    if ($code == null) {
        Flight::json(['status' => 'error', 'error' => 'Code not sent'], 400);
    }

    // Get access token
    $restClient = new Client([
        'base_uri' => 'https://slack.com/api/',
    ]);

    $response = $restClient->request('POST', 'oauth.access', [
        'form_params' => [
            'client_id'     => $config['oauth_client_id'],
            'client_secret' => $config['oauth_client_secret'],
            'code'          => $code,
        ],
    ]);

    $responseData = json_decode((string) $response->getBody(), true);

    if (isset($responseData['access_token'])) {
        Flight::json(['status' => 'ok']);
    } else {
        Flight::json(['status' => 'error', 'error' => 'Authorization failed']);
    }
});

Flight::route('POST /api/v1/connections', function () use ($config) {
    $request = Flight::request();

    $token = $request->data->token;
    if ($token !== $config['slack_token']) {
        Flight::json(['status' => 'error', 'error' => 'Unauthorized'], 401);
    }

    // From this point on we know the request is coming from slack
    $commandParameters = $request->data->text;

    $regexMatches = [];
    preg_match_all('/(\\w+)|"([\\w ]+)"/', $commandParameters, $regexMatches, PREG_PATTERN_ORDER);
    $matchCount = count($regexMatches[0]);

    $parsedParams = [];
    for ($i = 0; $i < $matchCount; $i++) {
        if ($regexMatches[1][$i] != '') {
            $parsedParams[] = $regexMatches[1][$i];
        } else {
            $parsedParams[] = $regexMatches[2][$i];
        }
    }

    if (count($parsedParams) < 2) {
    } // TODO Error & Exit

    $transportApi = new TransportAPI();
    $locations_from = $transportApi->queryLocations($parsedParams[0]);
    $locations_to = $transportApi->queryLocations($parsedParams[1]);

    // Ask for exact location

    if (count($locations_from) > 1) {
    } // TODO ask to choose location
    elseif (count($locations_from) == 0) {
    } // TODO cancel and ask for better location

    if (count($locations_to) > 1) {
    } // TODO ask to choose location
    elseif (count($locations_to) == 0) {
    } // TODO cancel and ask for better location

    $connections = $transportApi->getConnections($locations_from[0], $locations_to[0], isset($parsedParams[2]) ? $parsedParams[2] : null, false);
    // TODO Transform connection data and set APC

    Flight::json($connections);
});

Flight::route('POST /api/v1/interactive', function () use ($config) {
    $request = Flight::request();

    $token = $request->data->token;
    if ($token !== $config['slack_token']) {
        Flight::json(['status' => 'error', 'error' => 'Unauthorized'], 401);
    }

    echo 'Not Yet Implemented';
});

Flight::route('/api/v1/ping', function () {
    echo 'Pong.';
});

Flight::route('/', function () {
    Flight::render('index.view.php');
});

Flight::route('error', function (Exception $ex) use ($errorHandler) {
    $errorHandler->handleException($ex, true, Flight::request());

    Flight::json(['status' => 'error', 'error' => 'Internal Server Error'], 500);
});

// *** important ***
Flight::start();
