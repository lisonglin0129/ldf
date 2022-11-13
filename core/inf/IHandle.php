<?php
namespace core\inf;

use core\App;

interface  IHandle
{
    /**
     * 注册处理机制
     *
     */
    public function register(App $app);
}

