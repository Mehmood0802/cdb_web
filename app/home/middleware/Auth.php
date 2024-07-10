<?php
namespace app\home\middleware;

use think\facade\Session ;
use think\facade\Cookie ;


class Auth 
{

    /**
     * 默认返回资源类型
     * @var \think\Request $request
     * @var mixed $next
     * @var string $name
     * @throws \Exception
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {


        return $next($request);

    }



}