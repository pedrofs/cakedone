<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Tracking Entity.
 *
 * @property int $id
 * @property \Cake\I18n\Time $started_at
 * @property \Cake\I18n\Time $stopped_at
 * @property int $trackable_id
 * @property \App\Model\Entity\Trackable $trackable
 * @property string $trackable_type
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class Tracking extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
