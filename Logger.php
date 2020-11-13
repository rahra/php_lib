<?php
/*! \file Logger.php
 * This file provides the class Logger which is a simple logging utility.
 *
 * \author Bernhard R. Fischer aka Rahra, <bernhard.fischer@netlogix.at>
 * \date 2020/07/02
 */


namespace php_lib;


class Logger
{
   const LOG_EMERG = 0;
   const LOG_ALERT = 1;
   const LOG_CRIT = 2;
   const LOG_ERR = 3;
   const LOG_WARNING = 4;
   const LOG_NOTICE = 5;
   const LOG_INFO = 6;
   const LOG_DEBUG = 7;

   const prioritynames = array(
      LOG_EMERG => "emerg",
      LOG_ALERT => "alert",
      LOG_CRIT => "crit",
      LOG_ERR => "err",
      LOG_WARNING => "warning",
      LOG_NOTICE => "notice",
      LOG_INFO => "info",
      LOG_DEBUG => "debug"
   );

   //! define minium logging priority
   public $log_level = Logger::LOG_INFO;
   //! default output resource
   protected $f = NULL;


   /*! Constructor method for the Logger object.
    * @param $logname The optional parameter $logname allows to specify a
    * logfile where the log output is written to. The special name "stderr"
    * logs output to the stderr and the special name "error_log" logs output to
    * the webserver's error log. The latter is default if either STDERR is not
    * defined (i.e. the program was not called on the command line) or if an
    * error occured when opening the file with the filename $logname. Please
    * note that the user must have write permissions at the intended file
    * location.
    */
   function __construct($logname = "stderr")
   {
      switch ($logname)
      {
         case "stderr":
            $this->f = defined("STDERR") ? STDERR : NULL;
            break;

         case "error_log":
            $this->f = NULL;
            break;

         default:
            if (($this->f = fopen($logname, "w")) === FALSE)
            {
               $this->f = defined("STDERR") ? STDERR : NULL;
               $this->log_msg(Logger::LOG_ERR, "could not open logfile \"$logname\"");
            }
            else
               $this->log_msg(Logger::LOG_INFO, "logging to \"$logname\"");
      } // switch ($logname)
   }


   function __destruct()
   {
      // properly close file on object destruction
      if ($this->f != NULL)
         fclose($this->f);
   }


   /*! Log message $s with log level $l.
    * @param $l Log level which should be one of Logger::LOG_*.
    * @param $s Message to log.
    * @return The method returns the number of bytes written to the log. In
    * case of error, FALSE is returned.
    */
   function log_msg($l, $s)
   {
      // ignore messages of lower priority
      if ($l > $this->log_level)
         return 0;

      $lstr = " [" . Logger::prioritynames[$l] . "] " . $s;
      if ($this->f != NULL)
         return fwrite($this->f, "%" . date("H:i:s") . $lstr . PHP_EOL);
      else
      {
         if (!error_log($lstr))
            return FALSE;
         return strlen($lstr);
      }
   }


   /*! This is a convenience method. It is the same as calling log_msg() with
      * log level Logger::LOG_DEBUG.
      * @param $s Message to log.
      * @return \see method log_msg().
    */
   function log_debug($s)
   {
      return $this->log_msg(Logger::LOG_DEBUG, $s);
   }
}


?>
