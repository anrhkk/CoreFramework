<?php namespace core\kernel;

/*
 * 服务抽象类
 */
abstract class ServiceProvider
{

    //延迟加载
    public $defer = false;

    //应用程序实例
    protected $app;

    //注册服务

    public function __construct($app)
    {
        $this->app = $app;
    }

    abstract function register();

    public function __call($method, $args)
    {
        if ($method == 'boot') {
            return;
        }
        throw new BadMethodCallException("$method 方法不存在 ");
    }
}




