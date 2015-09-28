<?php

  namespace DealTap;

  use Exception;

  /**
   * Animal class.
   * Every object that is an animal will extend this class.
   */
  abstract class Animal
  {
    protected $serialNumber;
    protected $genus;
    protected $species;
    protected $subspecies = null;
    protected $chromosomes;

    /**
     *
     * @return void
     */
    public function __construct($number) {
      $this->setSerialNumber($number);
    }

    /**
     * Get the animal's serial number
     * @return int
     */
    public function getSerialNumber()
    {
      return $this->serialNumber;
    }

    /**
     * Get the length of the animal's serial number
     * @return int
     */
    public function getSerialNumberLength() {
      return strlen($this->serialNumber);
    }

    /**
     * Set the animal's serial number
     * @return void
     */
    private function setSerialNumber($number)
    {
      if (!\DealTap\Utils::isPrimeNumber($number)) {
        throw new Exception('Serial Number must be a prime number');
      }
      $this->serialNumber = $number;
    }
  }


