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
     * I could have used php-gmp advanced math functions (gmp_nextprime, etc) for this,
     * but not everyone has it installed, so I decided to stick with vanilla PHP.
     * @return array
     */
    public static function generatePrimes($max, $qty = 100) {
      if ($max < 2) {
        throw new Exception("Please choose a range maximum greater than 2");
      }
      $time_start = microtime(true);

      /*
       * NOTE:  I came up with four possible strategies for generating random, unique prime numbers.
       * The fourth method outlined below is the most efficient by far, the others
       * are all fairly similar as far as average execution time is concerned.
       */

      /*
       * METHOD 1: average execution time 0.0225s
       *
       * set a range and then remove the non-prime values
       */

      // $range_numbers = range(2, $max);
      // foreach($range_numbers as &$range_number) {
      //   if(!self::isPrime($range_number)) {
      //     $range_number = null;
      //   }
      // }
      // unset($range_number);


      /*
       * METHOD 2: average execution time 0.0269s
       *
       * set a range and use array_map to check each number in the range for primality, leaving
       * array of only prime numbers
       */

      // $range_numbers = range(2,$max);
      // $callback = function($number) {
      //   return self::isPrime($number) ? $number : null;
      // };
      // $range_numbers = array_map($callback, $range_numbers);


      /*
       * METHOD 3: average execution time 0.0213s
       *
       * loop through the numbers 2 to $max and check each one for primality.  If it is
       * a prime number, add it to $range_numbers
       */

      // $range_numbers = array();

      // for ($i = 2; $i <= $max; ++$i) {
      //   if (self::isPrime($i)) {
      //     $range_numbers[] = $i;
      //   }
      // }

      /*
       * METHOD 4: average execution time 0.004s, almost 200% increased efficiency! :)
       *
       * choose several random ranges from $min to $x and keep looping and generating
       * new ranges until at least 100 random unique primes have been generated.
       * The biggest issue with this algorithm was that although it was significantly
       * more efficient than just looping through all of the numbers in the range,
       * the execution time varied widely because I initially used of rand() twice over
       * a large range to determine both $min and $x. I optimized further by limiting the
       * distance between $min and $x to a maximum of 500.  Setting this distance to
       * less than 500 seems to provide only a minimal improvement.
       */

      $primes = array();

      while (sizeof($primes) < 100) {
        $min = rand(2, $max);
        $mx = ($max < ($min+500)) ? $max : $min+500; // this is how I limit the distance between $min and $x
        $x = rand($number_1, $mx);

        for ($i = $min; $i <= $x; ++$i) {
          if (self::isPrime($i)) {
            $primes[] = $i;
          }
        }

        $primes = array_unique($primes); // remove any duplicate primes on the off-chance that some ranges overlap.

      }


      /*
       * remove all empty array elements - this is only necessary for methods #1, #2, and #3 above.
       */
      // $primes = array_filter($range_numbers);

      /*
       * Randomly reorder $primes array and grab the first $qty
       */
      shuffle($primes);
      $primes = array_slice($primes, 0, $qty);

      $time_end = microtime(true);
      $time_to_generate = $time_end - $time_start;
      echo "100 random & unique prime numbers generated in " . $time_to_generate . "s.\n";

      return $primes;
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

  /*
   * \DealTap\FileUtils
   * A class of static utility functions for file access
   */
  class FileUtils
  {
    public static function writeFile($filename = "filename.txt", $content = "")
    {
      file_put_contents($filename, $content);
    }
  }
