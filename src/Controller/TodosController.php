<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\UnauthorizedException;

/**
 * Todos Controller
 *
 * @property \App\Model\Table\TodosTable $Todos
 */
class TodosController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $user = $this->Auth->user();
        $this->set('todos', $this->paginate($this->Todos->find('forUser', ['id' => $user['id']])));
        $this->set('_serialize', ['todos']);
    }

    /**
     * View method
     *
     * @param string|null $id Todo id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $todo = $this->Todos->get($id, [
            'contain' => ['Trackings']
        ]);
        $this->set('todo', $todo);
        $this->set('_serialize', ['todo']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['post']);

        $user = $this->Auth->user();
        $todo = $this->Todos->newEntity();
        $todo = $this->Todos->patchEntity($todo, $this->request->data);
        $todo->set('user_id', $user['id']);

        if ($this->Todos->save($todo)) {
            $this->response->statusCode(201);
            $this->set(compact('todo'));
            $this->set('_serialize', ['todo']);
        } else {
            $this->response->statusCode(400);
            $this->set('errors', $todo->errors());
            $this->set('_serialize', ['errors']);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Todo id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->request->allowMethod(['post', 'put', 'patch']);
        $user = $this->Auth->user();

        $todo = $this->Todos->get($id);

        if ($todo->user_id !== $user['id']) {
            throw new UnauthorizedException();
        }

        $todo = $this->Todos->patchEntity($todo, $this->request->data);

        if ($this->Todos->save($todo)) {
            $this->set(compact('todo'));
            $this->set('_serialize', ['todo']);
        } else {
            $this->response->statusCode(400);
            $this->set('errors', $todo->errors());
            $this->set('_serialize', ['errors']);
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Todo id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Auth->user();

        $todo = $this->Todos->get($id);

        if ($todo->user_id !== $user['id']) {
            throw new UnauthorizedException();
        }

        if (!$this->Todos->delete($todo)) {
            $this->response->statusCode(400);
        }
    }
}
