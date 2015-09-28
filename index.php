<?php

  require_once("utils.php");
  require_once("animal_collection.php");
  require_once("models/animal.php");
  require_once("models/animal/goat.php");
  require_once("models/animal/sheep.php");

  define('MAX_PRIME', 10000);

  try {
    $goats = new \DealTap\Animal\Collection(["collection" => \DealTap\Utils::getRandomPrimes(MAX_PRIME), "animal" => "goat"]);
    $sheep = new \DealTap\Animal\Collection(["collection" => \DealTap\Utils::getRandomPrimes(MAX_PRIME), "animal" => "sheep"]);
  } catch (Exception $e) {
    echo $e->getMessage();
    exit();
  }

  // write out the serial numbers to the appropriate files
  $goats->writeOutSerialNumbers();
  $sheep->writeOutSerialNumbers();

  // we could use the initial collection we generated earlier, but I chose not to
  // since we might want to alter the serial numbers somehow inside the individual animal class
  // later on.  So we grab the serial numbers that exist inside the collection instead.
  $goat_serials = $goats->getSerialNumbers();
  $sheep_serials = $sheep->getSerialNumbers();

  $soulmates = array_intersect($goat_serials, $sheep_serials);

  if (sizeof($soulmates)) {
    \DealTap\FileUtils::writeFile('soulmates.txt', join(",",$soulmates));
    echo "Found " . sizeof($soulmates) . " soulmates :)\n\n";
  } else {
    echo "No soulmates found :(\n\n";
  }

  $facts = array();

  $facts[] = $goats->getFacts();
  $facts[] = $sheep->getFacts();

  foreach($facts as $animal_facts) {

    echo "FUN FACTS ABOUT " . strtoupper($animal_facts["animal_type"]) . " SERIAL NUMBERS\n";
    echo "=============================================================================\n\n";

    echo "The average length of a " . $animal_facts["animal_type"] . " serial number is: " . $animal_facts["average_serial_number_length"] . " digits.\n";
    echo "Number of palindromic " . $animal_facts["animal_type"] . " serial numbers: " . sizeof($animal_facts["palindromes"]) . "\n";
    if (sizeof($animal_facts["palindromic_serial_numbers"])) {
      echo "Palindromic ". ucfirst($animal_facts["animal_type"]) . " Serial Numbers: " . join(",",$animal_facts["palindromic_serial_numbers"]) . "\n";
    }
    echo "\n";
    echo "The distribution of each digit 0 - 9 in all of the " . $animal_facts["animal_type"] . " serial numbers is:\n\n";
    print_r($animal_facts["digit_distribution"]);
    echo "\n";
    echo "The most popular digit contained in the " . $animal_facts["animal_type"] . " serial numbers is: " . $animal_facts["most_popular_digit"] . "\n";
    echo "The least popular digit contained in the " . $animal_facts["animal_type"] . " serial numbers is: " . $animal_facts["least_popular_digit"] . "\n";

    echo "\n";

    echo "The average (mean) of the serial numbers in the " . $animal_facts["animal_type"] . " collection is " . $animal_facts["mean_serial_number"] . "\n";
    echo "The median value of the serial numbers in the " . $animal_facts["animal_type"] . " collection is " . $animal_facts["median_serial_number"] . "\n";
    echo "\nThe median value of the set of digits that comprise the average of the serial numbers\n";
    echo "in the " . $animal_facts["animal_type"] . " collection [ " . join("-",$animal_facts['mean_serial_number_digits']) . " ] is " . $animal_facts["mean_median"];

    echo "\n\n";

  }



