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

  print_r($goat_serial_numbers);

  $goat_serz = $goats->getSerialNumbers();

  //print_r($goat_serz);

  $time_end = time();
  $time_to_generate = $time_end - $time_start;
  echo "100 goats and 100 sheep branded in " . $time_to_generate . "ms";



  // file_put_contents('goat.txt', join(",",$goat_primes));
  // file_put_contents('sheep.txt', join(",",$sheep_primes));

  // $soulmates = array_intersect($goat_primes, $sheep_primes);

  // if (sizeof($soulmates)) {
  //   file_put_contents('soulmates.txt', join(",",$soulmates));

  // } else {
  //   echo "No soulmates found :(\n";
  // }