<?php
namespace App\Test\TestCase\Model\Table;

trait HelperTrait
{
    public function assertPresenceOf($table, $field, $existingError = [])
    {
        $attributes = $this->validAttributes();
        $attributes[$field] = '';
        $entity = $table->newEntity($attributes);
        $expected = array_merge($this->emptyError($field), $existingError);
        $this->assertEquals($expected, $entity->errors());
    }

    public function assertEmailFormat($table, $field = 'email')
    {
        $attributes = $this->validAttributes();
        $attributes['email'] = 'testtest.com';
        $user = $this->Users->newEntity($attributes);
        $this->assertEquals($this->emailError($field), $user->errors());
    }

    private function emptyError($field)
    {
        return [$field => ['_empty' => 'This field cannot be left empty']];
    }

    private function emailError($field)
    {
        return [$field => ['valid' => 'The provided value is invalid']];
    }
}