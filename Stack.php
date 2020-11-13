<?php
/*! \file Stack.php
 * This file contains an implementation of a mapped stack. It allows to
 * push/pop elements under certain keys. The keys don't have to be unique, i.e.
 * the same key can be pushed repeatetly onto the stack.
 *
 * \author Bernhard R. Fischer aka Rahra, <bernhard.fischer@netlogix.at>
 * \date 2020/07/03
 */


namespace php_lib;


/*! This class implements a stack.
 */
class Stack
{
   //! internal array to keep the elements
   protected $stack = array();


   /*! Convenienve method for simple text output of the elements on the stack.
    */
   function __toString()
   {
      $s = "";
      foreach ($this->stack as $vp)
         $s .= "([{$vp['key']}] => {$vp['val']})";
      return $s;
   }


   /*! Return number of elements on the stack.
    * @return Returns the number of elements.
    */
   function count()
   {
      return count($this->stack);
   }


   /*! This function returns the last element on the stack as a key value pair.
    * The element is not removed from the stack.
    * @return Returns the last element as an array in the form ["key" =>
    * <key>, "val" => <val>]. If no element is currently on the stack, NULL
    * is returned.
    */
   function end()
   {
      if (!$this->count())
         return NULL;

      return end($this->stack);
   }


   /*! Check if the last element has the key $k.
    * @param $k Key to check.
    * @return Returns TRUE or FALSE.
    */
   function is_last($k)
   {
      if (!$this->count())
         return FALSE;

      return $this->end()["key"] == $k;
   }


   /*! Push element $k onto the stack. Optionally a value $v may be associated
    * (mapped) with the element.
    * @param $k Element to push on the stack.
    * @param $v Optional value to associate with the element $k.
    * @return Returns the number of elements on the stack, after the new
    * element is pushed on top.
    */
   function push($k, $v = NULL)
   {
      $this->stack[] = array("key" => $k, "val" => $v);
      return $this->count();
   }


   /*! Pop (remove) last element from the stack and return its value, i.e. the
    * value it was associate with it at the call to push(). If the parameter $k
    * is given, the last element is only removed if its key matches $k.
    * @param $k Optional parameter to match with last element.
    * @return The method returns the key/value pair as an array in the form
    * ["key" => <key>, "val" => <val>]. If there are no more elements on the
    * stack or if $k is given but does not match the last element, NULL is
    * returned.
    */
   function pop($k = NULL)
   {
      if (!$this->count())
         return NULL;

      if ($k != NULL && !$this->is_last($k))
         return NULL;

      return array_pop($this->stack);
   }


   /*! This method clears the stack completely.
    */
   function clear()
   {
      $this->stack = array();
   }
}


?>
