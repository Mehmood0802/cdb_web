<?php
namespace app\admin\middleware;

use think\facade\Session ;
use app\admin\model\Admin ;

use app\admin\model\AdminPower ;
use app\admin\model\AdminRole ;



class Role 
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


        // dump($request);

        $controller = strtolower($request->controller() ) ;
        $action = strtolower($request->action() ) ;

        $where[] = ['controller','=', $controller ];
        $where[] = ['action','=', $action ];
        $power = AdminPower::getOneW($where) ;

        $account = Admin::getOne(Session::get('admin_id')) ;

        if($account){
            if($account['role_id'] && $power ){
                $role = AdminRole::getOne($account['role_id']) ;
                $role_arr = explode(',', $role['power_ids'] ) ;

                if(!in_array($power['id'], $role_arr )){
                    die('无权限！');
                }

            }
        }
        

        return $next($request);

    }



}