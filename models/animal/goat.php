<?php

  namespace DealTap\Animal;

  /**
   * Goat class.
   * A goat is an animal.
   */
  class Goat extends \DealTap\Animal
  {
    /**
     * Call parent constructor and set animal specific properties
     */
    public function __construct($serialNumber)
    {
      parent::__construct($serialNumber);
      $this->genus = "Capra";
      $this->species = "Aegagrus";
      $this->subspecies = "Hircus";
      $this->chromosomes = 60;
    }

    /* ... stuff just for goats ... */
  }