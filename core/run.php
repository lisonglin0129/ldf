<?php
use core\App;
use core\Request;
use core\handle\ConfigureHandle;
use core\handle\SysDefaultHandle;
use core\handle\RouteHandle;
use core\Response;
use core\Controller;
use core\handle\ControllerHandle;

require_once  __DIR__ . '/App.php';
$FILE_ARR = explode("/", str_replace("\\","/",__FILE__));
$FILE_NAME = end($FILE_ARR);
define('CORE_PATH', str_replace($FILE_NAME, "", str_replace("\\","/",__FILE__)));
define('ROOT_PATH', CORE_PATH."../");

try{
    $app = new App(realpath(ROOT_PATH), function(){
        return require(CORE_PATH . 'Load.php');
    });
    /**
     * 配置加载
     */
    $app->load(function(){
        return new ConfigureHandle();
    });
    /**
     * 加载用户控制器，模型
     */
    $app->load(function(){
        return new SysDefaultHandle();
    });
 
    /**
     * 加载用户路由规则，及控制器关联
     */
    $app->load(function(){
        return new RouteHandle();
    });

    $app->run(function () use ($app) {
        return new Request($app);
    });
    $app->response(function($app){
        return new Response($app);  
    });
}catch (Exception $e){
    echo $e->getMessage();
}