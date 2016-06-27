<?php require __DIR__ . "/vendor/autoload.php";

use PegNu\Api\TransportAPI;
use GuzzleHttp\Client;

$config = require("config.php");

// OAuth endpoint
Flight::route("GET /api/v1/oauth", function () use ($config) {
    $request = Flight::request();

    $code = $request->query->code;
    if ($code == null)
        Flight::json(["status" => "error", "error" => "Code not sent"], 400);

    // Get access token
    $restClient = new Client([
        "base_uri" => "https://slack.com/api/"
    ]);

    $response = $restClient->request("POST", "oauth.access", [
        "form_params" => [
            "client_id" => $config["oauth_client_id"],
            "client_secret" => $config["oauth_client_secret"],
            "code" => $code,
        ]
    ]);

    $responseData = json_decode($response->getBody());

    if (isset($responseData["access_token"])) {
        echo "I don't really care about the access token but Slack gave me this one: {$responseData["access_token"]}. The app should now work in your team.";
    } else {
        echo "I could not authorize you correctly. Sorry, try again.";
    }
});

// Connections endpoint
Flight::route("POST /api/v1/connections", function () use ($config) {
    $request = Flight::request();

    $token = $request->data->token;
    if ($token !== $config["slack_token"])
        Flight::json(["status" => "error", "error" => "Unauthorized"], 401);

    // From this point on we know the request is coming from slack and valid
    $transportApi = new TransportAPI();

});

Flight::route("GET /api/v1/ping", function () {
    echo "Pong.";
});

// *** important ***
Flight::start();