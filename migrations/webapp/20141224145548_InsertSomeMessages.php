<?php

$cliIndex = implode(DIRECTORY_SEPARATOR, ['vreasy', 'application', 'cli', 'cliindex.php']);
require_once($cliIndex);

use Vreasy\Models\Task;
use Vreasy\Models\Message;

class InsertSomeMessages extends Ruckusing_Migration_Base
{
    public function up()
    {
        foreach ([1,2,3,4,5,6] as $i) {
            $r = rand($i,1500)*10000;
            $m = Message::instanceWith([
                'task' => $i,
                'status' => Task::STATUS_PENDING,
                'created_at' => (new \DateTime("+$r seconds"))->format(DATE_FORMAT),
                'from' => null,
                'message' => null
                ]);
            $m->save();
        }

        $r = rand(4,1500)*10000;
        $m = Message::instanceWith([
            'task' => 4,
            'status' => Task::STATUS_ACCEPTED,
            'created_at' => (new \DateTime("+$r seconds"))->format(DATE_FORMAT),
            'from' => '+55 555-555-555',
            'message' => 'Yes'
            ]);
        $m->save();

        $r = rand(5,1500)*10000;
        $m = Message::instanceWith([
            'task' => 5,
            'status' => Task::STATUS_REFUSED,
            'created_at' => (new \DateTime("+$r seconds"))->format(DATE_FORMAT),
            'from' => '+55 555-555-555',
            'message' => 'No'
            ]);
        $m->save();

        $r = rand(6,1500)*10000;
        $m = Message::instanceWith([
            'task' => 6,
            'status' => Task::STATUS_ACCEPTED,
            'created_at' => (new \DateTime("+$r seconds"))->format(DATE_FORMAT),
            'from' => '+55 555-555-555',
            'message' => 'Yes'
            ]);
        $m->save();

        $r = rand(6,1500)*10000;
        $m = Message::instanceWith([
            'task' => 6,
            'status' => Task::STATUS_COMPLETED,
            'created_at' => (new \DateTime("+$r seconds"))->format(DATE_FORMAT),
            'from' => '+55 555-555-555',
            'message' => 'Yes'
            ]);
        $m->save();
    }

    public function down()
    {
    }
}
