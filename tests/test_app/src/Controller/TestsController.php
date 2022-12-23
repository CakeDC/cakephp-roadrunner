<?php
declare(strict_types=1);

namespace App\Controller;


use Cake\Http\Exception\RedirectException;
use Cake\Routing\Router;

class TestsController extends AppController
{
    public function initialize() : void
    {
        parent::initialize();
    }

    public function index()
    {
        $this->request->allowMethod('GET');
        $data = ['hello' => 'world'];
        $this->set(compact('data'));
        $this->viewBuilder()->setOption('serialize', 'data');
    }

    public function write()
    {
        $this->request->allowMethod(['POST', 'PUT', 'PATCH']);
        $data = $this->request->getData();
        $this->set(compact('data'));
        $this->viewBuilder()->setOption('serialize', 'data');
    }

    public function delete()
    {
        $this->request->allowMethod('DELETE');
        return $this->response->withStatus(204);
    }

    public function testRedirectException()
    {
        throw new RedirectException(Router::url('/redirected_location.json', full: true));
    }
}
