<?php

  namespace DealTap\Animal;

  require_once("models/animal.php");

  class Goat extends \DealTap\Animal
  {
    public function __construct($serialNumber)
    {
      parent::__construct($serialNumber);
      // add goat-specific constructor code
    }

    /* ... stuff just for goats ... */
  }