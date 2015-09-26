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

    public function writeOutSerialNumbers()
    {
      $serial_numbers = $this->getSerialNumbers();
      file_put_contents($this->filename, join(",",$serial_numbers));
    }

    /*
     * builds a collection of $class objects from provided serial numbers
     * @return void
     */
    private function buildCollectionFromSerialNumbers($collection, $class) {
      foreach ($collection as $serialNumber) {
        if (class_exists($class)) {
          $this->addAnimal(new $class($serialNumber));
        } else {
          throw new Exception($class . " does not exist!");
        }
      }
    }
  }