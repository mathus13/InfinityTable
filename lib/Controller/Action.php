<?php

namespace Infinity\Controller;

class Action
{
    protected $request;
    protected $response;
    protected $args;
    protected $container;

    public function __construct(
        \Psr\Http\Message\ServerRequestInterface $request,
        \Psr\Http\Message\ResponseInterface $response,
        \Pimple\Container $container,
        array $args
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->container = $container;
        $this->args = $args;
    }

    public function fire($action)
    {
        if (!method_exists($this, $action)) {
            return $this->response->withStatus(404, 'Action not found');
        }
        $this->beforeAction();
        $this->{$action}();
        $this->afterAction();
        $this->dispatch();
        return $this->response;
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }

        if (isset($this->args[$property])) {
            return $this->args[$property];
        }

        try {
            if ($service = $this->container[$property]) {
                return $service;
            }
        } catch(Exception $e) {
            //property wasnt in di container
        }
        return false;
    }

    public function getUri()
    {
        $uri = $this->request->getUri();
        return "{$uri->getScheme()}://{$uri->getHost()}{$uri->getBasePath()}/{$uri->getPath()}";
    }

    protected function required($param)
    {
        $found = $this->{$param};
        if ($found) {
            $this->response = $this->response->withStatus(400, "Required element {$param} not found");
        }
        return $found;
    }

    /**
     * prepare the response for return
     * @return \Psr\Http\Message\ResponseInterface PSR7 Response Object
     */
    protected function dispatch()
    {
        
    }

    protected function beforeAction()
    {

    }

    protected function afterAction()
    {

    }

    /**
     * Gets the value of request.
     *
     * @return Slim\Http\Request
     */
    protected function getRequest()
    {
        return $this->request;
    }

    /**
     * Gets the value of responce.
     *
     * @return Slim\Http\Response
     */
    protected function getResponse()
    {
        return $this->responce;
    }

    /**
     * Gets the value of args.
     *
     * @return mixed
     */
    protected function getArgs()
    {
        return $this->args;
    }
}
