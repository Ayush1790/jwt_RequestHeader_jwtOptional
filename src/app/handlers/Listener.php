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

    public function beforeHandleRequest(Event $event, Application $application, Dispatcher $dispatcher)
    {
        $aclFile = APP_PATH . '/storage/security/acl.cache';
        // Check whether ACL data already exist
        if (true !== is_file($aclFile)) {

            // The ACL does not exist - build it
            $acl = new Memory();

            // ... Define roles, components, access, etc

            $acl->addComponent(
                'admin',
                [
                    'index',
                    'order',
                    'product',
                    'user'
                ]
            );
            $acl->addComponent(
                'index',
                [
                    'index',
                ]
            );
            $acl->addComponent(
                'login',
                [
                    'index',
                    'login'
                ]
            );
            $acl->addComponent(
                'product',
                [
                    'index',
                    'add',
                    'order',
                    'getorder'
                ]
            );
            $acl->addComponent(
                'signup',
                [
                    'index',
                    'register'
                ]
            );
            $acl->addComponent(
                'logout',
                [
                    'index',
                ]
            );
            $acl->addComponent(
                'setting',
                [
                    'index',
                ]
            );

            $acl->addRole('user');
            $acl->addRole('guest');
            $acl->addRole('admin');
            $acl->addRole('accountant');
            $acl->addRole('manager');

            $acl->allow('admin', '*', '*');
            $acl->allow('guest', 'index', '*');
            $acl->allow('guest', 'product', 'index');
            $acl->allow('guest', 'product', 'index');
            $acl->allow('guest', 'login', '*');
            $acl->allow('guest', 'signup', '*');
            $acl->allow('user', 'signup', '*');
            $acl->allow('user', 'login', '*');
            $acl->allow('user', 'index', '*');
            $acl->allow('user', 'product', '*');
            $acl->allow('user', 'logout', '*');
            $acl->allow('accountant', 'product', 'order');
            $acl->allow('manager', 'product', '*');

            // Store serialized list into plain file
            file_put_contents(
                $aclFile,
                serialize($acl)
            );
        } else {
            // Restore ACL object from serialized file
            $acl = unserialize(
                file_get_contents($aclFile)
            );
        }

        $controller = "index";
        $action = "index";
        if (!empty($dispatcher->getControllerName())) {
            $controller = $dispatcher->getControllerName();
        }
        if (!empty($dispatcher->getActionName())) {
            $action = $dispatcher->getActionName();
        }
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
        } else {
            $role = 'guest';
        }
        if ($acl->isAllowed($role, $controller, $action)) {
            //redirect to view
        } else {
            echo "Permission denied";
            die;
        }
    }
}
