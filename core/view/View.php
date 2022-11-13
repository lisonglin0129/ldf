<?php
namespace core\view;

class View
{
    
    // 模板引擎实例
    private $template;
    private $app;
    // 模板引擎参数
    protected  $config = [
        // 默认模板渲染规则 1 解析为小写+下划线 2 全部转换小写
        'auto_rule'   => 1,
        // 视图基础目录（集中式）
        'view_base'   => '',
        // 模板起始路径
        'view_path'   => '',
        // 模板文件后缀
        'view_suffix' => 'html',
        // 模板文件名分隔符
        'view_depr'   => DIRECTORY_SEPARATOR,
        // 是否开启模板编译缓存,设为false则每次都会重新编译
        'tpl_cache'   => true,
    ];
    public function __construct($config = [])
    {
      
        $this->config = array_merge($this->config, $config);
       
        $this->template = new Template($this->config);
    }
    
  
    /**
     * 模板变量赋值
     * @access public
     * @param  mixed $name  变量名
     * @param  mixed $value 变量值
     * @return $this
     */
    public function assign($name="", $value = '')
    {
        if (is_array($name)) {
            $this->template->assign(array_merge($name));
        } else {
            $this->template->assign([$name=>$value]);
        }
        return $this;
    }
    
    public  function fetch($template,$data=[],$config=[])
    {
        $template = $this->config['view_path'] . $template;
        if ('' == pathinfo($template, PATHINFO_EXTENSION)) {
            // 获取模板文件名
            $template = $this->parseTemplate($template);
        }
   
        // 模板不存在 抛出异常
        if (!is_file($template)) {
            echo $template."不存在";
            exit;
        }
        return $this->template->fetch($template, $data, $config);
    }
}

