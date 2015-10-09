<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TrackingsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TrackingsTable Test Case
 */
class TrackingsTableTest extends TestCase
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
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Trackings);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $tracking = $this->Trackings->newEntity($this->validAttributes());
        $this->assertEquals(0, count($tracking->errors()));

        $this->assertPresenceOf($this->Trackings, 'started_at');
        $this->assertPresenceOf($this->Trackings, 'trackable_id');
        $this->assertPresenceOf($this->Trackings, 'trackable_type');
    }

    private function validAttributes()
    {
        return ['started_at' => new \DateTime(), 'trackable_id' => 1, 'trackable_type' => 'Trackable'];
    }
}
