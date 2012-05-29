<?php
namespace Mnl;
class ErrorHandler
{
    private static $instance;

    private function __construct()
    {
        $this->registerHandlers();
    }

    public function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function setErrorAction($type="dump", $args = array())
    {
        $this->errorAction = $type;
        $this->errorActionArgs = $args;
    }
    public function errorAction()
    {
        switch ($this->errorAction) {
            case 'redirect':
                $_SESSION['error_message'] = $this->message;
                $_SESSION['error_dump'] = $this->errorDump;
                header('Location: '.$this->errorActionArgs['redirect_uri']);
                die();
                break;
            case 'dump':
                echo '<pre>'.$this->errorMessage.'</pre>';
                break;
            default:
                break;
        }
    }

    public static function registerHandlers()
    {
        set_exception_handler('Mnl\ErrorHandler::handleException');
    }

    public static function handleException($exception)
    {
        $traceline = "#%s %s(%s): %s(%s)";
        $msg = "PHP Fatal error:  Uncaught exception '%s'\n\t\twith message: '%s' \n\t\tin %s:%s\nStack trace:\n%s\n  thrown in %s on line %s";

        $trace = $exception->getTrace();

         $result = array();
        foreach ($trace as $key => $stackPoint) {
            $result[] = sprintf(
                $traceline,
                $key,
                $stackPoint['file'],
                $stackPoint['line'],
                $stackPoint['function'],
                isset($stackPoint['args'])?implode(', ', $stackPoint['args']):''
            );
        }
        $result[] = '#' . ++$key . ' {main}';

        $msg = sprintf(
            $msg,
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            implode("\n", $result),
            $exception->getFile(),
            $exception->getLine()
        );
        error_log($msg);
        self::getInstance()->message = $exception->getMessage();
        self::getInstance()->errorDump = $msg;
        self::getInstance()->errorAction();
    }
}
