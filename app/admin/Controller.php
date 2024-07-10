<?php
declare(strict_types = 1);

namespace app\admin ;

use app\BaseController ;

use think\facade\Session ;

use app\admin\model\Admin ;


//后台基类
Class Controller extends BaseController{


	protected $middleware = ['app\admin\middleware\Auth','app\admin\middleware\Role'];

	//后台用户数据
	protected $admin ;

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


		$this->uri = strtolower($this->request->controller()).'/'.$this->request->action() ;
		$this->admin = Admin::getOne(Session::get('admin_id')) ;
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

		//是否推荐
		if(array_key_exists('is_recommend',$this->postData)){
			$this->postData['is_recommend'] = $this->postData['is_recommend'] == 'on' ? 1 : 0 ;
		}

		//是否默认
		if(array_key_exists('is_default',$this->postData)){
			$this->postData['is_default'] = $this->postData['is_default'] == 'on' ? 1 : 0 ;
		}


	}




}