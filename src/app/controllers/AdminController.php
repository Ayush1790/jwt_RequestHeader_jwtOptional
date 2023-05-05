<?php

namespace MyApp\Controllers;

use MyApp\Models\Users;
use MyApp\Models\Products;
use MyApp\Models\Orders;
use Phalcon\Mvc\Controller;

class AdminController extends Controller
{
    public function indexAction()
    {
        $users = $this->db->fetchAll(
            "SELECT * FROM users",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        $products = $this->db->fetchAll(
            "SELECT * FROM products",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        $orders = $this->db->fetchAll(
            "SELECT * FROM orders ",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        $data = array("users" => $users, "products" => $products, "orders" => $orders);
        $this->view->data = $data;
    }

    public function userAction()
    {
        if ($_POST['type'] == 'Delete') {
            $id = $_POST['id'];
            $user = Users::findFirst($id);
            $user->delete();
        } elseif ($_POST['type'] == 'edit') {
            $id = $_POST['id'];
            $user = Users::findFirst($id);
            $user->name = $_POST['name'];
            $user->email = $_POST['email'];
            $user->role = $_POST['role'];
            $user->pincode = $_POST['pin'];
            $user->pswd = $_POST['pswd'];
            $user->save();
        }
    }
    public function productAction()
    {
        if ($_POST['type'] == 'Delete') {
            $id = $_POST['id'];
            $product = Products::findFirst($id);
            $success = $product->delete();
            if (!$success) {
                $this->logger->error("deletion faild due to following reasons <br>/
                " . implode('<br>', $product->getMessages()));
            }
        } elseif ($_POST['type'] == 'edit') {
            $id = $_POST['id'];
            $product = Products::findFirst($id);
            $product->product_name = $_POST['name'];
            $product->price = $_POST['price'];
            $product->qty = $_POST['qty'];
            $product->desc = $_POST['desc'];
            $product->save();
        }
    }
    public function orderAction()
    {
        if ($_POST['type'] == 'Delete') {
            $id = $_POST['id'];
            $orders = Orders::findFirst($id);
            $success = $orders->delete();
            if (!$success) {
                $this->logger->error("orders deletion faild due to following reasons <br>/
                " . implode('<br>', $orders->getMessages()));
            }
        } elseif ($_POST['type'] == 'edit') {
            $id = $_POST['order_id'];
            $order = Orders::findFirst($id);
            $order->product_id = $_POST['product_id'];
            $order->user_id = $_POST['user_id'];
            $order->qty = $_POST['qty'];
            $order->price = $_POST['price'];
            $order->save();
        }
    }
}
