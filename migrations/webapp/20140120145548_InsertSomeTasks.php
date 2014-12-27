<?php

$cliIndex = implode(DIRECTORY_SEPARATOR, ['vreasy', 'application', 'cli', 'cliindex.php']);
require_once($cliIndex);

use Vreasy\Models\Task;

class InsertSomeTasks extends Ruckusing_Migration_Base
{
    public function up()
    {
        foreach ([1,2,3] as $i) {
            $r = rand($i,1500)*10000;
            $t = Task::instanceWith([
                'deadline' => (new \DateTime("+$r seconds"))->format(DATE_FORMAT),
                'assigned_name' => 'John Doe',
                'assigned_phone' => '+55 555-555-555',
                'status' => Task::STATUS_PENDING
                ]);
            $t->save();
        }

        $r = rand(4,1500)*10000;
        $t = Task::instanceWith([
            'deadline' => (new \DateTime("+$r seconds"))->format(DATE_FORMAT),
            'assigned_name' => 'John Doe',
            'assigned_phone' => '+55 555-555-555',
            'status' => Task::STATUS_ACCEPTED
            ]);
        $t->save();

        $r = rand(5,1500)*10000;
        $t = Task::instanceWith([
            'deadline' => (new \DateTime("+$r seconds"))->format(DATE_FORMAT),
            'assigned_name' => 'John Doe',
            'assigned_phone' => '+55 555-555-555',
            'status' => Task::STATUS_REFUSED
            ]);
        $t->save();

        $r = rand(6,1500)*10000;
        $t = Task::instanceWith([
            'deadline' => (new \DateTime("+$r seconds"))->format(DATE_FORMAT),
            'assigned_name' => 'John Doe',
            'assigned_phone' => '+55 555-555-555',
            'status' => Task::STATUS_COMPLETED
            ]);
        $t->save();

    }

    public function down()
    {
    }
}
