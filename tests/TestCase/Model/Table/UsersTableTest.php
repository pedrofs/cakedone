<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersTable Test Case
 */
class UsersTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users',
        'app.todos'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Users') ? [] : ['className' => 'App\Model\Table\UsersTable'];
        $this->Users = TableRegistry::get('Users', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Users);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $user = $this->Users->newEntity($this->validAttributes());
        $this->assertEquals(count($user->errors()), 0);

        $attributes = $this->validAttributes();
        $attributes['email'] = '';
        $user = $this->Users->newEntity($attributes);
        $this->assertEquals(['email' => ['_empty' => 'This field cannot be left empty']], $user->errors());

        $attributes = $this->validAttributes();
        $attributes['name'] = '';
        $user = $this->Users->newEntity($attributes);
        $this->assertEquals(['name' => ['_empty' => 'This field cannot be left empty']], $user->errors());

        $attributes = $this->validAttributes();
        $attributes['password'] = '';
        $user = $this->Users->newEntity($attributes);
        $this->assertEquals(
            [
                'password' => ['_empty' => 'This field cannot be left empty'],
                'password_confirmation' => ['custom' => 'The password does not match']
            ],
            $user->errors()
        );

        $attributes = $this->validAttributes();
        $attributes['email'] = 'testtest.com';
        $user = $this->Users->newEntity($attributes);
        $this->assertEquals(['email' => ['valid' => 'The provided value is invalid']], $user->errors());

        $attributes = $this->validAttributes();
        $attributes['password_confirmation'] = '321';
        $user = $this->Users->newEntity($attributes);
        $this->assertEquals(['password_confirmation' => ['custom' => 'The password does not match']], $user->errors());
    }

    private function validAttributes()
    {
        return [
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => '123',
            'password_confirmation' => '123'
        ];
    }
}
