<?php
/**
 * Mnl_Log
 *
 * @category Mnl
 * @package  Mnl
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
class Mnl_Log
{
    /**
     * @var bool Do you want to log the date
     */
    protected $_logDate;
    /**
     * @var string Format of date to log
     */
    protected $_logDateFormat;
    /**
     * @var string Path to the logfile
     */
    protected $_logFile;

    /**
     * Constructor
     * @param array $configuration Configuration
     */
    public function __construct(array $configuration = array())
    {
        $this->_logFile = "logs/systemlog";
        $this->_logDate = true;
        $this->_logDateFormat = "Y-m-d H:i:s";

        if (isset($configuration['logFile'])) {
            $this->_logFile = $configuration['logFile'];
        }

        if (isset($configuration['logDate'])) {
            $this->_logDate = $configuration['logDate'];
        }

        if (isset($configuration['logDateFormat'])) {
            $this->_logDateFormat = $configuration['logDateFormat'];
        }


    }

    /**
     * Writes a string to logfile
     * @param string $string String to write to log
     * @return TRUE on success FALSE on error
     */
    public function write($string)
    {
        $fh = fopen($this->_logFile, "a+");

        if ($fh !== false) {
            if ($this->_logDate) {
                $string = date($this->_logDateFormat, time()).' '.$string;
            }
            fwrite($fh, $string);
            fclose($fh);
            return true;
        } else {
            return false;
        }
    }
}

