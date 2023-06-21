<?php
// listener class for event handling
namespace MyApp\Handlers;

use Phalcon\Di\Injectable;
use Phalcon\Mvc\Application;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;


class Listener extends Injectable
{

    public function beforeHandleRequest(Event $event, Application $application, Dispatcher $dis)
    {
        if (empty($dis->getControllerName())) {
            $controller = 'index';
        } else {
            $controller = $dis->getControllerName();
        }
        if ($controller == 'index' || $controller == 'login' || $controller == 'signup') {
            //accessible
        } else {
            if ($this->session->has('token')) {
                $tokenReceived = $this->session->get('token');
                $now           = new \DateTimeImmutable();
                $issued        = $now->getTimestamp();
                $notBefore     = $now->modify('-1 minute')->getTimestamp();
                $expires       = $now->getTimestamp();
                $parser      = new Parser();

                // Phalcon\Security\JWT\Token\Token object
                $tokenObject = $parser->parse($tokenReceived);

                // Phalcon\Security\JWT\Validator object
                $validator = new Validator($tokenObject, 100);
                $validator->validateExpiration($expires);
                $token = $tokenObject->getClaims()->getPayload();
                $role = $token['sub'];
                if (!$role) {
                    echo "You are not authorized ! Please login first <br> <a href='login'>Login</a>";
                    die;
                }
            } else {
                echo "You are not authorized ! Please login first <br> <a href='login'>Login</a>";
                die;
            }
        }
    }
}
