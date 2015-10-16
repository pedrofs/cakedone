<?php
namespace App\Test\TestCase\Controller\Component;

use Cake\TestSuite\TestCase;
use Cake\Controller\ComponentRegistry;
use Cake\Controller\Controller;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\Event\Event;
use App\Controller\Component\FlashApiComponent;
use App\TestApp\Controller\ArticlesController;

class FlashApiComponentTest extends TestCase
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        $this->request = new Request('/articles');
        $this->response = $this->getMock('Cake\Network\Response');
        $this->controller = new ArticlesController($this->request, $this->response);
        parent::setUp();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Test no flash message
     *
     * @return void
     */
    public function testNoFlashMessage()
    {
        $flashApiComponent = new FlashApiComponent($this->controller->components());
        $event = new Event('Controller.beforeRender', $this->controller);
        $flashApiComponent->beforeRender($event);
        $this->assertTrue(!isset($flashApiComponent->_registry->getController()->viewVars['flash']));

        $flashApiComponent->set('success', 'Testing flash message');
        $flashApiComponent->beforeRender($event);
        $this->assertTrue(!isset($flashApiComponent->_registry->getController()->viewVars['flash']));
    }

    /**
     * Test set flash message
     *
     * @return void
     */
    public function testSetFlashMessage()
    {
        $this->request->env('HTTP_ACCEPT', 'application/json');
        $flashApiComponent = new FlashApiComponent($this->controller->components());
        $event = new Event('Controller.beforeRender', $this->controller);
        $flashApiComponent->set('success', 'Testing flash message');
        $flashApiComponent->beforeRender($event);
        $result = $flashApiComponent->_registry->getController()->viewVars['flash'];
        $this->assertSame(['flash' => ['success' => 'Testing flash message']], $result);
    }

    /**
     * Test set flash message with custom config key
     *
     * @return void
     */
    public function testFlashMessageWithCustomConfigKey()
    {
        $this->request->env('HTTP_ACCEPT', 'application/json');
        $flashApiComponent = new FlashApiComponent($this->controller->components(), [
            'key' => 'feedback'
        ]);
        $event = new Event('Controller.beforeRender', $this->controller);
        $flashApiComponent->set('success', 'Testing flash message');
        $flashApiComponent->beforeRender($event);
        $this->assertTrue(isset($flashApiComponent->_registry->getController()->viewVars['feedback']));
        $result = $flashApiComponent->_registry->getController()->viewVars['feedback'];
        $this->assertSame(['feedback' => ['success' => 'Testing flash message']], $result);
    }
}
