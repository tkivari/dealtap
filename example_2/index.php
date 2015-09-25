<?php

  require_once("models/animal/goat.php");
  require_once("models/animal/sheep.php");
  require_once("utils.php");

  $time_start = time();
  $goat_primes = \DealTap\Utils::generatePrimes(10000);
  $sheep_primes = \DealTap\Utils::generatePrimes(10000);
  $time_end = time();
  $time_to_generate = $time_start - $time_end;
  echo "100 goats and 100 sheep branded in " . $time_to_generate . "ms";

  $goats = $sheep = array();

  foreach($goat_primes as $prime) {
    $goats[] = new \DealTap\Goat($prime);
  }

  foreach($sheep_primes as $prime) {
    $sheep[] = new \DealTap\Sheep($prime);
  }

  file_put_contents('goat.txt', join(",",$goat_primes));
  file_put_contents('sheep.txt', join(",",$sheep_primes));

  $soulmates = array_intersect($goat_primes, $sheep_primes);

  if (sizeof($soulmates)) {
    file_put_contents('soulmates.txt', join(",",$soulmates));

  } else {
    echo "No soulmates found :(\n";
  }

  foreach($sheep_primes as $prime) {
    $sheep[] = new \DealTap\Sheep($prime);
  }