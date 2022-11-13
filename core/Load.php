<?php
namespace core;

class Load
{
    public static $namespaceMap = [];
    /**
     * 类名映射
     *
     * @var array
     */
    public static $map = [];
    public static function register(App $app)
    {
     
        self::$namespaceMap = [
            'LDF' => $app->ROOT_PATH
        ];
      
        spl_autoload_register(['core\Load', 'autoload']);
        
    }
    /**
     * 自加载函数
     *
     * @param  string $class 类名
     *
     * @return void
     */
    public static function autoload($class)
    {

        $classOrigin = $class;
        $classInfo   = explode('\\', $class);
        $className   = array_pop($classInfo);
        foreach ($classInfo as &$v) {
            $v = strtolower($v);
        }
        unset($v);
      
        array_push($classInfo, $className);
        $class       = implode('\\', $classInfo);
       
        $path        = self::$namespaceMap['LDF'];
        $classPath   = $path . '/'.str_replace('\\', '/', $class) . '.php';
        self::$map[$classOrigin] = $classPath;
        if (file_exists($classPath)){
            require $classPath;
        }
    }
}

