<?php

  namespace DealTap\Animal;

  use Exception;

  /**
   * A collection to store and manage animals.
   */
  class Collection
  {
    private $collection = array();
    private $filename;

    public function __construct($options)
    {
      $this->filename = $options["animal"] ? $options["animal"] . ".txt" : "filename.txt";
      $this->animal = $options["animal"];
      $animal_class = __NAMESPACE__ . '\\' . ucfirst(strtolower($this->animal));
      $this->buildCollectionFromSerialNumbers($options["collection"], $animal_class);
    }

    /**
     * Add an animal to this collection
     * @return void
     */
    public function addAnimal($animal)
    {
      $this->collection[] = $animal;
    }

    /**
     * Return a list of this collection's animals
     * @return array
     */
    public function getAnimals()
    {
      return $this->collection;
    }

    /**
     * Return an array of the serial numbers in this collection
     * @return array
     */
    public function getSerialNumbers()
    {
      $callback = function($animal) {
        return $animal->getSerialNumber();
      };

      return array_map($callback, $this->collection);
    }

    /**
     * Return the average length of the serial numbers in the collection
     * This function could use array_reduce or a foreach loop to sum the serial numbers.
     * @return int
     */
    public function getAverageSerialNumberLength()
    {
      $sum = array_reduce($this->collection, function($i, $animal)
      {
          return $i += $animal->getSerialNumberLength();
      });

      return number_format((float)($sum / sizeof($this->collection)), 2);
    }

    /**
     * Get the average (mean) of the serial numbers in the collection
     * @return int
     */
    public function getSerialNumberAverage()
    {
      return array_sum($this->getSerialNumbers()) / sizeof($this->collection);
    }

    /**
     * Write out serial numbers from collection to the appropriate file
     * @return void
     */
    public function writeOutSerialNumbers()
    {
      $serial_numbers = $this->getSerialNumbers();
      \DealTap\FileUtils::writeFile($this->filename, join(",",$serial_numbers));
    }

    /**
     * Build an array of facts about the serial numbers in the collection
     * @return array
     */
    public function getFacts()
    {
      /*
       * FUN FACTS
       * Some questions to explore:
       * 1 - What is the average length of a serial number?
       * 2 - Are any of the serial numbers palindromes?
       * 3 - How many times does each digit 0 - 9 appear in the collection of serial numbers?
       * 4 - What is the most popular digit in the serial numbers of the collection?
       * 5 - What is the least popular digit in the serial numbers of the collection?
       * 6 - What is the average (mean) of the serial numbers in the collection?
       * 7 - What is the median of the set of digits of the mean of the serial numbers in the collection?
       */

      $facts = [
        "animal_type" => $this->animal
      ];

      $serial_numbers = $this->getSerialNumbers();

      // Question 1:  What is the average length of a serial number?

      $facts['average_serial_number_length'] = $this->getAverageSerialNumberLength();

      // Question 2: Are any of the serial numbers in either collection palindromes?

      list($facts['palindromes'], $facts['palindromic_serial_numbers']) = $this->getPalindromeInformation();

      // Question 3: How many times does each digit 0 - 9 appear in the set of serial numbers?
      // let's try a map/reduce function to find out!

      $facts['digit_distribution'] = $this->getDigitDistribution($serial_numbers);

      // Questions 4 & 5:  What are the most and least popular digits found in the serial numbers in the collection?

      list($facts['most_popular_digit'], $facts['least_popular_digit']) = \DealTap\Utils::arrayMinMax($facts['digit_distribution']);

      // Question 6: What is the average (mean) of the serial numbers in the collection?

      $facts['mean_serial_number'] = $this->getSerialNumberAverage();

      $facts['mean_serial_number_digits'] = \DealTap\Utils::getDigits(str_replace(".","",$facts['mean_serial_number']));
      sort($facts['mean_serial_number_digits'], SORT_NUMERIC);

      $serial_numbers_sorted = $serial_numbers;
      sort($serial_numbers_sorted);

      $facts['median_serial_number'] = $this->getMedian($serial_numbers);

      // Question 7: What is the median of the set of digits of the mean of the serial numbers in the collection?

      $facts['mean_median'] = $this->getMedian($facts['mean_serial_number_digits']);

      return $facts;
    }

    /**
     * Get the median of the set of $digits
     * @return int
     */
    private function getMedian($digits)
    {
      return \DealTap\Utils::getMedian($digits);
    }

    /**
     * Get the digit distribution from 0 - 9 in the serial numbers in the collection
     * @return array
     */
    private function getDigitDistribution($serial_numbers)
    {
      $serialNumberDigitCount = function($serial_number) {
        return array_count_values(\DealTap\Utils::getDigits($serial_number));
      };

      $counts = \DealTap\MapReduceUtils::map($serial_numbers, $serialNumberDigitCount);
      $totals = \DealTap\MapReduceUtils::reduce($counts);

      return $totals;
    }

    /**
     * Get Information about the palindromes
     * @return array
     */
    private function getPalindromeInformation()
    {
      $palindromes = $this->getPalindromicSerialNumbers();
      $palindromic_serial_numbers = array();

      if (sizeof($palindromes)) {
        $callback = function($animal) {
          return $animal->getSerialNumber();
        };
        $palindromic_serial_numbers = array_map($callback, $palindromes);
      }

      return array($palindromes, $palindromic_serial_numbers);
    }

    /**
     * Get palindromic serial numbers
     * @return array
     */
    private function getPalindromicSerialNumbers()
    {
      $palindromes = [];
      foreach($this->collection as $animal) {
        if (\DealTap\Utils::isPalindrome($animal->getSerialNumber()))
          $palindromes[] = $animal;
      }

      return $palindromes;
    }

    /**
     * builds a collection of $class objects from provided serial numbers
     * @return void
     */
    private function buildCollectionFromSerialNumbers($collection, $class) {
      foreach ($collection as $serial_number) {
        if (class_exists($class)) {
          $this->addAnimal(new $class($serial_number));
        } else {
          throw new Exception($class . " does not exist!");
        }
      }
    }
  }