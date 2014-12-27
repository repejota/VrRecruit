<?php

class AddTasksTable extends Ruckusing_Migration_Base
{
    public function up()
    {
        $tasks = $this->create_table('tasks', ['id' => false, 'options' => 'Engine=InnoDB']);
        $tasks->column(
            'id',
            'integer',
            [
            'primary_key' => true,
            'auto_increment' => true,
            'null' => false
            ]
            );
        $tasks->column(
            "status",
            "enum",
            [
            'null'    => false,
            'values' => ['pending', 'accepted', 'refused', 'completed'],
            'default' => 'pending'
            ]
            );
        $tasks->column('deadline', 'datetime');
        $tasks->column('created_at', 'datetime');
        $tasks->column('updated_at', 'datetime');
        $tasks->column('assigned_name', 'text');
        $tasks->column('assigned_phone', 'text');
        $tasks->finish();
    }

    public function down()
    {
        $this->drop_table("tasks");
    }
}
