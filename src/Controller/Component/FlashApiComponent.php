<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\Controller;
use Cake\Event\Event;

class FlashApiComponent extends Component
{
    /**
     * Default config.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'key' => 'flash'
    ];

    /**
     * Array that holds flash message
     */
    private $messages = [];

    /**
     * beforeRender will look for any flash message and add it to the response
     *
     * @param Event $event
     * @return void
     */
    public function beforeRender(Event $event)
    {
        $controller = $event->subject();

        if (empty($this->messages) || !$this->isApiRequest($controller)) {
            return;
        }

        $flash = [$this->config('key') => $this->messages];
        $controller->set($this->config('key'), $flash);
        $controller->viewVars['_serialize'][] = $this->config('key');
    }

    /**
     * Sets a $message for the given $key
     *
     * @param string $key The key to be used
     * @param string $message The flash message
     * @return void
     */
    public function set($key, $message)
    {
        $this->messages[$key] = $message;
    }

    /**
     * Checks if the request is XML or JSON
     *
     * @param Controller $controller
     * @return bool True if the request is XML or JSON otherwise false
     */
    private function isApiRequest($controller)
    {
        return $controller->request->is('json') ||
            $controller->request->is('xml');
    }
}
