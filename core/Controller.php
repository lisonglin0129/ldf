<?php
namespace core;
use core\view\View;

/**
 * 控制器
 * @author Administrator
 *
 */
class  Controller
{
    protected  $tmpconfig = [
        'cache_path'  => '',
    ];
    private $view;
    
    public function __construct()
    {
        $this->init_template();
    }
    
    public function assign($name="", $value="")
    {
        $this->view->assign($name, $value);
    }
    
    public  function fetch($template,$data=[],$config=[])
    {
        return $this->view->fetch($template,$data , $config);
    }
    
    public function init_template()
    {
        $config  = App::$container->getSingle('config');
        $tmp = $config->config['template']['tmp'];
        $tmp_catch_dir = $config->config['template']['cahce_dir'];
        if(!file_exists($tmp_catch_dir)){
            mkdir($tmp_catch_dir, 775);
        }
        if($tmp)
        {
            require CORE_PATH.'view/ViewDriver.php';
            require CORE_PATH.'view/Taglib.php';
            require CORE_PATH.'view/Tag.php';
            require CORE_PATH.'view/Template.php';
            require CORE_PATH.'view/View.php';
            $foldName = App::getFoldName();
            $moduleName = App::getModuleName();
            $controllerName = App::getControllerName();
            $viewPath =  ROOT_PATH . $foldName . '/'. $moduleName . '/' . $config->config['template']['view_path'] .'/';
            $viewPath = $viewPath . $controllerName.'/';
            $this->tmpconfig['cache_path'] = $tmp_catch_dir;
            $this->tmpconfig['view_path'] =  $viewPath;
            $this->view = new View($this->tmpconfig);
        }
    }
  
}

