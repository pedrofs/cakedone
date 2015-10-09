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
     * Trait for helping the validations
     */
    use HelperTrait;

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

        $this->assertPresenceOf($this->Users, 'name');
        $this->assertPresenceOf($this->Users, 'email');
        $this->assertPresenceOf($this->Users, 'password', ['password_confirmation' => ['custom' => 'The password does not match']]);
        $this->assertEmailFormat($this->Users);

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
