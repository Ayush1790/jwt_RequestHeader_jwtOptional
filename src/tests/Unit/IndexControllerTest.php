<?php

declare(strict_types=1);

namespace Tests\Unit;

use MyApp\Models\Users;
use MyApp\Models\Products;
use MyApp\Models\Orders;

class IndexControllerTest extends AbstractUnitTest
//class UnitTest extends \PHPUnit\Framework\TestCase
{
    public function testAdd()
    {
        $userModel = new Users();
        $userModel->name = 'cedcoss1';
        $userModel->email = 'cedcoss@cedcoss.com';
        $userModel->pswd = 1;
        $userModel->role = 'user';
        $userModel->pincode = 1;
        $userModel->gender = 'male';
        $result = $userModel->save();
        $this->assertEquals(1, $result);

        $result = $userModel->delete();
        $this->assertEquals(1, $result);

        $products = new Products();
        $products->product_name = 'book';
        $products->img = 'book.png';
        $products->qty = 20;
        $products->price = 500;
        $products->desc = "boooooooooooook";
        $result = $products->save();
        $this->assertEquals(1, $result);

        $result=$products->delete();
        $this->assertEquals(1, $result);

        $order=new Orders();
        $order->product_id=5;
        $order->user_id=2;
        $order->qty=10;
        $order->amount=1000;
        $result = $order->save();
        $this->assertEquals(1, $result);

        $result=$order->delete();
        $this->assertEquals(1, $result);

    }
}
