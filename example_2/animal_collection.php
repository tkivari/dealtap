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
      $this->filename = $options["animal"] . ".txt" || "filename.txt";

      $animal_class = __NAMESPACE__ . '\\' . ucfirst(strtolower($options["animal"]));

      foreach ($options["collection"] as $serialNumber) {
        if (class_exists($animal_class)) {
          $this->addAnimal(new $animal_class($serialNumber));
        } else {
          throw new Exception($animal_class . " does not exist!");
        }
      }


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
  }