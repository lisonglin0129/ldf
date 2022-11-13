<?php
namespace core;

class App
{
    /**
     * 框架加载流程一系列处理类集合
     *
     * @var array
     */
    private $handlesList = [];
    /**
     * 框架实例根目录
     *
     * @var string
     */
    public  $ROOT_PATH;
    /**
     * 框架实例
     *
     * @var object
     */
    public static $app;
    /**
     * 服务容器
     *
     * @var object
     */
    public static $container;
    /**
     * 响应对象
     *
     * @var object
     */
    public $responseData;
    
    public function __construct($root_path, \Closure $loader)
    {
        $this->ROOT_PATH = $root_path;
        $loader();
        Load::register($this);
        self::$app = $this;
        self::$container = new Container();
    }
    /**
     * 注册框架运行过程中一系列处理类
     *
     * @param  object $handle handle类
     * @return void
     */
    public function load(\Closure $handle)
    {
        $this->handlesList[] = $handle;
    }
    public static function getFoldName()
    {
        return self::$container->get("router")->foldName;
    }
    public static function getActionName()
    {
        return self::$container->get("router")->actionName;
    }
    public static function getModuleName()
    {
        return self::$container->get("router")->moduleName;
    }
    public static function getControllerName()
    {
        return self::$container->get("router")->controllerName;
    }
    public static function getApplication($app="")
    {
        return self::$container->get($app);
    }
    
    /**
     * 运行应用
     *
     * fpm mode
     *
     * @param  Request $request 请求对象
     * @return void
     */
    public function run(\Closure $request)
    {
        self::$container->set('request', $request);
        foreach ($this->handlesList as $handle) {
            $handle()->register($this);
        }
    }
    /**
     * 生命周期结束
     *
     * 响应请求
     * @param   $closure |  响应类
     * @return  object
     */
    public function response(\Closure $response)
    {
        register_shutdown_function([$this, 'responseShutdownFun'], $response);
    }
    /**
     * shutdown response
     *
     * @param  $closure
     * @return void
     */
    public function responseShutdownFun(\Closure $closure)
    {
        $closure($this)->render($this->responseData);
    }
}

