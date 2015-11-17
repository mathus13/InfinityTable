<?php

namespace Infinity\Games\Controllers;

use \Infinity\Games\Games as GamesTable;
use \Infinity\Controller\Api as ApiController;

class Games extends ApiController
{
    protected $table;

    protected function beforeAction()
    {
        parent::beforeAction();
        $this->table = new GamesTable($this->db);
    }

    public function listClients()
    {
        if (count($this->args) > 0) {
            $items = $this->table->search($this->args);
        } else {
            $items = $this->table->getAllActive();
        }
        $items = $this->buildItems($items);
        $this->return['args'] = $this->args;
        $this->return['data'] = $items;
    }

    private function buildItems(array $rows)
    {
        $items = array();
        foreach ($rows as $client) {
            $item = array(
                'id' => $client->id,
                'type' => 'game',
                'attributes' => $client->toArray()
            );
            $items[] = $item;
        }
        return $items;
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
                'href' => '/games',
                'meta' => array(
                    'method' => 'GET'
                )
            )
        );
    }

    public function getById()
    {
        $id = $this->client_id;
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
        $id = $this->client_id;
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
        $id = $this->client_id;
        if (!$id) {
            $this->throwError(103, "URL segment 3 (id) missing");
            return;
        }
        $client = $this->table->find($id);
        foreach ($this->request->getParsedBody() as $k => $v) {
            $client->{$k} = $v;
        }
        $client->save();
        $this->return['data'] = $this->buildItems(array($client));
    }

    public function getItemOptions()
    {
        $id = $this->required('client_id');
        $this->return['links'] = array(
            'Get Game' => array(
                'href' => "/games/{$id}",
                'meta' => array(
                    'method' => 'GET'
                )
            ),
            'Update Game' => array(
                'href' => "/games/{$id}",
                'meta' => array(
                    'method' => 'PUT'
                )
            ),
            'Delete Game' => array(
                'href' => "/games/{$id}",
                'meta' => array(
                    'method' => 'DELETE'
                )
            ),
        );
    }
}
