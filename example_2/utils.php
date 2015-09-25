<?php

  namespace DealTap;

  /*
   * \DealTap\Utils
   * A class of static utility functions
   */
  class Utils
  {

    /*
     * Generate a list of $qty prime numbers up to $number
     * @return array
     */
    public static function generatePrimes($number, $qty = 100) {
      // generate $primes here
      if ($number < 2) return false;
      $primes = range(2, $number);
      for ($i = 2, $max = sqrt($number); $i < $max; $i++)
      {
          if ($primes[$i - 2] == null) continue;
          for ($j = 2, $j_max = count($primes); ($k = $i * $j - 2) < $j_max; $j++) unset($primes[$k]);
      }

      var_dump($primes);
      // Randomly reorder $primes array and grab the first $qty
      shuffle($primes);
      return array_slice($primes, 0, $qty);
    }

    /*
     * Split the digits of $number into an array of numbers
     * @return array
     */
    public static function getDigits($number) {
      return str_split($number);
    }

    /*
     * Tests primality of $number
     * Based on the simple test outlined at https://en.wikipedia.org/wiki/Primality_test
     * @return boolean
     */
    public static function isPrime($number)
    {
      for ($c = 2; $c <= sqrt($number); ++$c)
      {
        if ($number % $c === 0) return false;
      }
      return true;
    }

    /*
     * Gets the sum of the digits in a given number
     * @return int
     */
    public static function sumDigits($number)
    {
      $digits = self::getDigits($number);
      return array_sum($digits);
    }

    /*
     * Gets the mean of the digits in a given number
     * @return int
     */
    public static function mean($number) {
      $sum = self::sumDigits($number);
      return $sum / sizeof(digits);
    }

    /*
     * Gets the median of the digits in a given number
     * @return int
     */
    public static function median($number)
    {
      $digits = sort(self::getDigits($number), SORT_NUMERIC);
      $middle = floor(sizeof($digits) / 2);
      $median = $array[$middle];
      if (sizeof($digits) % 2 == 0) {
        $median = ($median + $digits[$middle - 1]) / 2;
      }
      return $median;
    }
  }