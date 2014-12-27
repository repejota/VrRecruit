<?php
use Vreasy\Models\Message;

/**
 * Class TaskMessageTest
 * TaskMessage Unit Tests
 */
class TaskMessageTest extends \Codeception\TestCase\Test
{
    protected $TaskMessage;
    protected $TaskMessages;

    /**
     * Before test prepare environment
     */
    protected function _before()
    {
        $this->TaskMessage = new Message();
    }

    /**
     * After test clean environment
     */
    protected function _after()
    {
    }

    /**
     * By default all TaskMessages id's are null.
     * The unique identifier for each TaskMessage message is generated when
     * it is being saved and assigned to a task.
     */
    public function testTaskMessageIdNullByDefault()
    {
        $this->assertNull($this->TaskMessage->id);
    }

    /**
     * By default a new TaskMessage is not assigned to a task so its value
     * is null.
     */
    public function testTaskMessageTaskIdNullByDefault()
    {
        $this->assertNull($this->TaskMessage->task);
    }

    /**
     * By default a new TaskMessage doesn't have a recipient phone number so
     * its value is null.
     */
    public function testTaskMessageFromNullByDefault()
    {
        $this->assertNull($this->TaskMessage->from);
    }

    /**
     * By default a new TaskMessage doesn't have a message body so its value
     * is null.
     */
    public function testTaskMessageMessageNullByDefault()
    {
        $this->assertNull($this->TaskMessage->message);
    }

}