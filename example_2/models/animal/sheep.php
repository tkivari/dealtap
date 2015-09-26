<?php

  namespace DealTap\Animal;

  require_once("models/animal.php");

  class Sheep extends \DealTap\Animal
  {
    public function __construct($serialNumber)
    {
      parent::__construct($serialNumber);
      // add sheep-specific constructor code
    }

    /* ... stuff just for sheep ... */
  }

