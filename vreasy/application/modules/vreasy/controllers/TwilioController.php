<?php

use Vreasy\Models\Task;

/**
 * Class Vreasy_TwilioController
 */
class Vreasy_TwilioController extends Vreasy_Rest_Controller
{
    protected $twilioAccountId;
    protected $twilioAuthToken;

    /**
     * Setup controller
     */
    public function init()
    {
        $config = Zend_Registry::get("config");
        $this->twilioAccountId = $config->twilio->applicationSid;
        $this->twilioAuthToken = $config->twilio->authToken;
    }

    /**
     * Proccess HTTP call determining its controller and action to execute
     * @throws Zend_Controller_Action_Exception
     */
    public function preDispatch()
    {
        $this->request = $this->getRequest();
        $this->action = $this->request->getActionName();

        if (!in_array($this->action, ["update"])) {
            throw new Zend_Controller_Action_Exception("Resource not found", 404);
        }
    }

    /**
     * Parses HTTP Post payload and get its content
     * @return array
     * - It also checks if the payload is encoded in application/json
     */
    private function parsePostPayload()
    {
        $body = null;
        $from = null;
        $rawBody = $this->request->getRawBody();
        if ($rawBody) {
            if (stristr($this->request->getHeader('Content-Type'), 'application/json')) {
                $payload = Zend_Json::decode($rawBody);
                $body = $payload["Body"];
                $from = $payload["From"];
            }
        }
        return array($body, $from);
    }

    /**
     * Parse SMS body and decides if the message is a confirmation or not
     * @param $body
     * @return bool
     */
    private function parseMessageBody($body)
    {
        $body = trim(strtolower($body));
        $confirmation_responses = array("yes", "si", "ok");
        if (in_array($body, $confirmation_responses)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Updates a task
     * @throws Zend_Controller_Action_Exception
     * - Receives a message as a POST payload from Twilio
     * - Finds a task assigned to the phone sender detailed on the payload
     * - Updates the task accordingly depending on its current state
     */
    public function updateAction()
    {
        // Setup application/json response headers
        $this->getHelper("Layout")->disableLayout();
        $this->getHelper("ViewRenderer")->setNoRender();
        $this->getResponse()->setHeader("Content-Type", "application/json");

        // Get Twilio message data
        list($body, $from) = $this->parsePostPayload();

        // Try to get the task
        $tasks = Task::where([], ["orderBy" => "updated_at", "orderDirection" => "desc", "limit" => 1]);

        if (isset($tasks[0])) {
            $task = $tasks[0];

            // If task is accepted
            // We can complete the task
            if ($task->status === Task::STATUS_ACCEPTED) {
                $task->Complete($from);
                $task->save();
            }

            // If task is pending
            // We can accept or refuse the task
            if ($task->status === Task::STATUS_PENDING) {
                if (!$this->parseMessageBody($body)) {
                    $task->Refuse($from);
                } else {
                    $task->Accept($from);
                }
                $task->save();
            }

            // If task is refused
            // Nothing to do ... tasks cannot be reopened

            // If task is completed
            // Nothing to do ... tasks cannot be reopened

        } else {
            throw new Zend_Controller_Action_Exception("No active tasks assigned to " . $from . " has been found", 404);
        }
    }
}