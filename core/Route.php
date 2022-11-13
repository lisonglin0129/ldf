<?php
namespace core;

use core\inf\IRoute;

class Route implements IRoute
{
    /**
     * 框架实例
     *
     * the framework instance
     *
     * @var App
     */
    private $app;
    /**
     * 框架实例
     *
     * the framework instance
     *
     * @var App
     */
    private $config;
    
    
    
    /**
     * 请求对象实例
     *
     * the request instance
     *
     * @var
     */
    private $request;
    
    private $foldName;
    
    /**
     * 请求对象实例
     *
     * the request instance
     *
     * @var
     */
    private $moduleName;
    /**
     * 默认控制器
     *
     * default controller
     *
     * @var string
     */
    private $controllerName = '';
    /**
     * 默认操作.
     *
     * default action
     *
     * @var string
     */
    private $actionName = '';
    /**
     * 类文件路径.
     *
     * class path
     *
     * @var string
     */
    private $classPath = '';
    /**
     * 类文件执行类型.
     *
     * ececute type
     *
     * @var string
     */
    private $executeType = 'controller';
    /**
     * 请求uri.
     *
     * the request uri
     *
     * @var string
     */
    private $requestUri = '';
    
    private $route = [];
    private $route_url="";
    
    public static $routele=[];
    
    
    /**
     *
     * @param string $name 属性名称
     *
     * @return mixed
     */
    public function __get($name = '')
    {
        return $this->$name;
    }
    public static function get($route, $action)
    {
      
        if(!is_object($action)){
            list($class, $action) = explode("@", $action);
            $class = str_replace("/","\\", $class);
            if("" != $action) {
                array_push(self::$routele, [
                    "ROUTE_URL"=> $route,
                    "CLASS"=> $class,
                    "TYPE"=>"OBJ",
                    "FUNCTION_NAME"=>$action,
                ]);
            }else{
                array_push(self::$routele, [
                    "ROUTE_URL"=> $route,
                    "CLASS_NAME"=> $class,
                    "TYPE"=>"OBJ",
                    "FUNCTION_NAME"=>"",
                ]);
            }
        }else{
            array_push(self::$routele, [
                "ROUTE_URL"=> $route,
                "TYPE"=>"FUN",
                "FUNCTION_NAME"=>$action,
            ]);
        } 
    }
    /**
     * @param string $name  属性名称
     * @param mixed  $value 属性值
     *
     * @return mixed
     */
    public function __set($name = '', $value = '')
    {
        $this->$name = $value;
    }
    /**
     * 注册路由处理机制.
     *
     * @param App $app 框架实例
     * @param void
     */
    public function init(App $app)
    {
       
        //--注入当前对象到容器中
        $app::$container->set('router', $this);
        $this->request  = $app::$container->get('request');
        $this->requestUri     = $this->request->server('REQUEST_URI');
        // App
        $this->app            = $app;
        // 获取配置 get config
        $this->config         = $app::$container->getSingle('config');
        $this->foldName       = $this->config->config['defualt_fold_name'];
        // 设置默认模块 set default module
        $this->moduleName     = $this->config->config['route']['default_module'];
        // 设置默认控制器 set default controller
        $this->controllerName = $this->config->config['route']['default_controller'];
        // 设置默认操作 set default action
        $this->actionName     = $this->config->config['route']['default_action'];
        
        $this->makeClassPath($app);
       
        $this->start($app);
    }
    public function makeClassPath(App $app)
    {
      
        $controllerName    = ucfirst($this->controllerName);
        $folderName        = ucfirst($this->config->config['defualt_fold_name']);
       
        $this->classPath   = "{$folderName}\\{$this->moduleName}\\Controller\\{$controllerName}";
        $this->classPath =   $this->classPath ;

    }
    public function makeRouutUrl(App $app)
    {
     
        $urlStr = $this->request->server("REQUEST_URI");
        $url_arr = [];
        list($qurl, $q_param) = explode("?", $urlStr);
        $this->route_url = trim($qurl);
    }
    
    public function start(App $app)
    { 
        $this->request  = $app::$container->get('request');
        require_once $app->ROOT_PATH."/config/route.php";
        $this->makeRouutUrl($app);
        $route_status = false;
        if(0<sizeof(self::$routele)){
            foreach(self::$routele AS $route)
            {
                if($route['TYPE'] == 'OBJ') {
                    if(strcasecmp($this->route_url,$route['ROUTE_URL']) === 0){
                        if (!class_exists($route['CLASS'])) {
                            echo $route['CLASS']."类不存在";
                            exit;
                        }
                        if('' == $route['FUNCTION_NAME']) {
                            $route['FUNCTION_NAME'] = $this->actionName;
                        }
                        list($this->foldName,$this->moduleName, $ControllerFold, $this->controllerName) = explode("\\", $route['CLASS']);
                      
                        $controller = new $route['CLASS']($this->request, (new Response($app)));
                        if (!is_callable([$controller,$route['FUNCTION_NAME']])) {
                            echo $route['FUNCTION_NAME']."方法不存在";
                            exit;
                        }
                        $route_status = true;
                        $this->actionName = $route['FUNCTION_NAME'];
                        $this->app->responseData  =  $controller->{$route['FUNCTION_NAME']}($this->request, (new Response($app)));
                    }
                }else{
                    $route['FUNCTION_NAME']();
                }
            }
            if($route_status == false){
                $c = ""; $f="";
                if($this->route_url != "") {
                    $qurl = substr($this->route_url, 1, strlen($this->route_url));
                    $url_arr = explode("/", $qurl);
                    list($m, $c, $f) = $url_arr;
                }
                $this->moduleName = $m == ""?$this->moduleName:$m;
                $this->controllerName = $c == ""?$this->classPath:ucfirst($c);
                $this->actionName = $f == ""?$this->actionName:$f;
                $Class = $this->foldName."\\".$this->moduleName."\\Controller\\".$this->controllerName;
                if (!class_exists($Class)) {
                    throw new \Exception("类不存在".$Class, 500);
                }
                $controller = new $Class($this->request, (new Response($app)));
                if (!is_callable([$controller,$this->actionName])) {
                    throw new \Exception("方法不存在".$this->actionName, 500);
                }
                $this->app->responseData  = $controller->{$this->actionName}($this->request, (new Response($app)));
            }
        }else{
            // 判断控制器存不存在
            if (!class_exists($this->classPath)) {
                echo "类不存在";
                exit;
            }
            // 实例化当前控制器
            $controller = new $this->classPath($this->request, (new Response($app)));
            if (!is_callable([$controller, $this->actionName])) {
                echo "方法不存在";
                exit;
            }
            $this->request  = $app::$container->get('request');
            $actionName = $this->actionName;
            $this->app->responseData  =  $controller->$actionName($this->request, (new Response($app)));
        }
    }
}

