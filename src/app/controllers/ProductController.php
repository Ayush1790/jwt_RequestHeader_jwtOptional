<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;
use MyApp\Models\Products;
use MyApp\Models\Orders;

class ProductController extends Controller
{
    public function indexAction()
    {
        $products = $this->db->fetchAll(
            "SELECT * FROM products",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        $this->view->data = $products;
    }

    public function addAction()
    {
        $id = $this->request->getPost('id');
        $products = $this->db->fetchAll(
            "SELECT * FROM products where `product_id`='$id' ",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        $this->view->data = $products;
    }
    public function orderAction()
    {
        $id = $this->request->getPost('id');
        $qty = $this->request->getPost('qty');
        $data = $this->db->fetchAll(
            "SELECT * FROM products where `product_id`='$id' ",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        if ($data[0]['qty'] >= $qty && $qty > 0) {
            $product = Products::findFirst($id);
            $product->qty = $product->qty - $qty;
            $product->save();
            $order = new Orders();
            $data = array(
                'product_id' => $id,
                'user_id' => $this->session->get('id'),
                'qty' => $qty,
                'amount' => $qty * $product->price
            );
            $order->assign(
                $data,
                [
                    'product_id',
                    'user_id',
                    'qty',
                    'amount'
                ]
            );
            $success = $order->save();
            if ($success) {
                $this->response->redirect('product');
            } else {
                $this->logger->error("order faild - reasons <br>" . implode('<br>', $order->getMessages()));
                $this->response->redirect('order');
            }
        } else {
            echo "failed";
        }
    }
    public function getorderAction()
    {
        $id = $this->session->get('id');
        $data = $this->db->fetchAll(
            "SELECT * FROM orders where `user_id`='$id' ",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        $this->view->data = $data;
    }
    public function addproductAction()
    {
        //redirect to view
    }

    public function productaddAction()
    {
        $product = new Products();
        $data = array(
            'product_name' => $this->escaper->escapeHtml($this->request->getPost('name')),
            'img' => $this->request->getPost('file'),
            'qty' => $this->escaper->escapeHtml($this->request->getPost('qty')),
            'price' => $this->escaper->escapeHtml($this->request->getPost('price')),
            'desc' => $this->escaper->escapeHtml($this->request->getPost('desc')),

        );
        $product->assign(
            $data,
            [
                'product_name',
                'img',
                'qty',
                'price',
                'desc'
            ]
        );
        $success = $product->save();
        if (!$success) {
            $this->logger->error("product addition faild - reasons <br>" . implode('<br>', $product->getMessages()));
        } else {
            $this->response->redirect('product');
        }
    }
}
