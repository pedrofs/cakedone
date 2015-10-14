<?php
namespace App\Test\TestCase\Controller;

use App\Controller\UsersController;
use Cake\TestSuite\IntegrationTestCase;
use Cake\ORM\TableRegistry;

/**
 * App\Controller\UsersController Test Case
 */
class UsersControllerTest extends IntegrationTestCase
{
    use HelperTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users'
    ];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Users') ? [] : ['className' => 'App\Model\Table\UsersTable'];
        $this->Users = TableRegistry::get('Users', $config);
    }

    public function testAdd()
    {
        $this->post("/users/add.json", $this->getValidUserAttributes());
        $response = $this->decodedResponse();
        $this->assertResponseCode(201);
        $this->assertEquals(2, count($this->Users->find()->all()));

        $this->assertTrue(isset($response['token']));
        $this->assertTrue(!isset($response['user']['password']));
        $this->assertTrue(!isset($response['user']['password_confirmation']));

        $attributes = $this->getValidUserAttributes();
        $attributes['password_confirmation'] = '321';
        $this->post("/users/add.json", $attributes);
        $this->assertResponseCode(400);
        $this->assertResponseEquals($this->getExpectedResponse([
            'errors' => [
                'password_confirmation' => [
                    'custom' => 'The password does not match'
                ]
            ]
        ]));
        $this->assertEquals(2, count($this->Users->find()->all()));
    }

    private function getValidUserAttributes()
    {
        return [
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => '123',
            'password_confirmation' => '123'
        ];
    }
}
