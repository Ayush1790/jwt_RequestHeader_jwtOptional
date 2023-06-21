<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;


class LoginController extends Controller
{
    public function indexAction()
    {
        //redirect to view
    }
    public function loginAction()
    {
        $user = $this->db->fetchAll(
            "SELECT * FROM users",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        $email = $this->request->getPost('email');
        $pswd = $this->request->getPost('pswd');
        foreach ($user as $key => $value) {
            if ($value['email'] == $email && $value['pswd'] == $pswd) {

                $signer  = new Hmac();

                // Builder object
                $builder = new Builder($signer);

                $now        = new \DateTimeImmutable();
                $issued     = $now->getTimestamp();
                $notBefore  = $now->modify('-1 minute')->getTimestamp();
                $expires    = $now->modify('+1 day')->getTimestamp();
                $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';
                // Setup
                $builder
                    ->setExpirationTime($expires)               // exp
                    ->setId('abcd123456789')                    // JTI id
                    ->setIssuedAt($issued)                      // iat
                    ->setIssuer('https://phalcon.io')           // iss
                    ->setNotBefore($notBefore)                  // nbf
                    ->setSubject($value['role'])                // sub
                    ->setPassphrase($passphrase)                // password
                ;
                // Phalcon\Security\JWT\Token\Token object
                $tokenObject = $builder->getToken();
                // The token
                $token = $tokenObject->getToken();
                $this->session->set('token', $token);
                $this->cookies->set(
                    'token',
                    $token,
                    time() + 15 * 86400
                );
                $this->cookies->send();
                $this->session->set('role', $value['role']);
                $this->session->set('id', $value['id']);
                $this->response->redirect('product');
            }
        }
    }
}
