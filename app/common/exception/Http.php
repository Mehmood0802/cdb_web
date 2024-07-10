<?php
declare (strict_types = 1);

namespace app\common\exception;

use think\exception\Handle;
use think\exception\HttpException;
use think\exception\ValidateException;
use think\Response;
use Throwable;

class Http extends Handle
{

    public static $_code_1 = 10001 ;  //系统报错
    public static $_code_5 = 10005 ;  //字段验证报错



    public function render($request, Throwable $e): Response
    {


        // 参数验证错误
        if ($e instanceof ValidateException) {
            return returnMsg($e->getError(), self::$_code_5 );
        }

        // 请求异常
        if ($e instanceof HttpException && $request->isAjax()) {
            return response($e->getMessage(), $e->getStatusCode());
        }


        // return returnMsg( $e->getMessage() , self::$_code_1 );

        // 其他错误交给系统处理
        return parent::render($request, $e);

    }

}


