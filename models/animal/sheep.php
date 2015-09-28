<?php

  namespace DealTap\Animal;

  require_once("models/animal.php");

  /**
   * Sheep class.
   * A sheep is an animal.
   */
  class Sheep extends \DealTap\Animal
  {
    /**
     * Call parent constructor and set animal specific properties
     */
    public function __construct($serialNumber)
    {
      parent::__construct($serialNumber);
      $this->genus = "Ovis";
      $this->species = "Aries";
      $this->subspecies = null;
      $this->chromosomes = 54;
    }

    /* ... stuff just for sheep ... */
  }

