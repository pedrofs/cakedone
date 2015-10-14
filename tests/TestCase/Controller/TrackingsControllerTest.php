<?php
namespace App\Test\TestCase\Controller;

use App\Controller\TrackingsController;
use Cake\TestSuite\IntegrationTestCase;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

/**
 * App\Controller\TrackingsController Test Case
 */
class TrackingsControllerTest extends IntegrationTestCase
{
    use HelperTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users',
        'app.trackings',
        'app.todos'
    ];

    /**
     * setUp method
     *
     * It will set the needed Tables.
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Trackings') ? [] : ['className' => 'App\Model\Table\TrackingsTable'];
        $this->Trackings = TableRegistry::get('Trackings', $config);
        $config = TableRegistry::exists('Users') ? [] : ['className' => 'App\Model\Table\UsersTable'];
        $this->Users = TableRegistry::get('Users', $config);
    }

    public function testAdd()
    {
        $this->post("/todos/1/trackings/add.json", []);
        $this->assertResponseCode(401);

        $token = $this->getToken();

        $this->post("/todos/1/trackings/add.json?_token=$token", [
            'started_at' => '2015-10-14T16:50:21-0300',
            'stopped_at' => '2015-10-14T18:50:21-0300'
        ]);
        $this->assertResponseCode(201);
        $this->assertEquals(6, count($this->Trackings->find()->all()));
        $this->assertResponseEquals($this->getExpectedResponseAdd());

        $this->post("/todos/1/trackings/add.json?_token=$token", []);
        $this->assertResponseCode(400);
        $this->assertEquals(6, count($this->Trackings->find()->all()));

        $this->get("/todos/1/trackings/add.json?_token=$token");
        $this->assertResponseCode(405);
    }

    public function testEdit()
    {
        $this->put("/todos/1/trackings/edit/1.json", []);
        $this->assertResponseCode(401);

        $token = $this->getToken();

        $this->put("/invalid_trackable/1/trackings/edit/1.json?_token=$token", []);
        $this->assertResponseCode(404);

        $this->put("/todos/1/trackings/edit/4.json?_token=$token", ['stopped_at' => '2015-10-14T19:50:21-0300']);
        $response = $this->decodedResponse();
        $this->assertResponseCode(200);
        $this->assertEquals('2015-10-14T19:50:21-0300', $response['tracking']['stopped_at']);

        $this->put("/todos/1/trackings/edit/4.json?_token=$token", ['started_at' => '']);
        $this->assertResponseCode(400);

        $this->put("/todos/1/trackings/edit/5.json?_token=$token", ['started_at' => '2015-10-14T11:50:21-0300']);
        $this->assertResponseCode(401);

        $this->put("/todos/1/trackings/edit/23.json?_token=$token", ['started_at' => '2015-10-14T11:50:21-0300']);
        $this->assertResponseCode(404);

        $this->get("/todos/1/trackings/edit/4.json?_token=$token", ['started_at' => '2015-10-14T11:50:21-0300']);
        $this->assertResponseCode(405);
    }

    public function testDelete()
    {
        $this->get("/todos/1/trackings/delete/1.json");
        $this->assertResponseCode(401);

        $token = $this->getToken();

        $this->delete("/todos/1/trackings/delete/1.json?_token=$token");
        $this->assertResponseCode(200);
        $this->assertEquals(4, count($this->Trackings->find()->all()));

        $this->delete("/todos/1/trackings/delete/3.json?_token=$token");
        $this->assertResponseCode(401);

        $this->delete("/todos/1/trackings/delete/25.json?_token=$token");
        $this->assertResponseCode(404);
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
        $trackings = $this->Trackings->find()->last();
        $created = $trackings->created;
        $modified = $trackings->modified;

        return $this->getExpectedResponse(["tracking" => [
            'started_at' => '2015-10-14T16:50:21-0300',
            'stopped_at' => '2015-10-14T18:50:21-0300',
            'trackable_id' => 1,
            'trackable_type' => 'Todos',
            'created' => $created,
            'modified' => $modified,
            'id' => 6,
        ]]);
    }
}
