<?php
/**
 * This PHP implementation of the LZW compression algorithm allows for
 * the compression of bytes using the Adobe PDF characteristics according
 * to the "PDF Reference Second Edition" by Adobe Systems Incorporated (2000)
 * 
 * This code is primarily a conversion from Mark Nelson's LZW.c which can be found here:
 *       http://marknelson.us/attachments/lzw-data-compression/lzw.c
 *  Do not contact Mr. Nelson regarding this package, because this code was written by Samuel Shull
 * 
*/
/** 
 * @package LZW
 * @example  /lzwexample.php  Example usage of this class.
 * @category   Numbers
 * @author Sam Shull <samshull@samshull.com>
 * @copyright Copyright (c) 2007, Sam Shull
 * @license http://www.samshull.com/bsdlicense.txt BSD License
 * @link       http://samshull.com/lzwexample.php
 * @version    0.9
 * @access     public
*/
class LZW{ 
/**
 * Table for storing codes
 *
 * @var array
 * @access protected
 */
	var $code_value = array();
/**
 * Table for storing prefixes to codes
 *
 * @var array
 * @access protected
 */
	var $prefix_code = array();
/**
 * Table for storing individual characters
 *
 * @var array
 * @access protected
 */
	var $append_character = array();
/**
 * Output
 *
 * @var string
 * @access protected
 */
	var $out = "";
/**
 * Total size of table of values
 *
 * @var integer
 * @access protected
 */
	var $TABLE_SIZE = 5021;
/**
 * Number of bits available for encoding
 *
 * @var integer
 * @access protected
 */
	var $output_bit_count = 0;
/**
 * The actual bits for encoding
 *
 * @var string
 * @access protected
 */
	var $output_bit_buffer = "0";
/**
 * Next code in the table
 *
 * @var integer
 * @access protected
 */
	var $next_code = 256;
/**
 * Decoding: the table
 *
 * @var array
 * @access protected
 */
	var $sTable = array();
/**
 * Data to be decoded
 *
 * @var string
 * @access protected
 */
	var $data = NULL;
/**
 * Decoding: next code (same as $next_code)
 *
 * @var integer
 * @access protected
 */
	var $tIdx;
/**
 * bits in next code
 *
 * @var integer
 * @access protected
 */
	var $bitsToGet = 9;
/**
 * Position holder within data string
 *
 * @var string
 * @access protected
 */
	var $bytePointer;
/**
 * Position holder for bits in data string
 *
 * @var string
 * @access protected
 */
	var $bitPointer;
/**
 * Next value to be decoded
 *
 * @var integer
 * @access protected
 */
	var $nextData = 0;
/**
 * Next number of bits to be decoded
 *
 * @var string
 * @access protected
 */
	var $nextBits = 0;
/**
 * Table of max bit values per number of bits
 *
 * @var string
 * @access protected
 */
	var $andTable = array(511, 1023, 2047, 4095);
/**
  * Method: compress
  *      The primary method used by this class, accepts only a string as input and 
  *      returns the string compressed. 
  */
function compress($string){
  $this->output_code(256);
  $this->input = $string;

  $this->next_code=258;              /* Next code is the next available string code*/
  $string_code=ord($this->input{0});    /* Get the first code                         */

  for($i=1;$i<=strlen($this->input);$i++)
  {
	$character=ord($this->input{$i});
    $index=$this->find_match($string_code,$character);/* See if the string is in */
    if (isset($this->code_value[$index]))            /* the table.  If it is,   */
      $string_code=$this->code_value[$index];        /* get the code value.  If */
    else                                    /* the string is not in the*/
    {                                       /* table, try to add it.   */
      if ($this->next_code <= 4094)
      {
		$this->code_value[$index]=$this->next_code;
        $this->prefix_code[$index]=$string_code;
        $this->append_character[$index]=$character;
		$this->next_code++;
      }else{
	     $this->output_code(256);
		 $this->next_code = 258;
		 $this->code_value = array();
         $this->prefix_code = array();
         $this->append_character = array();
		 
		 $this->code_value[$index]=$this->next_code;
         $this->prefix_code[$index]=$string_code;
         $this->append_character[$index]=$character;
		 $this->next_code++;
	  }

      $this->output_code($string_code);  /* When a string is found  */
      $string_code=$character;            /* that is not in the table*/
    }                                   /* I output the last string*/
  }                                     /* after adding the new one*/
  
  $this->output_code(257);
  $this->output_code(0);  //Clean up
  return $this->out;
}
/**
 * Method: find_match - if PHP5 mark as private or protected
 *   Finds the matching index of the character with the table
 * @param string $hash_prefix
 * @param char $hash_character
 * @return int
 */
function find_match($hash_prefix,$hash_character){

  $index = ($hash_character << 4 ) ^ $hash_prefix;
  if ($index == 0)
    $offset = 1;
  else
    $offset = $this->TABLE_SIZE - $index;
    
	while (1){
      if (!isset($this->code_value[$index]))
        return $index;
      if ($this->prefix_code[$index] == $hash_prefix && $this->append_character[$index] == $hash_character)
        return $index;
        $index -= $offset;
      if ($index < 0)
        $index += $this->TABLE_SIZE;
    }
}
/**
 * Method: output_code - if PHP5 mark as private or protected
 *   Adds the input to the output buffer and 
 *     Adds the char code of next 8 bits of the output buffer
 * @param int $code
 */ 
function output_code($code){
	 $len = ($code < 512 ? 9 : ($code < 1024 ? 10 : ($code < 2048 ? 11 : 12)));
	 $this->output_bit_buffer = $this->bitOR($this->lshift(decbin($code),(32 - $len - $this->output_bit_count)),$this->output_bit_buffer);
     $this->output_bit_count += $len;
     while ($this->output_bit_count >= 8){
        $this->out .= chr($this->rshift($this->output_bit_buffer,24));
        $this->output_bit_buffer = $this->lshift($this->output_bit_buffer,8);
        $this->output_bit_count -= 8;
     }
}
/*/
//  The following methods are adapted directly from FPDI - Version 1.1 and are only 
    included here in order to effect the decompression of the above encoding algorithm
//
//    Copyright 2004,2005 Setasign - Jan Slabon
//
//  Licensed under the Apache License, Version 2.0 (the "License");
//  you may not use this file except in compliance with the License.
//  You may obtain a copy of the License at
//
//      http://www.apache.org/licenses/LICENSE-2.0
//
//  Unless required by applicable law or agreed to in writing, software
//  distributed under the License is distributed on an "AS IS" BASIS,
//  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  See the License for the specific language governing permissions and
//  limitations under the License.
/*/
      function decode($data) {

        if(ord($data{0}) == 0x00 && ord($data{1}) == 0x01) {
            die("LZW flavour not supported.");
        }

        $this->initsTable();

        $this->data =& $data;

        // Initialize pointers
        $this->bytePointer = 0;
        $this->bitPointer = 0;

        $this->nextData = 0;
        $this->nextBits = 0;

        $oldCode = 0;

        $string = "";
        $uncompData = "";

        while (($code = $this->getNextCode()) != 257) {
			if ($code == 256) {
                $this->initsTable();
                $code = $this->getNextCode();

                if ($code == 257) {
                    break;
                }

                $uncompData .= $this->sTable[$code];
                $oldCode = $code;

            } else {

                if ($code < $this->tIdx) {
                    $string = $this->sTable[$code];
                    $uncompData .= $string;

                    $this->addStringToTable($this->sTable[$oldCode], $string[0]);
                    $oldCode = $code;
                } else {
                    $string = $this->sTable[$oldCode];
                    $string = $string.$string[0];
                    $uncompData .= $string;

                    $this->addStringToTable($string);
                    $oldCode = $code;
                }
            }
        }
        
        return $uncompData;
    }


