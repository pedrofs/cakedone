<?php
namespace App\Test\TestCase\Model\Behavior;

use App\Model\Behavior\TrackableBehavior;
use App\Model\Entity\Tracking;
use Cake\TestSuite\TestCase;
use Cake\ORM\TableRegistry;

/**
 * App\Model\Behavior\TrackableBehavior Test Case
 */
class TrackableBehaviorTest extends TestCase
{

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
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $config = TableRegistry::exists('Trackings') ? [] : ['className' => 'App\Model\Table\TrackingsTable'];
        $this->Trackings = TableRegistry::get('Trackings', $config);

        $config = TableRegistry::exists('Todos') ? [] : ['className' => 'App\Model\Table\TodosTable'];
        $this->Todos = TableRegistry::get('Todos', $config);

        $this->Trackable = new TrackableBehavior($this->Todos);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        $this->Trackings->deleteAll([]);
        $this->Todos->deleteAll([]);

        unset($this->Trackable);
        unset($this->Trackings);
        unset($this->Todos);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $trackingsAssociation = $this->Todos->associations()->get('Trackings');
        $this->assertTrue(!!$trackingsAssociation);
        $this->assertEquals(['trackable_type' => 'Todos'], $trackingsAssociation->conditions());
    }

    public function testStartTracking()
    {
        $todo = $this->Todos->find()->first();
        $tracking = $this->Todos->startTracking($todo);
        $this->assertTrue($tracking instanceof Tracking);
        $this->assertNotEmpty($tracking->started_at);
    }

    public function testExpectedExceptionWhenStartTrackingOnNewEntity()
    {
        $this->setExpectedException('InvalidArgumentException');
        $todo = $this->Todos->newEntity();
        $this->Todos->startTracking($todo);
    }

    public function testExpectedExceptionWhenStartTrackingOnStartedEntity()
    {
        $this->setExpectedException('InvalidArgumentException');
        $todo = $this->Todos->find()->first();
        $this->Todos->startTracking($todo);
        $this->Todos->startTracking($todo);
    }

    public function testStopTracking()
    {
        $todo = $this->Todos->find()->first();
        $trackingStart = $this->Todos->startTracking($todo);
        $trackingStop = $this->Todos->stopTracking($todo);

        $this->assertTrue($trackingStart->id == $trackingStop->id);
        $this->assertNotEmpty($trackingStop->stopped_at);
    }

    public function testExpectExceptionWhenStopTrackingForNonStartedEntity()
    {
        $this->setExpectedException('InvalidArgumentException');
        $todo = $this->Todos->get(1);
        $this->Todos->stopTracking($todo);
    }

    public function testTimeSpent()
    {
        $todo = $this->Todos->newEntity();
        $this->assertTrue(0 === $this->Todos->timeSpent($todo));

        $todo = $this->Todos->get(1);
        $this->assertEquals(25200, $this->Todos->timeSpent($todo));

        $todo = $this->Todos->get(2);
        $this->assertEquals(7245, $this->Todos->timeSpent($todo));
    }
}
