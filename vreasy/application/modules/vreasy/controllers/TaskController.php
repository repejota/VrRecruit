<?php

use Vreasy\Models\Task;

class Vreasy_TaskController extends Vreasy_Rest_Controller
{
    protected $task;
    protected $tasks;

    public function preDispatch()
    {
        parent::preDispatch();

        $request = $this->getRequest();
        $action = $request->getActionName();
        $contentType = $request->getHeader('Content-Type');

        $rawBody = $request->getRawBody();

        if ($rawBody) {
            if (stristr($contentType, 'application/json')) {
                $request->setParams(['task' => Zend_Json::decode($rawBody)]);
            }
        }
        if ($request->getParam('format') == 'json') {
            switch ($action) {
                case 'index':
                    $this->tasks = Task::where([]);
                    break;
                case 'new':
                    $this->task = new Task();
                    break;
                case 'create':
                    $this->task = Task::instanceWith($request->getParam('task'));
                    break;
                case 'show':
                case 'update':
                case 'destroy':
                    $this->task = Task::findOrInit($request->getParam('id'));
                    break;
            }
        }

        if (!in_array($action, [
                'index',
                'new',
                'create',
                'update',
                'destroy'
            ]) && !$this->tasks && !$this->task->id
        ) {
            throw new Zend_Controller_Action_Exception('Resource not found', 404);
        }
    }

    public function indexAction()
    {
        $this->view->tasks = $this->tasks;
        $this->_helper->conditionalGet()->sendFreshWhen(['etag' => $this->tasks]);
    }

    public function newAction()
    {
        $this->view->task = $this->task;
        $this->_helper->conditionalGet()->sendFreshWhen(['etag' => $this->task]);
    }

    public function createAction()
    {
        if ($this->task->isValid() && $this->task->save()) {
            $this->view->task = $this->task;
        } else {
            $this->view->errors = $this->task->errors();
            $this->getResponse()->setHttpResponseCode(422);
        }
    }

    public function showAction()
    {
        $this->view->task = $this->task;
        $this->_helper->conditionalGet()->sendFreshWhen(['etag' => [$this->task]]);
    }

    public function updateAction()
    {
        Task::hydrate($this->task, $this->_getParam('task'));
        if ($this->task->isValid() && $this->task->save()) {
            $this->view->task = $this->task;
        } else {
            $this->view->errors = $this->task->errors();
            $this->getResponse()->setHttpResponseCode(422);
        }
    }

    public function destroyAction()
    {
        if ($this->task->destroy()) {
            $this->view->task = $this->task;
        } else {
            $this->view->errors = ['delete' => 'Unable to delete resource'];
        }
    }
}
