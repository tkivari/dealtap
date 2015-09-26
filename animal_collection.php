<?php

  namespace DealTap\Animal;

  use Exception;

  /*
   * A collection to store animals
   */
  class Collection
  {
    private $collection = array();
    private $filename;

    public function __construct($options)
    {
      $this->filename = $options["animal"] ? $options["animal"] . ".txt" : "filename.txt";
      $animal_class = __NAMESPACE__ . '\\' . ucfirst(strtolower($options["animal"]));
      $this->buildCollectionFromSerialNumbers($options["collection"], $animal_class);
    }

    /*
     * Add an animal to this collection
     * @return void
     */
    public function addAnimal($animal)
    {
      $this->collection[] = $animal;
    }

    /*
     * Return a list of this collection's animals
     * @return array
     */
    public function getAnimals()
    {
      return $this->collection;
    }

    /*
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

    /*
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

    /*
     * Write out serial numbers from collection to the appropriate file
     * @return void
     */
    public function writeOutSerialNumbers()
    {
      $serial_numbers = $this->getSerialNumbers();
      \DealTap\FileUtils::writeFile($this->filename, join(",",$serial_numbers));
    }

    /*
     * Get palindromic serial numbers
     * @return array
     */
    public function getPalindromicSerialNumbers()
    {
      $palindromes = [];
      foreach($this->collection as $animal) {
        if (\DealTap\Utils::isPalindrome($animal->getSerialNumber()))
          $palindromes[] = $animal;
      }
      return $palindromes;
    }



    /*
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