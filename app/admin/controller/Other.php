<?php
declare(strict_types = 1);

namespace app\admin\controller;

use app\admin\Controller;

use think\facade\View;
use think\captcha\facade\Captcha;
use think\facade\Session ;
use think\facade\Db ;

use app\admin\model\Admin;


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\File\IOFactory as a ;



use app\admin\service\Uploads ;


class Other extends Controller
{

	public function initialize(){
		parent::initialize();

	}

	//登录
    public function login()
    {
        if($this->isPost){
            //验证码
            if(!Captcha::check($this->postData['vercode']) ){
                return errMsg('验证码不正确！');
            }

            //登录
            return Admin::login($this->postData) ;
        }

        // echo md5(md5('123123').'IkW9QLGd');
    	return view();
    }


    //验证码
    public function verify(){
        return Captcha::create();   
    }

    //登出
    public function logout(){
        Session::clear();
        return redirect('/admin/other/login') ;
    }



     //图片上传
    public function uploadImg(){
        // if($this->isPost){
            $file = request() -> file('file');
            return Uploads::img($file) ;
        // }
    }



    //图片上传
    // public function uploadImg(){
    //     $path = '/uploads/carwash' ;
    //     $file = request() -> file('file');
    //     $savename = \think\facade\Filesystem::disk('public')->putFile( $path , $file );
    //     $savename = str_replace('\\', '/', $savename);
    //     $url = '/';// 'http://'.$_SERVER['HTTP_HOST'].'/' ;
    //     if($savename){
    //         return trueMsg('上传成功！', $url.$savename ) ;
    //     }else{
    //         return errMsg('上传失败！') ;
    //     }
    // }

    //上传视频
    // public function uploadVideo(){
    //     $path = '/uploads/carwash' ;
    //     $file = request() -> file('file');
    //     $savename = \think\facade\Filesystem::disk('public')->putFile( $path , $file );
    //     $savename = str_replace('\\', '/', $savename);
    //     $url = 'http://'.$_SERVER['HTTP_HOST'].'/' ;
    //     if($savename){
    //         return trueMsg('上传成功！', $url.$savename) ;
    //     }else{
    //         return errMsg('上传失败！') ;
    //     }
    // }

    //上传文件
    // public function uploadFile(){
    //     $file = request() -> file('file');
    //     $savename = \think\facade\Filesystem::disk('public')->putFile( 'uploads', $file);
    //     $res =  $this -> excel_import($savename) ;

    //       // 
    //     if($res){
    //         return trueMsg('上传成功！', '/'.$savename) ;
    //     }else{
    //         return errMsg('上传失败！') ;
    //     }
    // }


    /**
     * 导入商品
     */
    protected function excel_import($file){

        $fileExtendName = substr(strrchr($file, '.'), 1);
        $filename = $file;
        if ($fileExtendName == 'xlsx') {
            $objReader = IOFactory::createReader('Xlsx');
        }else{
            $objReader = IOFactory::createReader('Xls');
        }

        // 启动事务
        Db::startTrans();
        try{
            $objPHPExcel = $objReader->load($filename);  //$filename可以是上传的表格，或者是指定的表格
            $sheet = $objPHPExcel->getSheet(0);   //excel中的第一张sheet
            $highestRow = $sheet->getHighestRow();
            $a = 0;


                $t = [] ;
                for ($i = 2; $i <= $highestRow; $i++) {
                    $data[$a]['bn'] = (string)$objPHPExcel->getActiveSheet()->getCell( 'A' . $i)->getValue();
                    $data[$a]['money'] = (string)$objPHPExcel->getActiveSheet()->getCell( 'B' . $i)->getValue();

                   
                    $t[] = [
                        'bn' => $data[$a]['bn'], 
                        'money' => $data[$a]['money'],
                        'create_time' => time() ,
                        'status' => 1 ,
                    ];


                    // $old = Db::name("market_card_log")->where( ['bn'=> $data[$a]['bn'] ] )->find();
                    // if(!$old){
                    // }

                }


                $re = Db::name("market_card_log")->insertAll($t);
                // dump($re); die;

            Db::commit();
            return true;
        } catch (\Throwable $t){
            Db::rollback();
            return false;
        }

    }



}
