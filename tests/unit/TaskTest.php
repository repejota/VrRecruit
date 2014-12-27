<?php
use Vreasy\Models\Task;

/**
 * Class TaskTest
 * Task Unit Tests
 */
class TaskTest extends \Codeception\TestCase\Test
{

    protected $task;
    protected $phone;

    /**
     * Before test prepare environment
     */
    protected function _before()
    {
        $this->task = new Task();
        $this->phone = '+34666123456';
    }

    /**
     * After test clean environment
     */
    protected function _after()
    {
    }

    /**
     * By default all task id's are null.
     * The unique identifier for each task is generated when
     * it is being saved.
     */
    public function testTaskIdNullByDefault()
    {
        $this->assertNull($this->task->id);
    }

    /**
     * By default all tasks starts as pending
     */
    public function testTaskStatusPendingByDefault()
    {
        $this->assertEquals($this->task->status, Task::STATUS_PENDING);
    }

    /**
     * By default created_at and updated_at are set to current date & time
     */
    public function testTaskSameUpdatedAtAndCreatedAtByDefault()
    {
        $this->assertNotNull($this->task->created_at);
        $this->assertNotNull($this->task->updated_at);
        $this->assertEquals($this->task->created_at, $this->task->updated_at);
    }

    /**
     * By default task deadline is null
     */
    public function testTaskDeadlineNullByDefault()
    {
        $this->assertNull($this->task->deadline);
    }

    /**
     * By default tasks are unassigned
     */
    public function testTaskUnassignedPhoneAndNameByDefault()
    {
        $this->assertNull($this->task->assigned_name);
        $this->assertNull($this->task->assigned_phone);
    }

    /**
     * If we accept the task:
     * - status is updated to accepted
     * - assigned_phone is updated to worker's phone number
     */
    public function testAcceptPendingTask()
    {
        $task = new Task();
        $this->assertEquals($task->status, Task::STATUS_PENDING);
        $task->accept($this->phone);
        $this->assertEquals($task->status, Task::STATUS_ACCEPTED);
        $this->assertEquals($task->assigned_phone, $this->phone);
    }

    /**
     * If we complete the task:
     * - status is updated to completed
     * - assigned_phone is updated to worker's phone number
     */
    public function testAcceptAndCompletePendingTask()
    {
        $task = new Task();
        $this->assertEquals($task->status, Task::STATUS_PENDING);
        $task->accept($this->phone);
        $this->assertEquals($task->status, Task::STATUS_ACCEPTED);
        $this->assertEquals($task->assigned_phone, $this->phone);
        $task->complete($this->phone);
        $this->assertEquals($task->status, Task::STATUS_COMPLETED);
        $this->assertEquals($task->assigned_phone, $this->phone);
    }

    /**
     * If we refuse the task:
     * - status is updated to refused
     * - assigned_phone is updated to worker's phone number
     */
    public function testRefusePendingTask()
    {
        $task = new Task();
        $this->assertEquals($task->status, Task::STATUS_PENDING);
        $task->refuse($this->phone);
        $this->assertEquals($task->status, Task::STATUS_REFUSED);
        $this->assertEquals($task->assigned_phone, $this->phone);
    }
}