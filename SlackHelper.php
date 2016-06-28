<?php
namespace PegNu;

use PegNu\Api\Model\Location;

class SlackHelper
{
	/**
	 * @param Location[] $locations
	 */
	public static function makeLocationConfirmMessage(array $locations)
	{
		/**
		 * @var Location[]
		 */
		$passedLocations = array_slice($locations, 0, 4, true);

		$actions = [];

		foreach ($passedLocations as $location) {
			$actions[] = [
				"name" => "location",
				"text" => $location->name,
				"type" => "button",
				"value" => $location->id,
				"style" => "primary" // TODO
			];
		}

		$message = [
			"text" => "Für _..._ gibt es verschiedene Möglichkeiten",
			"attachments" => [
				[
					"text" => "Bitte wähle die gewünschte Station:",
					"color" => "#0af",
					"actions" => $actions
				]
			]
		];

		// TODO apc, return
	}
}
