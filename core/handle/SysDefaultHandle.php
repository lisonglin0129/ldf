<?php
namespace core\handle;

use core\inf\IHandle;
use core\App;
/**
 * 自定义handle
 * 用户可以自定义框架运行到路由前的操作
 * @author  lisonglin
 */
class SysDefaultHandle implements IHandle
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        
    }
    public function register(App $app)
    { 
        /**
        // 获取配置
        $config  = $app::$container->getSingle('config');
        
        foreach ($config->config['defualt_fold_name'] as $v) {
            $v = ucwords($v);
            $className = "\App\\{$v}\\Logics\DefinedCase";
            if(class_exists($className)){
                new $className($app);
            }
        }
        **/
    }
}

