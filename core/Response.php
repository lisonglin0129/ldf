<?php
namespace core;
/**
 * 响应
 *
 */
class Response
{
    /**
     * app instance
     *
     */
    private $app = null;
    /**
     * 构造函数
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }
    public function text($str="")
    {
        
        header('Content-Type', 'Application/text');
        header('Charset', 'utf-8');
        return $str;
    }
   
    public function render($data)
    {
        echo $data;
        exit;
    }
}

