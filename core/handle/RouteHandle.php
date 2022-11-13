<?php
namespace core\handle;

use core\inf\IHandle;
use core\App;
use core\Route;

class RouteHandle implements IHandle
{
    
    public function register(App $app)
    {
        (new Route())->init($app);
    }
}

