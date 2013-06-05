<?php
namespace Mnl;

class ErrorHandler
{
    private static $instance;
    private $handlers;

    private function __construct()
    {
        $this->handlers = array();
        $this->registerHandler();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function addExceptionHandler($exceptionName = "Exception", $handler = "Mnl\ErrorHandler\DefaultHandler")
    {
        $this->handlers[$exceptionName] = $handler;
    }

    public static function registerHandler()
    {
        set_exception_handler('Mnl\ErrorHandler::handleException');
    }


    public static function handleException($exception)
    {
        $instance = self::getInstance();
        foreach ($instance->handlers as $exceptionName => $handler) {
            if (get_class($exception) == $exceptionName) {
                $handler = new $handler;
                return $handler->handle($exception);
            }
        }

        $defaultHandler = new ErrorHandler\DefaultHandler();
        return $defaultHandler->handle($exception);

    }
}
