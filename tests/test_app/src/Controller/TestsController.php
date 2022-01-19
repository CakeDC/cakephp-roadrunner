<?php
declare(strict_types=1);

namespace App\Controller;


class TestsController extends AppController
{
    public function initialize() : void
    {
        parent::initialize();
    }

    public function index()
    {
        $data = ['hello' => 'world'];
        $this->set(compact('data'));
        $this->viewBuilder()->setOption('serialize', 'data');
    }
}
