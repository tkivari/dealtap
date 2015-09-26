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
    \DealTap\FileUtils::writeFile('soulmates.txt', join(",",$soulmates));
  } else {
    echo "No soulmates found :(\n";
  }

  $callback = function($animal) {
    return $animal->getSerialNumber();
  };

  /*
   * FUN FACTS
   * Some fun facts we can gather about the serial numbers in goat and sheep collections
   * Some questions I'd like to explore:
   * - What is the average length of a serial number?
   * - Are any of the serial numbers palindromes?
   * - How many times does each digit 0 - 9 appear in each set of serial numbers?
   * - What is the most popular digit in the serial numbers of each collection?
   * - What is the average (mean) of the serial numbers in each collection?
   * - What is the median of the set of digits of the mean of the serial numbers in each collection?
   */

  // Question 1:  What is the average length of a serial number?

  echo "The average length of a goat serial number is: " . $goats->getAverageSerialNumberLength() . " digits.\n";
  echo "The average length of a sheep serial number is: " . $sheep->getAverageSerialNumberLength() . " digits.\n\n";

  // Question 2: Are any of the serial numbers in either collection palindromes?
  $goat_palindromes = $goats->getPalindromicSerialNumbers();

  echo "Number of Goats with palindromic serial numbers: " . sizeof($goat_palindromes) . "\n";
  if (sizeof($goat_palindromes)) {
    $goat_palindromic_serial_numbers = array_map($callback, $goat_palindromes);
    echo "Palindromic Goat Serial Numbers: " . join(",",$goat_palindromic_serial_numbers) . "\n\n";
  }

  $sheep_palindromes = $sheep->getPalindromicSerialNumbers();
  echo "Number of Sheep with palindromic serial numbers: " . sizeof($sheep_palindromes) . "\n";
  if (sizeof($sheep_palindromes)) {
    $sheep_palindromic_serial_numbers = array_map($callback, $sheep_palindromes);
    echo "Palindromic Sheep Serial Numbers: " . join(",",$sheep_palindromic_serial_numbers) . "\n\n";
  }


  // let's try a map/reduce function to get a count of how many times each digit from 0 - 9 is used
  // in the serial number arrays for goats and sheep!

  $serialNumberDigitCount = function($serial_number) {
    return array_count_values(\DealTap\Utils::getDigits($serial_number));
  };

  $counts = array_map($serialNumberDigitCount, $goat_serials);

  //var_dump($counts);

