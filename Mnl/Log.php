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
    protected $logDate;
    /**
     * @var string Format of date to log
     */
    protected $logDateFormat;
    /**
     * @var string Path to the logfile
     */
    protected $logFile;

    /**
     * Constructor
     * @param array $configuration Configuration
     */
    public function __construct(array $configuration = array())
    {
        $this->logFile = "logs/systemlog";
        $this->logDate = true;
        $this->logDateFormat = "Y-m-d H:i:s";

        if(isset($configuration['logFile'])) {
            $this->logFile = $configuration['logFile'];
        }

        if(isset($configuration['logDate'])) {
            $this->logDate = $configuration['logDate'];
        }

        if(isset($configuration['logDateFormat'])) {
            $this->logDateFormat = $configuration['logDateFormat'];
        }


    }

    /**
     * Writes a string to logfile
     * @param string $string String to write to log
     * @return TRUE on success FALSE on error
     */
    public function write($string)
	{
		$fh = fopen($this->logFile, "a+");

		if ($fh !== false) {
            if ($this->logDate) {
                $string = date($this->logDateFormat, time()).' '.$string;
            }
			fwrite($fh, $string);
			fclose($fh);
			return true;
		} else {
			return false;
		}
	}
}

