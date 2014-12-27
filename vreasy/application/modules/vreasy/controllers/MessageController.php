<?php
use Vreasy\Models\Message;

class Vreasy_MessageController extends Vreasy_Rest_Controller
{
    protected $messages;
    protected $message;

    public function preDispatch()
    {
        parent::preDispatch();

        $request = $this->getRequest();
        $action = $request->getActionName();
        $contentType = $request->getHeader('Content-Type');

        $rawBody = $request->getRawBody();

        if ($rawBody) {
            if (stristr($contentType, 'application/json')) {
                $request->setParams(['message' => Zend_Json::decode($rawBody)]);
            }
        }

        if ($request->getParam('format') == 'json') {
            switch ($action) {
                case "show":
                    $this->messages = Message::where(
                        ["task" => $request->getParam("id")],
                        ["orderBy" => "id", "orderDirection" => "asc"]);
                    break;
            }
        }

        if (!in_array($action, ['show']) && !$this->messages && !$this->message->id) {
            throw new Zend_Controller_Action_Exception('Resource not found', 404);
        }
    }

    public function showAction()
    {
        $this->view->messages = $this->messages;
        $this->_helper->conditionalGet()->sendFreshWhen(['etag' => $this->messages]);
    }
}