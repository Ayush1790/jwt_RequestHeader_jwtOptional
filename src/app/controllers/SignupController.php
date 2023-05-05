<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;
use MyApp\Models\Users;

class SignupController extends Controller
{
    public function indexAction()
    {
        //redirect to view
    }

    public function registerAction()
    {
        $user = new Users();
        $data = array(
            'name' => $this->escaper->escapeHtml($this->request->getPost('name')),
            'email' => $this->escaper->escapeHtml($this->request->getPost('email')),
            'pswd' => $this->escaper->escapeHtml($this->request->getPost('pswd')),
            'pincode' => $this->escaper->escapeHtml($this->request->getPost('pincode')),
            'gender' => $this->request->getPost('gender'),

        );
        $user->assign(
            $data,
            [
                'name',
                'email',
                'pswd',
                'pincode',
                'gender'
            ]
        );
        $success = $user->save();
        if ($success) {
            $this->session->set('email', $data['email']);
            $this->session->set('pswd', $data['pswd']);
            $this->response->redirect('login');
        } else {
            $this->logger->error("signup faild due to following reasons <br>" . implode('<br>', $user->getMessages()));
            $this->response->redirect('signup');
        }
    }
}
