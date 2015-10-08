<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Todos'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="todos form large-9 medium-8 columns content">
    <?= $this->Form->create($todo) ?>
    <fieldset>
        <legend><?= __('Add Todo') ?></legend>
        <?php
            echo $this->Form->input('content');
            echo $this->Form->input('user_id', ['options' => $users]);
            echo $this->Form->input('is_done');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
