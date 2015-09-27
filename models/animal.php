<?php

  namespace DealTap;

  use Exception;

  abstract class Animal
  {
    protected $serialNumber;

    public function __construct($number) {
      $this->setSerialNumber($number);
    }

    public function getSerialNumber()
    {
      return $this->serialNumber;
    }

    public function getSerialNumberLength() {
      return strlen($this->serialNumber);
    }

    private function setSerialNumber($number)
    {
      if (!\DealTap\Utils::isPrime($number)) {
        throw new Exception('Serial Number must be a prime number');
      }
      $this->serialNumber = $number;
    }
  }


