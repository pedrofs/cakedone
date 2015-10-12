<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TodosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TodosTable Test Case
 */
class TodosTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.todos',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Todos') ? [] : ['className' => 'App\Model\Table\TodosTable'];
        $this->Todos = TableRegistry::get('Todos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Todos);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $todo = $this->Todos->newEntity();
        $todo = $this->Todos->patchEntity($todo, ['content' => '', 'is_done' => false]);
        $this->assertEquals($todo->errors(), ['content' => ['_empty' => 'This field cannot be left empty']]);

        $todo = $this->Todos->newEntity();
        $todo = $this->Todos->patchEntity($todo, ['content' => 'Write tests for app']);
        $this->assertEquals($todo->errors(), ['is_done' => ['_required' => 'This field is required']]);
    }

    public function testForUserFinder()
    {
        $todos = $this->Todos->find('forUser', ['id' => 1])->all();
        $this->assertEquals(count($todos), 2);
    }

    public function testForUserFinderException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $todos = $this->Todos->find('forUser', ['user' => 1]);
    }
}
