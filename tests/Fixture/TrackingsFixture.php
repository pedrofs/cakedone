<?php
namespace App\Test\Fixture;

use Cake\I18n\Time;
use Cake\TestSuite\Fixture\TestFixture;

/**
 * TrackingsFixture
 *
 */
class TrackingsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'started_at' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        'stopped_at' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => '0000-00-00 00:00:00', 'comment' => '', 'precision' => null],
        'trackable_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'trackable_type' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public function init()
    {
        $stoppedAt = new Time();
        $startedAt = clone($stoppedAt);
        $startedAt->modify('-2 hours');

        $stoppedAt2 = new Time();
        $startedAt2 = clone($stoppedAt2);
        $startedAt2->modify('-45 seconds');

        $this->records = [
            [
                'id' => 1,
                'started_at' => $startedAt,
                'stopped_at' => $stoppedAt,
                'trackable_id' => 1,
                'trackable_type' => 'Todos',
                'created' => '2015-10-09 13:19:39',
                'modified' => '2015-10-09 13:19:39'
            ],
            [
                'id' => 2,
                'started_at' => $startedAt,
                'stopped_at' => $stoppedAt,
                'trackable_id' => 2,
                'trackable_type' => 'Todos',
                'created' => '2015-10-09 13:19:39',
                'modified' => '2015-10-09 13:19:39'
            ],
            [
                'id' => 3,
                'started_at' => $startedAt2,
                'stopped_at' => $stoppedAt2,
                'trackable_id' => 2,
                'trackable_type' => 'Todos',
                'created' => '2015-10-09 13:19:39',
                'modified' => '2015-10-09 13:19:39'
            ],
        ];

        parent::init();
    }
}
