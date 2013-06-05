<?php
namespace Mnl\ErrorHandler;

class DefaultNoRouteFoundHandler implements HandlerInterface
{
    public function handle($exception)
    {
        $response = new \Mnl\Response("", "404");
        $response->setContent($response->getStatusString());
        $response->send();
    }
}
