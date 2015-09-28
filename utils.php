<?php

  namespace DealTap;

  /**
   * \DealTap\Utils
   * A class of mathy static utility functions
   */
  class Utils
  {

    /**
     * Generate a list of $qty prime numbers up to $max
     * I could have used php-gmp advanced math functions (gmp_nextprime, etc) for this,
     * but not everyone has it installed, so I decided to stick with vanilla PHP.
     * @return array
     */
    public static function getRandomPrimes($max, $qty = 100)
    {
      if ($max < 2) {
        throw new Exception("Please choose a range maximum greater than 2");
      }
      $time_start = microtime(true);

      /*
       * NOTE:  I came up with four possible strategies for generating random, unique prime numbers.
       * The first method outlined below is the most efficient by far, the others
       * are all fairly similar as far as average execution time is concerned.
       * Of course, execution time will be affected by external factors, and is only
       * provided for a comparison of the different approaches.
       */

      /*
       * BEST METHOD (fourth attempt): average execution time 0.002s, more than 10x increased efficiency over the other strategies.
       *
       * Limit the number of primes we generate to as close to 100 as possible while
       * maintaining randomness and uniqueness.
       */

      $primes = array();
      while (sizeof($primes) < $qty) {
        $primes = array_merge($primes, self::generatePrimes($max,$qty));
        $primes = array_unique($primes); // remove any duplicates on the off-chance that some ranges overlap.
      }

      /*
       * ALTERNATE METHOD 1 (first attempt): average execution time 0.0225s
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
       * ALTERNATE METHOD 2 (second attempt): average execution time 0.0269s
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
       * ALTERNATE METHOD 3 (third attempt): average execution time 0.0213s
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
       * remove all empty array elements - this is only necessary for alternate methods #1, #2, and #3 above.
       */
      // $primes = array_filter($range_numbers);

      /*
       * Randomly reorder $primes array and grab only the first $qty
       */
      shuffle($primes);
      $primes = array_slice($primes, 0, $qty);

      $time_end = microtime(true);
      $time_to_generate = $time_end - $time_start;
      echo "$qty random & unique prime numbers generated in " . $time_to_generate . "s.\n";

      return $primes;
    }

    /**
     * Generate and return an array of $qty prime numbers between 2 and $max
     * Choose several random ranges from $min to $x (<= $max) and keep looping and generating
     * new ranges until at least 100 random unique primes have been generated.
     * The reason we can't just take the first 100 primes in the range is that every set
     * we generate would have the all same numbers!
     *
     * The biggest issue with this algorithm was that although it was significantly
     * more efficient than just looping through all of the numbers in the range,
     * the execution time varied widely because I initially used rand() twice over
     * a large range to determine both $loop_min and $loop_max. I optimized further by limiting the
     * distance between $loop_min and $loop_max to a maximum of $distance to reduce the amount of overrun above $qty.
     * @return array
     */
    public static function generatePrimes($max, $qty, $distance = 500) {
      $primes = array();

      while (sizeof($primes) < $qty) {
        // limit the distance between $loop_min and $loop_max for faster loops
        $loop_min = rand(2, $max);
        $loop_max = ($max < ($loop_min+$distance)) ? $max : $loop_min+$distance;

        for ($i = $loop_min; $i <= $loop_max; ++$i) {
          if (self::isPrime($i)) {
            $primes[] = $i;
            if (sizeof($primes) > $qty) break 2; // if there are already 100 elements in $prime, we don't need any more.
          }
        }

      }

      return $primes;
    }

    /**
     * Split the digits of $number into an array of numbers
     * @return array
     */
    public static function getDigits($number)
    {
      return str_split($number);
    }

    /**
     * Get the median value of the specified array.
     * The array should be sorted numerically.
     * @return int
     */
    public static function getMedian($array)
    {
      $array = array_filter($array, "is_numeric");
      sort($array, SORT_NUMERIC);
      $count = sizeof($array);
      $middle_value = floor(($count-1)/2);
      if($count % 2) { // if there is an odd number of digits, the middle is the median
          $median = $array[$middle_value];
      } else { // if there is an even number of digits, find the average of the middle 2 digits
          $median = (($array[$middle_value]+$array[$middle_value + 1]) / 2);
      }

      return $median;
    }

    /**
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

    /**
     * Some day mother will die and I'll get the money
     * https://www.youtube.com/watch?v=-gW513E8_6I
     * @return boolean
     */
    public static function isPalindrome($number)
    {
      if (strlen($number) <= 1) return false; //disregard any single-character palindromes - they're not *real* palindromes, IMO.
      $reverse = strrev((string) $number);

      return $number == $reverse;
    }

    /**
     * get min and max values from array and return the corresponding array keys
     * @return array
     */
    public static function arrayMinMax($array)
    {
      $most_popular_digit = array_keys($array, max($array))[0];
      $least_popular_digit = array_keys($array, min($array))[0];

      return array($most_popular_digit, $least_popular_digit);
    }
  }


  /**
   * \DealTap\FileUtils
   * A class of static utility functions for file access
   */
  class FileUtils
  {
    /**
     * Write out the provided content to the specified filename
     * @return void
     */
    public static function writeFile($filename = "filename.txt", $content = "")
    {
      file_put_contents($filename, $content);
    }
  }


  /**
   * \DealTap\MapReduceUtils
   * A class of static utility functions for map/reduce operations
   */
  class MapReduceUtils
  {
    /**
     * Applies the callback to the elements of the given array
     * @return array
     */
    public static function map($array, $callback)
    {
      return array_map($callback, $array);
    }

    /**
     * Reduce the given counts to a single array of values
     * @return array
     */
    public static function reduce($counts)
    {
      $sumCounts = function($previous, $current) {
        $digits = array_merge(array_keys($previous), array_keys($current));
        $output = array();

        foreach($digits as $digit) {
          $output[$digit] = isset($previous[$digit]) ? $previous[$digit] : 0;
          $output[$digit] += isset($current[$digit]) ? $current[$digit] : 0;
        }

        return $output;
      };

      $totals = array_reduce($counts, $sumCounts, array());

      ksort($totals, SORT_NUMERIC);

      return $totals;
    }
  }
