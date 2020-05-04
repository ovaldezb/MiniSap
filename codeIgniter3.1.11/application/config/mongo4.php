<?php
require 'vendor/autoload.php'; // include Composer's autoloader

$client = new MongoDB\Client("mongodb://127.0.0.1");
$collection = $client->demo->beers;

$result = $collection->find();

foreach ($result as $entry) {
    echo $entry['_id'], ': ', $entry['name'], "\n";
}

error_reporting(E_ALL);

?>
