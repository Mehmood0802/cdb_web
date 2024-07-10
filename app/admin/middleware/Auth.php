<?php
namespace app\admin\middleware;

use think\facade\Session ;
use think\facade\Cookie;
use app\admin\model\Admin ;

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
        //cookie
        if( Cookie::get('admin_id') ){
            Session::set('admin_id', Cookie::get('admin_id')  ) ;
        }


        // dump( Session::get('admin_id') ) ; die;


        //白名单
        $allowUri=[
            'other/pdf' ,
            'other/login' ,
            'other/verify' ,
        ];
        //路由
        $uri = strtolower($request->controller()).'/'.$request->action() ;
        //判断
        if(!in_array($uri,$allowUri)){
            if(empty(Session::get('admin_id'))){
                return redirect(Url('other/login')) ;
            }
        }
        return $next($request);

    }



}