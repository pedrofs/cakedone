<?php
namespace App\Test\TestCase\Controller;

use App\Controller\TodosController;
use Cake\TestSuite\IntegrationTestCase;
use Cake\ORM\TableRegistry;

/**
 * App\Controller\TodosController Test Case
 */
class TodosControllerTest extends IntegrationTestCase
{
    use HelperTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.todos',
        'app.users',
        'app.trackings'
    ];

    /**
     * setUp method
     *
     * It will set the needed Tables.
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Todos') ? [] : ['className' => 'App\Model\Table\TodosTable'];
        $this->Todos = TableRegistry::get('Todos', $config);
        $config = TableRegistry::exists('Users') ? [] : ['className' => 'App\Model\Table\UsersTable'];
        $this->Users = TableRegistry::get('Users', $config);
    }

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->get('/todos.json?page=1');
        $this->assertResponseCode(401);

        $token = $this->getToken();
        $this->get("/todos.json?_token=$token&page=1");
        $this->assertResponseOk();
        $this->assertResponseEquals($this->getExpectedResponseIndex());
    }

    public function testAdd()
    {
        $this->post("/todos/add.json", ['content' => 'test', 'is_done' => false]);
        $this->assertResponseCode(401);

        $token = $this->getToken();

        $this->post("/todos/add.json?_token=$token", ['content' => 'Testing todo', 'is_done' => false]);
        $this->assertResponseCode(201);
        $this->assertEquals(5, count($this->Todos->find()->all()));
        $this->assertResponseEquals($this->getExpectedResponseAdd());

        $this->post("/todos/add.json?_token=$token", ['content' => '', 'is_done' => false]);
        $this->assertResponseCode(400);
        $this->assertEquals(5, count($this->Todos->find()->all()));

        $this->get("/todos/add.json?_token=$token");
        $this->assertResponseCode(405);
    }

    public function testEdit()
    {
        $this->post("/todos/edit/1.json", ['content' => 'test', 'is_done' => false]);
        $this->assertResponseCode(401);

        $token = $this->getToken();

        $this->post("/todos/edit/1.json?_token=$token", ['content' => 'Edited todo']);
        $response = $this->decodedResponse();
        $this->assertResponseCode(200);
        $this->assertEquals('Edited todo', $response['todo']['content']);

        $this->post("/todos/edit/1.json?_token=$token", ['content' => '']);
        $this->assertResponseCode(400);

        $this->post("/todos/edit/3.json?_token=$token", ['content' => 'Edited todo']);
        $this->assertResponseCode(401);

        $this->post("/todos/edit/25.json?_token=$token", ['content' => "Edited todo"]);
        $this->assertResponseCode(404);

        $this->get("/todos/edit/1.json?_token=$token", ['content' => 'Edited todo']);
        $this->assertResponseCode(405);
    }

    public function testView()
    {
        $this->get("/todos/view/1.json");
        $this->assertResponseCode(401);

        $token = $this->getToken();

        $this->get("/todos/view/1.json?_token=$token");
        $response = $this->decodedResponse();
        $this->assertResponseCode(200);
        $this->assertTrue(isset($response['todo']['trackings']));
        $this->assertEquals(1, count($response['todo']['trackings']));

        $this->get("/todos/view/25.json?_token=$token");
        $this->assertResponseCode(404);
    }

    public function testDelete()
    {
        $this->get("/todos/delete/1.json");
        $this->assertResponseCode(401);

        $token = $this->getToken();

        $this->post("/todos/delete/1.json?_token=$token");
        $this->assertResponseCode(200);
        $this->assertEquals(3, count($this->Todos->find()->all()));
        $this->assertEquals(2, count($this->Todos->Trackings->find()->all()));

        $this->post("/todos/delete/3.json?_token=$token");
        $this->assertResponseCode(401);

        $this->post("/todos/delete/25.json?_token=$token");
        $this->assertResponseCode(404);
    }

    public function testStart()
    {
        $token = $this->getToken();
        $this->post("/todos/start/1.json?_token=$token");
        $response = $this->decodedResponse();
        $this->assertResponseCode(200);
        $this->assertTrue(isset($response['tracking']));
        $this->assertEquals(4, count($this->Todos->Trackings->find()->all()));

        $this->post("/todos/start/1.json?_token=$token");
        $response = $this->decodedResponse();
        $this->assertResponseCode(400);
        $this->assertTrue(!isset($response['tracking']));
        $this->assertEquals('Entity is started. Stop it before starting again.', $response['error']);
        $this->assertEquals(4, count($this->Todos->Trackings->find()->all()));

        $this->post("/todos/start/25.json?_token=$token");
        $this->assertResponseCode(404);

        $this->post("/todos/start/3.json?_token=$token");
        $this->assertResponseCode(401);
    }

    public function testStop()
    {
        $token = $this->getToken();

        $this->post("/todos/stop/1.json?_token=$token");
        $response = $this->decodedResponse();
        $this->assertResponseCode(400);
        $this->assertTrue(!isset($response['tracking']));
        $this->assertEquals('Entity tracking not started. Start it before trying to stop.', $response['error']);
        $this->assertEquals(3, count($this->Todos->Trackings->find()->all()));

        $this->post("/todos/start/1.json?_token=$token");
        $this->post("/todos/stop/1.json?_token=$token");
        $response = $this->decodedResponse();
        $this->assertResponseCode(200);
        $this->assertTrue(isset($response['tracking']));
        $this->assertEquals(4, count($this->Todos->Trackings->find()->all()));

        $this->post("/todos/stop/25.json?_token=$token");
        $this->assertResponseCode(404);

        $this->post("/todos/stop/3.json?_token=$token");
        $this->assertResponseCode(401);
    }

    private function getExpectedResponseIndex()
    {
        return $this->getExpectedResponse([
            "todos" => [
                [
                    'id' => 1,
                    'content' => 'This is a sample todo!',
                    'user_id' => 1,
                    "is_done" => true,
                    "created" => "2015-10-08T19:56:36-0300",
                    "modified" => "2015-10-08T19:56:36-0300"

                ],
                [
                    'id' => 2,
                    'content' => 'This another simple todo!',
                    'user_id' => 1,
                    "is_done" => true,
                    "created" => "2015-10-08T19:56:36-0300",
                    "modified" => "2015-10-08T19:56:36-0300"
                ],
            ],
            "paging" => [
                "page" => 1,
                "current" => 2,
                "count" => 2,
                "prevPage" => false,
                "nextPage" => false
            ]
        ]);
    }

    private function getExpectedResponseAdd()
    {
        $todo = $this->Todos->find()->last();
        $created = $todo->created;
        $modified = $todo->modified;

        return $this->getExpectedResponse(["todo" => [
            'content' => 'Testing todo',
            'user_id' => 1,
            'created' => $created,
            'modified' => $modified,
            'id' => 5,
        ]]);
    }
}
