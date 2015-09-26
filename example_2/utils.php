<?php

  namespace DealTap;

  /*
   * \DealTap\Utils
   * A class of mathy static utility functions
   */
  class Utils
  {

    /*
     * Generate a list of $qty prime numbers up to $number
     * @return array
     */
    public static function generatePrimes($max, $qty = 100) {
      if ($max < 2) {
        throw new Exception("There are no prime numbers below 2");
      }

      $range_numbers = range(2, $max);
      foreach($range_numbers as &$range_number) {
        if(!self::isPrime($range_number)) {
          $range_number = null;
        }
      }
      unset($range_number);

      //remove all empty array elements
      $primes = array_filter($range_numbers);

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
      for ($count = 2; $count <= sqrt($number); ++$count)
      {
        if ($number % $count === 0) return false;
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

    /*
     * Some day mother will die and I'll get the money
     * https://www.youtube.com/watch?v=-gW513E8_6I
     * @return boolean
     */
    public static function isPalindrome($number) {
      if (strlen($number) <= 1) return false; //disregard any single-character palindromes - they're not *real* palindromes, IMO.
      $reverse = strrev((string) $number);
      return $number == $reverse;
    }
  }