<?php
namespace core\handle;
use core\inf\IHandle;
use core\inf\IController;
use core\App;
use core\view\View;
use core\Controller;

class ControllerHandle implements IHandle,IController
{
    /**
     * 框架实例
     *
     * @var object
     */
    private $app;
    
    protected $view;
    
    protected  $tmpconfig = [
        'cache_path'  => '',
    ];
    
    public function getView()
    {
        return $this->view;
    }
    
    public function register(App $app)
    {
        $app::$container->set('Controller', $this);
        // 获取配置
        $config  = $app::$container->getSingle('config');
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
            $foldName = $app->getFoldName();
            
            $moduleName = $app->getModuleName();
            $viewPath =  ROOT_PATH . $foldName . '/'. $moduleName . '/' . $config->config['template']['view_path'] .'/';
            $this->tmpconfig['cache_path'] = $tmp_catch_dir;
            $this->tmpconfig['view_path'] =  $viewPath;
            $this->view = new View($this->tmpconfig);
        }
    }
}

