<?php

  error_reporting(E_STRICT);

  require_once("utils.php");
  require_once("animal_collection.php");
  require_once("models/animal/goat.php");
  require_once("models/animal/sheep.php");

  define('MAX_PRIME', 10000);

  try {
    $goats = new \DealTap\Animal\Collection(["collection" => \DealTap\Utils::generatePrimes(MAX_PRIME), "animal" => "goat"]);
    $sheep = new \DealTap\Animal\Collection(["collection" => \DealTap\Utils::generatePrimes(MAX_PRIME), "animal" => "sheep"]);
  } catch (Exception $e) {
    echo $e->getMessage();
    exit();
  }

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
    echo "Found " . sizeof($soulmates) . " soulmates :)\n\n";
  } else {
    echo "No soulmates found :(\n\n";
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


  // Question 3: How many times does each digit 0 - 9 appear in each set of serial numbers?
  // let's try a map/reduce function to find out!

  $serialNumberDigitCount = function($serial_number) {
    return array_count_values(\DealTap\Utils::getDigits($serial_number));
  };

  $counts = \DealTap\MapReduceUtils::map($goat_serials, $serialNumberDigitCount);
  $totals = \DealTap\MapReduceUtils::reduce($counts);

  print_r($totals);


