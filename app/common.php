<?php
// 应用公共文件
declare(strict_types = 1);

use think\Config ;
use think\facade\Session ;

//use chillerlan\QRCode\{QRCode, QROptions};



use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;


use app\api\service\QRCode as GTQRCode ;




//订单状态
function orderState($id){
    $list = Config('set.order_status') ;
    foreach($list as $k => $v){
        if($v['id'] == $id){
            return $v['title'] ;
        }
    }
}


//pdf保存到本地
function pdf_down($url,$rename,$ext='pdf'){

    $save_path = $_SERVER['DOCUMENT_ROOT'].'/uploads/pdf/'.$rename.".".$ext;

    $fp = fopen($url,'r');

    $re = file_put_contents($save_path, $fp );

    fclose($fp);

    $http = 'https://'.$_SERVER['HTTP_HOST'].'/uploads/pdf/'.$rename.".".$ext ;

    return $http ;

}





//admin后台返回
function trueMsg($message='',$data=[]){
	return json(['status'=>Config('status.success'),'message'=>$message,'data'=>$data]) ;
}

function errMsg($message='',$data=[]){
	return json(['status'=>Config('status.error'),'message'=>$message,'data'=>$data]) ;
}


//操作失败
function errMsgC($message='',$data=[]){
    return json(['status'=>Config('status.error_action'),'message'=>$message,'data'=>$data]) ;
}


//字段验证错误
function errMsgV($message='',$data=[]){
    return json(['error'=>$message]) ;
    // return json(['status'=>Config('status.validate'),'message'=>$message,'data'=>$data]) ;
}
//系统错误
function errMsgS($message='',$data=[]){
    return json(['error'=>$message]) ;
    // return json(['status'=>Config('status.system'),'message'=>$message,'data'=>$data]) ;
}




//api的返回
function trueMsgApi($message='',$data=[]){
    return json(['code'=>Config('status.success_api'),'message'=>$message,'data'=>$data]) ;
}

function errMsgApi($message='',$data=[]){
    return json(['code'=>Config('status.error_api'),'message'=>$message,'data'=>$data]) ;
}


//token错误 2023-10-04
function errTokenApi($message='',$data=[]){
    return json(['code'=>Config('status.error_token'),'message'=>$message,'data'=>$data]) ;
}

//java数据格式 2023-10-04
function trueJavaApi($message='',$data=[]){
    $current = 1 ;
    $pageSize = 10 ;
    $total = 1 ;
    if($data){
        if(array_key_exists('current',$data)){
            $current = $data['current'] ;
        }
        if(array_key_exists('pageSize',$data)){
            $pageSize = $data['pageSize'] ;
        }
        if(array_key_exists('total',$data)){
            $total = $data['total'] ;
        }
        if(array_key_exists('data',$data)){
            $data = $data['data'] ;
        }
    }
    return json(['code'=>Config('status.success_api'),'message'=>$message,'data'=>$data, 'current'=>$current, 'pageSize'=>$pageSize, 'total'=>$total ]) ;
}










//前端接口 2023-10-25   gt cdb project
//查询
function trueGT($message='', $data=[] , $type=1 ,$limit=10 ){
    //列表
    if($type == 1){
        $result = [
            // 'msg'=>$message ,
            'list'=>$data['data'] ,
            'pageInfo'=>[ 
                'totalPage' => $data['last_page'], 
                'totalCount' => $data['total'], 
                'page' => $data['current_page'], 
                'limit' => $limit , 
            ] ,

        ];
        return json($result);
    }

    //单页
    if($type == 2){
        return json(['data'=>$data ]) ;
    }

    //
    if($type == 3){
        return json(['detail'=>$data ]) ;
    }
    
    //配置文件
    if($type == 4){
        return json(['info'=>$data ]) ;
    }
    //配置文件
    if($type == 5){
        return json($data) ;
    }
    if($type == 6){
        return json(['user_info'=>$data ]) ;
    }
}

