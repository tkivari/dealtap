<?php

  require_once("utils.php");
  require_once("animal_collection.php");
  require_once("models/animal/goat.php");
  require_once("models/animal/sheep.php");

  define('MAX_PRIME', 10000);

  $time_start = time();

  try {
    $goats = new \DealTap\Animal\Collection(["collection" => \DealTap\Utils::generatePrimes(MAX_PRIME), "animal" => "goat"]);
    $sheep = new \DealTap\Animal\Collection(["collection" => \DealTap\Utils::generatePrimes(MAX_PRIME), "animal" => "sheep"]);
  } catch (Exception $e) {
    echo $e->getMessage();
    exit();
  }

  $time_end = time();
  $time_to_generate = $time_end - $time_start;
  echo "\n100 goats and 100 sheep branded in " . $time_to_generate . "ms.\n\n";

  // write out the serial numbers to the appropriate files
  $goats->writeOutSerialNumbers();
  $sheep->writeOutSerialNumbers();

  // we could use the initial collection we generated earlier, but I chose not to
  // since we might want to alter the serial numbers somehow inside the class
  // later on.  So we grab the serial numbers that exist inside the collection instead.
  $goat_serials = $goats->getSerialNumbers();
  $sheep_serials = $sheep->getSerialNumbers();

  $soulmates = array_intersect($goat_serials, $sheep_serials);

  if (sizeof($soulmates)) {
    file_put_contents('soulmates.txt', join(",",$soulmates));

  } else {
    echo "No soulmates found :(\n";
  }