<?php
namespace app\admin\service ;

// use think\facade\Filesystem ;
use yzh52521\filesystem\facade\Filesystem ;

class Uploads {

	static public $uri = '/uploads' ;
	
    //图片上传
    static public function img($file){
        $path = self::$uri ;
        // $file = request() -> file('file');
        $savename = Filesystem::disk('public')->putFile( $path , $file );
        $savename = str_replace('\\', '/', $savename);

        $a = substr($savename, strlen($savename) - 3 , 3) ;
        
        if($a != 'png' && $a != 'jpg' && $a != 'peg' && $a != 'gif' && $a != 'mp4'){
            return errMsg('图片或视频格式不对') ;
        } 

        
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/' ;

        // $url = "http://cdb2024.oss-ap-southeast-1.aliyuncs.com" ;
        // $url = "https://cdb2024.oss-ap-southeast-1.aliyuncs.com/" ;


        if($savename){
            return trueMsg('上传成功！', $url.$savename ) ;
        }else{
            return errMsg('上传失败！') ;
        }
    }

    //上传视频
    static public function video($file){
        $path = self::$uri ;
        $file = request() -> file('file');
        $savename = Filesystem::disk('aliyun')->putFile( $path , $file );
        $savename = str_replace('\\', '/', $savename);
        // $url = 'http://'.$_SERVER['HTTP_HOST'].'/' ;
        // $url = "http://cdb2024.oss-ap-southeast-1.aliyuncs.com" ;
        $url = "https://cdb2024.oss-ap-southeast-1.aliyuncs.com/" ;
        
        if($savename){
            return trueMsgApi('上传成功！', $url.$savename) ;
        }else{
            return errMsgApi('上传失败！') ;
        }
    }



    //图片上传
    static public function img2($file){

        $path = self::$uri ;
        // $file = request() -> file('file');
        try {
            validate(['image'=>'filesize:10240|fileExt:jpg|image:200,200,jpg'])
                ->check(['file'=>$file]);
            // $savename = [];
            // foreach($files as $file) {
            //     $savename[] = \think\facade\Filesystem::putFile( 'topic', $file);
            // }

            $savename = Filesystem::disk('public')->putFile( $path , $file );
            $savename = str_replace('\\', '/', $savename);

            $a = substr($savename, strlen($savename) - 3 , 3) ;
            
            if($a != 'png' && $a != 'jpg' && $a != 'peg' && $a != 'gif' && $a != 'mp4'){
                return errMsgApi('图片或视频格式不对') ;
            } 

            
            $url = 'http://'.$_SERVER['HTTP_HOST'].'/' ;
            if($savename){
                return trueMsgApi('上传成功！', $url.$savename ) ;
            }else{
                return errMsgApi('上传失败！') ;
            }

        } catch (\think\exception\ValidateException $e) {
            return errMsgApi($e->getMessage()) ;
        }
        
    }




}