//查询所有
function trueGTA($message='', $data=[] ){
    return json(['list'=>$data]) ;
}

//查询失败
function errGT($message=''){
    return json(['error'=>$message]) ;
}


//增删改 操作成功
function trueGTC($message='', $skip='' ){
    return json(['msg'=>$message, 'skip'=>$skip ] ) ;
}
//增删改 操作失败
function errGTC($message='', $skip='' ){
    return json(['error'=>$message, 'skip'=>$skip ] ) ;
}








//session
function sessionSet($key='',$value=''):void{
	Session::set($key, $value);
}

//加密方式
function passMd5($pass,$code){
	return md5(md5($pass).$code);
}


//距离的处理
function distance_name($distance){
    if($distance < 1000 && $distance>0){
        return round($distance,2).'m';
    }elseif ($distance<=0){
        return '0m';
    }else if($distance > 100000 ){
        // return '>100km';
        return round($distance/1000,2).'km';
    }else{
        return round($distance/1000,2).'km';
    }
}


//计算2个坐标之间的距离
function distance_count($lat1, $lng1, $lat2, $lng2){
    $PI = 3.1415926;
    $EARTH_RADIUS = 6370.996; // 地球半径系数
      $radLat1 = $lat1 * ($PI / 180);
      $radLat2 = $lat2 * ($PI / 180);
      $a = $radLat1 - $radLat2;
      $b = ($lng1 * ($PI / 180)) - ($lng2 * ($PI / 180));
      $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
      $s = $s * $EARTH_RADIUS;
      $s = round($s * 10000) / 10000;
  return distance_name($s);
}




/**
 * 生成随机字符串（默认16位）
 * @param  [type] $length 随机字符串位数
 * @return [type] $str    生成的随机字符串
 * jervis 2017.6.2
 */
