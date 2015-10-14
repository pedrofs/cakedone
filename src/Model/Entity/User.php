<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

/**
 * User Entity.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \App\Model\Entity\Todo[] $todos
 */
class User extends Entity
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
        'name' => true,
        'email' => true,
        'password' => true,
        'password_confirmation' => true
    ];

    /**
     * Fields that should not be exposed
     *
     * @var array
     */
    protected $_hidden = ['password', 'password_confirmation'];

    /**
     * Virtual properties that should be exposed
     *
     * @var array
     */
    protected $_virtual = ['time_ago_from_created'];

    /**
     * Getter for defining time ago virtual property
     *
     * @return string Time ago in words for the `created`
     */
    protected function _getTimeAgoFromCreated()
    {
        return $this->created->timeAgoInWords();
    }

    /**
     * Mutator/setter method to enable password hashing
     *
     * @param string $value The password that will be hashed
     * @return void
     */
    protected function _setPassword($value)
    {
        $hasher = new DefaultPasswordHasher();
        return $hasher->hash($value);
    }
}
