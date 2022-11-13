<?php
namespace App\index\Controller;
use App\BaseController;

class Text extends BaseController
{
    public function index()
    {
       return $this->fetch("a.html");
    }
}

