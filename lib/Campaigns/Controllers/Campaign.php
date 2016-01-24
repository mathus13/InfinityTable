<?php

namespace Infinity\Campaigns\Controllers;

use \Infinity\Campaigns\Campaigns as ClientsTable;
use \Infinity\Controller\Api as ApiController;

class Campaign extends ApiController
{
    protected $table;
    protected $type = 'campaign';

    protected function beforeAction()
    {
        parent::beforeAction();
        $this->table = new ClientsTable($this->db);
    }

    public function create()
    {
        $data = $this->request->getParsedBody();
        $client = $this->table->create($data);
        $client->save();
        $this->return['data'] = $client->toArray();
        $this->response = $this->response->withStatus(201, 'Item Created');
    }

    public function getOptions()
    {
        $this->return['links'] = array(
            'List' => array(
                'href' => '/campaigns',
                'meta' => array(
                    'method' => 'GET'
                )
            )
        );
    }

    public function getById()
    {
        $id = $this->id;
        if (!$id) {
            $this->throwError(103, "URL segment 3 (id) missing");
            return;
        }
        $client = $this->table->find($id);
        if (!$client) {
            $this->throwError(100);
            return;
        }
        if (!$client->active) {
            $this->throwError(101);
            return;
        }
        $this->return['data'] = $this->buildItems(array($client));
    }

    public function delete()
    {
        $id = $this->id;
        if (!$id) {
            $this->throwError(103, "URL segment 3 (id) missing");
            return;
        }
        
        $client = $this->table->find($id);
        if (!$client) {
            $this->throwError(100);
            return;
        }
        $client->delete();
        if (!$client->active) {
            $this->throwError(101);
            return;
        }
        $this->throwError(102);
        return;
    }

    public function update()
    {
        $id = $this->id;
        if (!$id) {
            $this->throwError(103, "URL segment 3 (id) missing");
            return;
        }
        $client = $this->table->find($id);
        $data = json_decode($this->request->getBody());
        foreach ($data as $k => $v) {
            $client->{$k} = $v;
        }
        $client->save();
        $this->return['data'] = $this->buildItems(array($client));
    }

    public function getItemOptions()
    {
        $id = $this->required('id');
        $this->return['links'] = array(
            'Get Campaign' => array(
                'href' => "/campaigns/{$id}",
                'meta' => array(
                    'method' => 'GET'
                )
            ),
            'Update Campaign' => array(
                'href' => "/campaigns/{$id}",
                'meta' => array(
                    'method' => 'PUT'
                )
            ),
            'Delete Campaign' => array(
                'href' => "/campaigns/{$id}",
                'meta' => array(
                    'method' => 'DELETE'
                )
            ),
            'Get Players' => array(
                'href' => "/players",
                'meta' => array(
                    'method' => 'get'
                )
            ),
            'Get Characters' => array(
                'href' => "/characters",
                'meta' => array(
                    'method' => 'get'
                )
            )
        );
    }
}
