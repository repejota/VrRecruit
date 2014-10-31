<?php

class ErrorController extends Zend_Controller_Action
{

    public function init() {
        $errors = $this->_getParam('error_handler');
        $this->_helper->layout()->setLayout('vreasy');
        $contextSwitch = $this->_helper->getHelper('contextSwitchExt');
        // request
        $contextSwitch->addActionContext($this->getRequest()->getActionName(), 'xml');
        if ($errors->request->getParam('setVreasyJson')) {
            $contextSwitch->addActionContext($this->getRequest()->getActionName(), 'json');
        }
        $contextSwitch->initContext();
    }

    public function errorAction() {
        $errors = $this->_getParam('error_handler');
        if( APPLICATION_ENV!='development' && APPLICATION_ENV!='testing'  && APPLICATION_ENV!='test'){
            $this->view->debug = false;
        }
        else {
            $this->view->debug = true;
        }

        $availableErrors = [404, 401, 412];
        if(in_array($errors->exception->getCode(), $availableErrors)) {
            $code = $errors->exception->getCode();
        } elseif($errors->exception instanceof \Vreasy\Exceptions\AuthException) {
            $code = 401;
        } else {
            $code = 500;
        }
        $this->view->code = $code;

        // Render error view
        if ( !$errors || !$errors instanceof ArrayObject ) {
            $this->view->message = 'You have reached the error page';
            return;
        }

        switch ( $errors->type ) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode($code);
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Page not found';
                break;
            default:
                $this->getResponse()->setHttpResponseCode($code);
                $priority = Zend_Log::CRIT;
                $this->view->message = 'Application error';
                break;
        }

        // Log exception, if logger available
        if ( $log = $this->getLog() ) {
            $params = print_r(($errors->request->getParams()), true);
            $headers = print_r(apache_request_headers(), true);
            $log->err("{$this->view->message} {$params} {$headers} {$errors->exception}");
        }

        $this->view->exception = $errors->exception;
        $this->view->requestParams = $errors->request->getParams();
    }

    public function getLog() {
        return Zend_Registry::get('Zend_Log');
    }


}

