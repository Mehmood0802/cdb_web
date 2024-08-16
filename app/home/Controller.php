<?php
declare(strict_types = 1);

namespace app\home ;

use app\BaseController ;

use think\facade\Session ;

use app\home\model\Site ;


//后台基类
Class Controller extends BaseController{


	protected $middleware = [];


	//站点
	protected $site ;

	//用户数据
	protected $member ;

	//路由
	protected $uri ;

	//是否post请求
	protected $isPost = false ;

	//post数据
	protected $postDate ;
	
	//构造继承
	public function initialize(){
		parent::initialize();

		//数据初始化
		$this->initData() ;

	}

	//获取uri
	private function initData(){




		$this->site = Site::getOne(1) ;



		$this->uri = strtolower($this->request->controller()).'/'.$this->request->action() ;
		$this->isPost = strtolower($this->request->method()) == 'post' ? true : false ;
		$this->postData = $this->request->post(empty($key) ? '' : "{$key}/a");

		//status数据处理
		if(array_key_exists('status',$this->postData)){
			if($this->postData['status'] == 'on'){
				$this->postData['status'] = 1 ;
			}
			if($this->postData['status'] == ''){
				$this->postData['status'] = 0 ;
			}
		}
		//删除上传文件
		unset($this->postData['file']);
	}
}