function createNonceStr($length=16,$type=1) {
    //大小写字母数字
    if($type==1){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    //小写字母数字
    if($type==2){
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    //数字
    if($type==3){
        $chars = "0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    //大写字母 + 数字
    if($type==4){
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }


}


//字符串转十六进制函数
function strToHex($str){ 
    $hex="";
    for($i=0;$i<strlen($str);$i++)
    $hex.=dechex(ord($str[$i]));
    $hex=strtoupper($hex);
    return $hex;
}

//十六进制转字符串函数
function hexToStr($hex){   
    $str=""; 
    for($i=0;$i<strlen($hex)-1;$i+=2)
    $str.=chr(hexdec($hex[$i].$hex[$i+1]));
    return  $str;
} 


//区号+手机号  去掉前面的 + 
function areaMobile($area,$mobile){   
    $a = substr($area,0,1);
    // if($a == '+'){
    //     return substr($area,1).$mobile ;
    // }else{
        return $area.$mobile ;
    // }
}



/**
 * GET 请求
 * @param string $url
*/
function http_get($url,$headers=[],$type=0){
    $oCurl = curl_init();
    if(stripos($url,"https://")!==FALSE){
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
    }
    

    curl_setopt($oCurl, CURLOPT_URL, $url);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($oCurl, CURLOPT_HTTPHEADER, $headers);
    

    if($type == 1){
        // 开启异步模式
        // curl_setopt($oCurl, CURLOPT_ASYNC, true);
        curl_setopt($oCurl, CURLOPT_NOSIGNAL, true); // 开启异步选项
        curl_setopt($oCurl, CURLOPT_TIMEOUT_MS, 200); // 设置超时时间
    }


    $sContent = curl_exec($oCurl);
    $aStatus = curl_getinfo($oCurl);
    curl_close($oCurl);

    // dump($sContent);
    // dump($aStatus);  

    

    if(intval($aStatus["http_code"])==200){
        return $sContent;
    }else{
        return false;
    }
}

    
/**
 * POST 请求
 * @param string $url 
 * @param array $param 
 * @return string content
*/
function http_post($url,$param,$headers=[]){
    $oCurl = curl_init();
    if(stripos($url,"https://")!==FALSE){
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
    }

    if (is_string($param)) {
        $strPOST = $param;
    } else {
        $strPOST =  json_encode($param);
    }
    curl_setopt($oCurl, CURLOPT_URL, $url);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($oCurl, CURLOPT_POST,true);
    curl_setopt($oCurl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
    $sContent = curl_exec($oCurl);
    $aStatus = curl_getinfo($oCurl);
    curl_close($oCurl);

    // var_dump($sContent);
    // var_dump($aStatus);    die;

    if(intval($aStatus["http_code"])==200 || intval($aStatus["http_code"])==201){
        return $sContent;
    }else{
        return false;
    }
}


//图片处理 带上url
function listImgHttp($list){
    $arr = $list['data'] ;
    $url = 'http://'.$_SERVER['HTTP_HOST'] ;
    if(count($arr) > 0 ){
        foreach ($arr as $key => $value) {
            if(array_key_exists('avatar', $value)){
                if($value['avatar'] && substr($value['avatar'],0,4) != 'http' ){
                    $arr[$key]['avatar'] = $url.str_replace("\\","/",$value['avatar']) ;
                }
            }
            if(array_key_exists('img', $value)){
                if($value['img'] && substr($value['img'],0,4) != 'http' ){
                    $arr[$key]['img'] = $url.str_replace("\\","/",$value['img']) ;
                }
            }
            if(array_key_exists('pic', $value)){
                if($value['pic'] && substr($value['pic'],0,4) != 'http' ){
                    $arr[$key]['pic'] = $url.str_replace("\\","/",$value['pic']) ;
                }
            }
            if(array_key_exists('qrcode', $value)){
                if($value['qrcode'] && substr($value['qrcode'],0,4) != 'http' ){
                    $arr[$key]['qrcode'] = $url.str_replace("\\","/",$value['qrcode']) ;
                }
            }
            if(array_key_exists('album', $value)){
                $arr2 = array_filter(explode(',', $value['album']) ) ;
                foreach ($arr2 as $k => $v) {
                    if($v && substr($v,0,4) != 'http' ) {
                        $arr2[$k] = $url.str_replace("\\","/",$v) ;
                    }
                }
                $arr[$key]['album'] = $arr2 ;
            }
            if(array_key_exists('content', $value)){
                if($value['content'] && substr($value['img'],0,4) != 'http' ){
                    // $arr[$key]['content'] = str_replace('<img src="', '<img style="width:100%" src="'.$url , $value['content'] ) ; 
                }
            }
        }
    }
    $list['data'] = $arr ;
    return $list ;
}


//单条数据 替换图片地址
function dataImgHttp($data){
    if($data){
        $url = 'http://'.$_SERVER['HTTP_HOST'] ;
        if(array_key_exists('avatar', $data)){
            if($data['avatar'] && substr($data['avatar'],0,4) != 'http' ){
                $list[$key]['avatar'] = $url.str_replace("\\","/",$data['avatar']) ;
            }
        }
        if(array_key_exists('img', $data)){
            if($data['img'] && substr($data['img'],0,4) != 'http' ){
                $data['img'] = $url.str_replace("\\","/",$data['img']) ;
            }
        }
        if(array_key_exists('pic', $data)){
            if($data['pic'] && substr($data['pic'],0,4) != 'http' ){
                $data['pic'] = $url.str_replace("\\","/",$data['pic']) ;
            }
        }
        if(array_key_exists('qrcode', $data)){
            if($data['qrcode'] && substr($data['qrcode'],0,4) != 'http' ){
                $data['qrcode'] = $url.str_replace("\\","/",$data['qrcode']) ;
            }
        }
        if(array_key_exists('album', $data)){
            if($data['album']){
                $arr = array_filter(explode(',', $data['album']) ) ;
                if(count($arr) > 0 ){
                    foreach ($arr as $k => $v) {
                        if($v && substr($v,0,4) != 'http' ) {
                            $arr[$k] = $url.str_replace("\\","/",$v) ;
                        }
                    }
                }
                $data['album'] = $arr ;
            }
        }
        if(array_key_exists('content', $data)){
            if($data['content']){
                 // $data['content'] = str_replace('<img src="', '<img style="width:100%" src="'.$url , $data['content'] ) ; 
            }
        }
    }
    return $data ;
}



//all
function allImgHttp($list){
    $arr = $list ;
    $url = 'http://'.$_SERVER['HTTP_HOST'] ;
    if(count($arr) > 0 ){
        foreach ($arr as $key => $value) {
            if(array_key_exists('avatar', $value)){
                if($value['avatar'] && substr($value['avatar'],0,4) != 'http' ){
                    $arr[$key]['avatar'] = $url.str_replace("\\","/",$value['avatar']) ;
                }
            }
            if(array_key_exists('img', $value)){
                if($value['img'] && substr($value['img'],0,4) != 'http' ){
                    $arr[$key]['img'] = $url.str_replace("\\","/",$value['img']) ;
                }
            }
            if(array_key_exists('pic', $value)){
                if($value['pic'] && substr($value['pic'],0,4) != 'http' ){
                    $arr[$key]['pic'] = $url.str_replace("\\","/",$value['pic']) ;
                }
            }
            if(array_key_exists('qrcode', $value)){
                if($value['qrcode'] && substr($value['qrcode'],0,4) != 'http' ){
                    $arr[$key]['qrcode'] = $url.str_replace("\\","/",$value['qrcode']) ;
                }
            }
            if(array_key_exists('album', $value)){
                $arr2 = array_filter(explode(',', $value['album']) ) ;
                foreach ($arr2 as $k => $v) {
                    if($v && substr($v,0,4) != 'http' ) {
                        $arr2[$k] = $url.str_replace("\\","/",$v) ;
                    }
                }
                $arr[$key]['album'] = $arr2 ;
            }
            if(array_key_exists('content', $value)){
                if($value['content'] && substr($value['img'],0,4) != 'http' ){
                    // $arr[$key]['content'] = str_replace('<img src="', '<img style="width:100%" src="'.$url , $value['content'] ) ; 
                }
            }
        }
    }
    $list = $arr ;
    return $list ;
}



//生成二维码
function wxUrlQrcode($code,$id){

    $options = new QROptions([
        'version'    => 7,                             //二维码版本
        'outputType' => QRCode::OUTPUT_IMAGE_JPG,      //生成图片
        'eccLevel'   => QRCode::ECC_H,                 //错误级别
        'scale'=>10,                            //二维码大小
        'image' => ''                                 
    ]);
    $qrcode = new QRCode($options);
    $data = $code ;                   //生成二维码的内容
    $path = "/uploads/qrcode/m_wx_".$id.".png" ;     //二维码的保存路径    
    $file_path = $_SERVER['DOCUMENT_ROOT'].$path ;                            
    $qrcode->render($data, $file_path);

    //加logo


    $ob_contents = file_get_contents($file_path); 
    $myImage = ImageCreate(245,245); //参数为宽度和高度
    $qr = imagecreatefromstring($ob_contents);
    $logo = $_SERVER['DOCUMENT_ROOT'].'/logo.png'; //需要显示在二维码中的Logo图像
    $logo = imagecreatefromstring(file_get_contents($logo));
    $qr_width = imagesx ($qr);
    $qr_height = imagesy ($qr);
    $logo_width = imagesx ($logo);
    $logo_height = imagesy ($logo);
    $logo_qr_width = intval( $qr_width / 5 ) ;
    // $scale = intval( $logo_width / $logo_qr_width ) ;
    $logo_qr_height = intval( $qr_width / 5 ) ;
    $from_width = intval( ($qr_width - $logo_qr_width) / 2 ) ;

    // dump($qr);
    // dump($logo);
    // dump($from_width);
    // dump($from_width);
    // dump($logo_qr_width);
    // dump($logo_qr_height);
    // dump($logo_width);
    // dump($logo_height); die;


    imagecopyresampled ( $qr, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height );
    imagepng ( $qr, $file_path ); //带Logo二维码的文件名

    return $path ;
    
}


//订单编号 二维码
function orderBnQrcode($bn){

    $options = new QROptions([
        'version'    => 7,                             //二维码版本
        'outputType' => QRCode::OUTPUT_IMAGE_JPG,      //生成图片
        'eccLevel'   => QRCode::ECC_H,                 //错误级别
        'scale'=>10,                            //二维码大小
        'image' => ''                                 
    ]);
    $qrcode = new QRCode($options);
    $data = $bn ;                   //生成二维码的内容
    $path = "/uploads/order/".$bn.".png" ;     //二维码的保存路径    
    $file_path = $_SERVER['DOCUMENT_ROOT'].$path ;                            
    $qrcode->render($data, $file_path);

    //加logo


    $ob_contents = file_get_contents($file_path); 
    $myImage = ImageCreate(245,245); //参数为宽度和高度
    $qr = imagecreatefromstring($ob_contents);
    $logo = $_SERVER['DOCUMENT_ROOT'].'/logo.png'; //需要显示在二维码中的Logo图像
    $logo = imagecreatefromstring(file_get_contents($logo));
    $qr_width = imagesx ($qr);
    $qr_height = imagesy ($qr);
    $logo_width = imagesx ($logo);
    $logo_height = imagesy ($logo);
    $logo_qr_width = intval( $qr_width / 5 ) ;
    // $scale = intval( $logo_width / $logo_qr_width ) ;
    $logo_qr_height = intval( $qr_width / 5 ) ;
    $from_width = intval( ($qr_width - $logo_qr_width) / 2 ) ;

    // dump($qr);
    // dump($logo);
    // dump($from_width);
    // dump($from_width);
    // dump($logo_qr_width);
    // dump($logo_qr_height);
    // dump($logo_width);
    // dump($logo_height); die;


    imagecopyresampled ( $qr, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height );
    imagepng ( $qr, $file_path ); //带Logo二维码的文件名

    return $path ;
    
}



//前端要的 支付二维码  base64
function payNowCode($bn){
    // ob_start();

    //====================================================== 长度溢出 报错

    // $options = new QROptions([
    //     'version'    => 7,                             //二维码版本
    //     'outputType' => QRCode::OUTPUT_IMAGE_JPG,      //生成图片
    //     'eccLevel'   => QRCode::ECC_H,                 //错误级别
    //     'scale'=>10,                            //二维码大小
    //     'image' => ''                                 
    // ]);
    // $qrcode = new QRCode($options);
    // $data = $bn ;                   //生成二维码的内容

    // $re = $qrcode->render($data);

    // // ob_end_clean(); //释放缓冲区并关闭缓冲区

    // return $re;

    //===================================================================  需要 imagick 插件

    // $path = "/uploads/fomopay/qrcode.png" ;     //二维码的保存路径  
    // $file_path = $_SERVER['DOCUMENT_ROOT'].$path ;     


    // $renderer = new ImageRenderer(
    //     new RendererStyle(600,2),
    //     new ImagickImageBackEnd()
    // );

    // $writer = new Writer($renderer);
    // $writer->writeFile('Hello World!', $file_path );



    //======================== 顾涛版本

    $path = "/uploads/fomopay/".date('YmdHis').rand(100,999).".png" ;     //二维码的保存路径  
    $file_path = $_SERVER['DOCUMENT_ROOT'].$path ;     

    GTQRCode::png($bn,$file_path);

    return 'http://'.$_SERVER['HTTP_HOST'].$path ;

}

