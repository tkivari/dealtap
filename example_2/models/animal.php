<?php

  namespace DealTap;

  use Exception;

  abstract class Animal
  {
    protected $serialNumber;
    protected $facts;

    public function __construct($number) {
      $this->facts = [];
      $this->setSerialNumber($number);
    }

    public function getSerialNumber()
    {
      return $this->serialNumber;
    }

    private function setSerialNumber($number)
    {
      if (!\DealTap\Utils::isPrime($number)) {
        throw new Exception('Serial Number must be a prime number');
      }
      $this->serialNumber = $number;
      $this->getInterestingFacts();
    }

    private function getInterestingFacts() {
      $this->facts[] = "The sum of the digits in the serial number is: " . \DealTap\Utils::sumDigits($this->serialNumber);
    }
  }


