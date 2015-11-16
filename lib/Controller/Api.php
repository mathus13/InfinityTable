<?php

namespace Infinity\Controller;

class Api extends Action
{
    protected $return;
    protected $errorCodes = array(
        100 => array(
            'status' => 404,
            'message' => 'Invalid ID',
            'title' => 'Invalid ID',
            'details' => 'The item your requested seems to have never existed'
        ),
        101 => array(
            'status' => 404,
            'message' => 'Deleted',
            'title' => 'Item has been deleted',
            'details' => 'The item you are trying to view has been deleted'
        ),
        102 => array(
            'status' => 500,
            'message' => 'Error Deleting',
            'title' => 'Error deleting item',
            'details' => 'There was an error deleting the item.'
        ),
        103 => array(
            'status' => 400,
            'message' => 'Missing Param',
            'title' => 'Request missing parameter',
            'details' => 'There is a required parameter missing from this request'
        ),
        999 => array(
            'status' => 500,
            'message' => 'Unknown Error',
            'title' => 'An Unknown errorhas occured',
            'details' => 'There was an error, but we dont kow what it was'
        ),
    );

    protected function beforeAction()
    {
        parent::beforeAction();
        $return = array();
    }

    protected function dispatch()
    {
        $response = $this->response->withHeader('Content-Type', 'application/vnd.api+json');
        if (!empty($this->return)) {
            $response->getBody()->write(
                json_encode($this->return)
            );
        }
        $this->response = $response;
    }

    protected function throwError($code, $detail = null)
    {
        if (isset($this->errorCodes[$code])) {
            $error = $this->errorCodes[$code];
        } else {
            $this->errorCodes[999];
        }
        if (!array_key_exists('errors', $this->return)) {
            $this->return['errors'] = array();
        }
        $this->return['errors'][] = array(
            'title' => "Error {$code}: {$error['title']}",
            'code' => $code,
            'detail' => $error['details'],
            'links' => array(
                'self' => $this->getUri()
            )
        );
        $this->response = $this->response->withStatus($error['status'], $error['message']);
    }
}