    /**
     * Initialize the string table. - if PHP5 mark as private or protected
     */
    function initsTable() {
        $this->sTable = array();

        for ($i = 0; $i < 256; $i++){
            $this->sTable[$i] = chr($i);
		}

        $this->tIdx = 258;
        $this->bitsToGet = 9;
    }

    /**
     * Add a new string to the string table. - if PHP5 mark as private or protected
     */
    function addStringToTable ($oldString, $newString="") {
        $string = $oldString.$newString;

        // Add this new String to the table
        $this->sTable[$this->tIdx++] = $string;

        if ($this->tIdx == 511) {
            $this->bitsToGet = 10;
        } else if ($this->tIdx == 1023) {
            $this->bitsToGet = 11;
        } else if ($this->tIdx == 2047) {
            $this->bitsToGet = 12;
        }
    }

    // Returns the next 9, 10, 11 or 12 bits - if PHP5 mark as private or protected
    function getNextCode() {
        if ($this->bytePointer == strlen($this->data)+1)
            return 257;

        $this->nextData = ($this->nextData << 8) | (ord($this->data{$this->bytePointer++}) & 0xff);
        $this->nextBits += 8;

        if ($this->nextBits < $this->bitsToGet) {
            $this->nextData = ($this->nextData << 8) | (ord($this->data{$this->bytePointer++}) & 0xff);
            $this->nextBits += 8;
        }

        $code = ($this->nextData >> ($this->nextBits - $this->bitsToGet)) & $this->andTable[$this->bitsToGet-9];
        $this->nextBits -= $this->bitsToGet;

		return $code;
    }
/**
 * The following methods allow PHP to deal with unsigned longs. 
 * They support the above primary methods. They are not warranted or guaranteed.
*/
/**
 * Method: lshift - if PHP5 mark as private or protected
 *   Used to allow class to deal with unsigned longs, bitwise left shift
 *    Two parameters, number to be shifted, and how much to shift
 * @param binary string $n
 * @param int $b
 * @return binary string
**/
  function lshift($n,$b){ return str_pad($n,($b+strlen($n)),"0");}
/**
 * Method: rshift - if PHP5 mark as private or protected
 *   Used to allow class to deal with unsigned longs, bitwise right shift
 *    Two parameters, number to be shifted, and how much to shift
 * @param binary string $n
 * @param int $b
 * @return int
 */  
  function rshift($n,$b){
   $ret = substr($n,0,(strlen($n) - $b));
   return ((int)bindec($ret));
  }
/**
 * Method: bitOR - if PHP5 mark as private or protected
 *   Used to allow class to deal with unsigned longs, bitwise OR (|)
 *    Bitwise comparison of two parameters, return string representation of not more than 32 bits
 * @param binary string $a
 * @param binary string $b
 * @return binary string
 */ 
  function bitOR($a,$b){
    $long = strlen($a) > strlen($b) ? $a : $b;
	$short = $long == $a ? $b : $a;
	$l = strrev($long);
	$s = strrev($short);
	for($r=0;$r<strlen($l);$r++){
	  $re[$r] = ($s{$r} == "1" || $l{$r} == "1") ? "1" : "0"; 
	}
	$ret = implode("",$re);
	$ret = strrev(substr($ret,0,32));
	return $ret;
  }

}

?>
