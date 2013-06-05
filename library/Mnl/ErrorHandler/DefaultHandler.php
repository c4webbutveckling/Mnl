<?php
namespace Mnl\ErrorHandler;

class DefaultHandler implements HandlerInterface
{
    public function handle($exception)
    {
        throw $exception;
    }
}
