<?php

class AddMessagesTable extends Ruckusing_Migration_Base
{
    public function up()
    {
        $logs = $this->create_table('messages', ['id' => false, 'options' => 'Engine=InnoDB']);
        $logs->column(
            'id',
            'integer',
            [
            'null' => false,
            'primary_key' => true,
            'auto_increment' => true
            ]
            );
        $logs->column(
            'task',
            'integer',
            [
            'null' => false
            ]
            );
        $logs->column(
            "status",
            "enum",
            [
            'null'    => false,
            'values' => ['pending', 'accepted', 'refused', 'completed'],
            'default' => 'pending'
            ]
            );
        $logs->column('created_at', 'datetime');
        $logs->column('from', 'text');
        $logs->column('message', 'text');
        $logs->finish();
    }

    public function down()
    {
        $this->drop_table("messages");
    }
}
