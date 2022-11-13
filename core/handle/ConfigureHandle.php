<?php
namespace core\handle;

use core\inf\IHandle;
use core\App;

class ConfigureHandle implements IHandle
{
    /**
     * 框架实例
     *
     * @var object
     */
    private $app;
    /**
     * 配置
     *
     * @var array
     */
    private $config = [];
    /**
     * 构造函数
     */
    public function __construct()
    {
        # code...
    }
    /**
     * @param string $name 属性名称
     *
     * @return mixed
     */
    public function __get($name = '')
    {
        return $this->$name;
    }
    
    /**
     * 
     * @param string $name  属性名称
     * @param mixed  $value 属性值
     *
     * @return mixed
     */
    public function __set($name = '', $value = '')
    {
        $this->$name = $value;
    }
    
    public function register(App $app)
    {
       
        $this->loadConfig($app);
        $app::$container->setSingle('config', $this);
        // 设置时区
        // define time zone
        date_default_timezone_set($this->config['default_timezone']);
    }
    
    /**
     * 加载配置文件
     *
     * @param  App    $app 框架实例
     * @return void
     */
    public function loadConfig(App $app)
    {
        $defaultCommon   = require $app->ROOT_PATH . '/config/common.php';
        $defaultDatabase = require($app->ROOT_PATH . '/config/database.php');
        $this->config = array_merge($defaultCommon, $defaultDatabase);
        //$this->config['module'] = isset($this->config['module'])?$this->config['module']:["name"=>"index"];
        $this->config['defualt_fold_name'] = isset($this->config['defualt_fold_name'])?$this->config['defualt_fold_name']:["defualt_fold_name"=>"app"];
        $this->config['route']['default_module'] = isset($this->config['route']['default_module'])?$this->config['route']['default_module']:'index';
        $this->config['route']['default_controller'] = isset($this->config['route']['default_controller'])?$this->config['route']['default_controller']:'index';
        $this->config['route']['default_action'] = isset($this->config['route']['default_action'])?$this->config['route']['default_action']:'index';
        
    }
}

