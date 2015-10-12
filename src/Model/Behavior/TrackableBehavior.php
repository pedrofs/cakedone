<?php
namespace App\Model\Behavior;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\I18n\Time;

/**
 * Trackable behavior
 */
class TrackableBehavior extends Behavior
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * Initialize hook
     *
     * It adds the hasMany association to the target table
     *
     * @param array $config
     * @return void
     */
    public function initialize(array $config)
    {
        $this->_table->hasMany('Trackings', [
            'foreignKey' => 'trackable_id',
            'dependent' => true,
            'conditions' => [
                'trackable_type' => $this->_table->alias()
            ]
        ]);
    }

    /**
     * startTracking will create a Tracking record for the $entity
     *
     * @param EntityInterface $entity
     * @return mixed the Tracking entity when it was right started otherwise false
     * @throws InvalidArgumentException if the $entity is new
     */
    public function startTracking(EntityInterface $entity)
    {
        $tracking = $this->_table->Trackings->newEntity($this->getAttributesForEntity($entity));

        if ($entity->isNew()) {
            throw new \InvalidArgumentException("Entity not persisted yet. Persist it before tracking.");
        }

        if ($this->findStartedTrackingForEntity($entity)) {
            throw new \InvalidArgumentException("Entity is started. Stop it before starting again.");
        }

        if ($this->_table->Trackings->save($tracking)) {
            return $tracking;
        }

        return false;
    }

    /**
     * stopTracking will look for a tracking record and stop it
     *
     * @param EntityInterface $entity
     * @return mixed the Tracking entity when it was right stopped otherwise false
     * @throws InvalidArgumentException if the $entity has not a started Tracking
     */
    public function stopTracking(EntityInterface $entity)
    {
        $tracking = $this->findStartedTrackingForEntity($entity);

        if (!$tracking) {
            throw new \InvalidArgumentException("Entity tracking not started. Start it before trying to stop.");
        }

        $tracking = $this->_table->Trackings->patchEntity($tracking, ['stopped_at' => new Time()]);

        if ($this->_table->Trackings->save($tracking)) {
            return $tracking;
        }

        return false;
    }

    /**
     * Calculates the time spent for the given $entity
     *
     * @param EntityInterface $entity
     * @return integer The time spent for the $entity in seconds
     */
    public function timeSpent(EntityInterface $entity)
    {
        return $this->_table->Trackings
            ->find('all')
            ->where(['trackable_id' => $entity->id])
            ->reduce(function ($total, $tracking) {
                if (!$tracking->stopped_at) {
                    return Time::now()->diffInSeconds($tracking->started_at) + $total;
                }

                return $tracking->stopped_at->diffInSeconds($tracking->started_at) + $total;
            }, 0);
    }

    private function findStartedTrackingForEntity(EntityInterface $entity)
    {
        $query = $this->_table->Trackings->find('started', ['id' => $entity->id]);

        return $query->first();
    }

    private function getAttributesForEntity($entity)
    {
        return [
            'started_at' => new Time(),
            'trackable_id' => $entity->id,
            'trackable_type' => $this->_table->alias()
        ];
    }
}
