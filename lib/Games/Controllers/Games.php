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
