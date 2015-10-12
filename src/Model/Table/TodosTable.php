<?php
namespace App\Model\Table;

use App\Model\Entity\Todo;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Todos Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 */
class TodosTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('todos');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Trackable');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('content', 'create')
            ->notEmpty('content');

        $validator
            ->add('is_done', 'valid', ['rule' => 'boolean'])
            ->requirePresence('is_done', 'create')
            ->notEmpty('is_done');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        return $rules;
    }

    /**
     * forUser finder method
     *
     * It will find all todos for a given user id
     *
     * @param Query $q The Query builder
     * @param $options The options should have an index `id`
     * @return Query
     */
    public function findForUser(Query $query, $options)
    {
        if (!isset($options['id'])) {
            throw new \InvalidArgumentException("You should provide an user id through \$options\['id'\]");
        }

        return $query->where(['user_id' => $options['id']]);
    }
}
