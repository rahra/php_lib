<?php
/*! Module containing a universial key/value pair parser. It parses strings of
 * the form <key1> <valsep> <value> [ <parsep> <key2> <valsep> <value> [ ... ]]
 * into an associative array of the form array(<key1> => <value>, <key2> =>
 * <value>, ...). The value separator <valsep> and the pair separator <parsep>
 * are configurable by setting the public object members $parsep and $valsep.
 *
 * Examples for key/value pair strings are 'foo="bar" bar="baz"' or
 * 'foo:bar;bar:baz;'.
 *
 * @author Bernhard R. Fischer aka Rahra, <bernhard.fischer@netlogix.at>
 * @version 2020/06/25
 */


/*! OptionList is a universal key/value pair parser.
 * The default value separator is '=' and the default pair separator is ' '.
 */
class OptionList
{
   //! array containing the final elements
   public $elem = array();
   //! separator element of the key/value pairs
   public $parsep = " ";
   //! separator element which separates the key from the value
   public $valsep = "=";
   //! original string
   public $str;


   /*! If the constructor is called with an argument it is directly parsed.
    * @param $s String to parse.
    */
   function __construct($s = "")
   {
      $this->parse($s);
   }


   /*! Convenience method for text output. It returns the original string which
    * was either passed to the constructor or the latest call to parser().
    */
   function __toString()
   {
      return $this->str;
   }


   /*! This method parses the option list string $s and splits it into an
      * associative array.
      * @param $s String to parse.
      * @return The method returns an associative array.
    */
   function parse($s)
   {
      $this->str = $s;
      $len = strlen($s);
      for ($i = 0; $i < $len; $i++)
      {
         // skip blanks
         for (; $i < $len && ($s[$i] == " " || $s[$i] == "\n"); $i++);

         // find next occurance of separator
         if (($j = strpos($s, $this->valsep, $i)) === FALSE)
            $j = $len;

         // extract string which is the key
         $key = substr($s, $i, $j - $i);
         $i = $j;

         $val = "";
         // check of end of string
         if ($i < $len)
         {
            $i++;
            $sep = $s[$i];
            if ($sep == '"' || $sep == "'")
               $i++;
            else
               $sep = $this->parsep;

            if (($j = strpos($s, $sep, $i)) === FALSE)
               $j = $len;

            $val = substr($s, $i, $j - $i);
            $i = $j + 1;
         }

         // add element to array
         $this->elem[$key] = $val;
      }
   }
}


/*! This class is an OptionList with the default value separator ':' and the
   * pair separator ';' as used in CSS.
 */
class CSSList extends OptionList
{
   function __construct($s = "")
   {
      $this->parsep = ";";
      $this->valsep = ":";
      parent::__construct($s);
   }
}


?>
