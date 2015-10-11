<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Todo Entity.
 *
 * @property int $id
 * @property string $content
 * @property int $user_id
 * @property \App\Model\Entity\User $user
 * @property bool $is_done
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class Todo extends Entity
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
        'content' => true
    ];
}
