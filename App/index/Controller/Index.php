<?php
namespace App\index\Controller;


use App\BaseController;
use core\Request;

class Index extends BaseController
{

    public function test(Request $request)
    {
        return $this->fetch("c.html");
    }
}

