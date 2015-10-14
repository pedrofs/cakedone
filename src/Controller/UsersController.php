<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Utility\Security;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    /**
     * beforeFilter hook method
     *
     * This before filter is used to enable users the add and authenticate
     *
     * @param Event $event The beforeFilter event
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', 'auth']);
    }

    /**
     * Add method
     *
     * @return void Render user and its token if success otherwise render errors
     */
    public function add()
    {
        $this->request->allowMethod(['post']);

        $user = $this->Users->newEntity();
        $user = $this->Users->patchEntity($user, $this->request->data);

        if ($this->Users->save($user)) {
            $this->response->statusCode(201);

            $token = $this->createToken($user);

            $this->set('user', $user);
            $this->set('token', $token);
            $this->set('_serialize', ['user', 'token']);
        } else {
            $this->response->statusCode(400);
            $this->set('errors', $user->errors());
            $this->set('_serialize', ['errors']);
        }
    }

    private function createToken($user)
    {
        return \JWT::encode(
            [
                'id' => $user->get('id')
            ],
            Security::salt()
        );
    }
}
