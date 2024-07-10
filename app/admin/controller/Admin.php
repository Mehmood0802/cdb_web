<?php 
declare(strict_types = 1);

namespace app\admin\controller ;

use app\admin\Controller ;
use think\facade\View ;

use app\admin\model\Admin as AdminModel ;
use app\admin\model\AdminCate ;
use app\admin\model\AdminRole ;
use app\admin\model\AdminPower ;
use app\admin\model\AdminParameter ;

use app\admin\service\Admin as AdminService ;


use phpspirit\databackup\BackupFactory;   //数据备份
use phpspirit\databackup\RecoveryFactory;   


Class Admin extends Controller{


	//修改密码
	public function password(){
		if($this->isPost){
			return AdminModel::changePassword($this->postData);
		}
		return view();
	}


	/**
	 * 账号管理
	 */
	public function account(){

		$where = [] ;
		if(input('key')){
			$where[] = ['title', 'like' , '%'.input('key').'%'] ;
		}
		View::assign('key', input('key') );

		View::assign('list', AdminModel::getListPO($where) ) ;
		View::assign('page', input('page') );
		View::assign('uri', $this->uri );
		return view();
	}

	//新增
	public function accountAdd(){
		if($this->isPost){
			return AdminModel::add( $this->postData ) ;
		}
		View::assign('roleTree', AdminService::roleTree());
		View::assign('uri', $this->uri );
		return view();
	}

	//编辑
	public function accountEdit(){
		if($this->isPost){
			return AdminModel::edit( $this->postData ) ;
		}
		$data = AdminModel::getOne(input('id'));
		View::assign('data', $data);
		View::assign('roleTree', AdminService::roleTree($data['role_id']));
		View::assign('uri', $this->uri );
		return view();
	}

	//删除
	public function accountDel(){
		if($this->isPost){
			return AdminModel::del( $this->postData ) ;
		}
	}

	//状态
	public function accountStatus(){
		if($this->isPost){
			$re = $this->postData ;
			return AdminModel::status( $re['id'] ) ;
		}
	}






	/**
	 * 角色管理 
	 */
	public function role(){

		$where = [] ;
		if(input('key')){
			$where[] = ['title', 'like' , '%'.input('key').'%'] ;
		}
		View::assign('key', input('key') );

		View::assign('list', AdminRole::getlistPO($where) ) ;
		View::assign('page', input('page') );
		View::assign('uri', $this->uri );
		return view();
	}

	//新增
	public function roleAdd(){
		if($this->isPost){
			return AdminRole::add( $this->postData ) ;
		}
		View::assign('powerTree', AdminService::powerTree());
		View::assign('uri', $this->uri );
		return view();
	}

	//编辑
	public function roleEdit(){
		if($this->isPost){
			return AdminRole::edit( $this->postData ) ;
		}
		$data = AdminRole::getOne(input('id'));
		View::assign('data', $data);
		View::assign('powerTree', AdminService::powerTree($data['power_ids'],$data['cate_ids']));
		View::assign('uri', $this->uri );
		return view();
	}

	//删除
	public function roleDel(){
		if($this->isPost){
			return AdminRole::del( $this->postData ) ;
		}
	}

	//状态
	public function roleStatus(){
		if($this->isPost){
			$re = $this->postData ;
			return AdminRole::status( $re['id'] ) ;
		}
	}




	/**
	 * 菜单管理 
	 */
	public function cate(){

		$where = [] ;
		if(input('key')){
			$where[] = ['title', 'like' , '%'.input('key').'%'] ;
		}
		View::assign('key', input('key') );

		View::assign('list', AdminCate::list($where) ) ;
		View::assign('page', input('page') );
		View::assign('uri', $this->uri );
		return view();
	}

	//新增
	public function cateAdd(){
		if($this->isPost){
			return AdminCate::add( $this->postData ) ;
		}
		View::assign('uri', $this->uri );
		View::assign('cateTree', AdminService::cateTree());
		View::assign('iconTree', AdminService::emnuIcon());
		View::assign('actionTree', AdminService::emnuAction());
		return view();
	}

	//编辑
	public function cateEdit(){
		if($this->isPost){
			return AdminCate::edit( $this->postData ) ;
		}
		$data = AdminCate::getOne(input('id'));
		View::assign('data', $data);
		View::assign('uri', $this->uri );
		View::assign('cateTree', AdminService::cateTree($data['pid']));
		View::assign('iconTree', AdminService::emnuIcon($data['icon']));
		View::assign('actionTree', AdminService::emnuAction($data['action']));
		return view();
	}

	//删除
	public function cateDel(){
		if($this->isPost){
			$arr = $this->postData ;
			if(gettype($arr['id']) == 'string'){
				$params[] = $arr['id'] ;
			}else{
				$params = $arr['id'] ;
			}
			return AdminCate::del($params) ;
		}
	}

	//状态
	public function cateStatus(){
		if($this->isPost){
			return AdminCate::status(input('id')) ;
		}
	}






	/**
	 * 权限管理 
	 */
	public function power(){

		$where = [] ;
		if(input('key')){
			$where[] = ['title', 'like' , '%'.input('key').'%'] ;
		}
		View::assign('key', input('key') );

		View::assign('list', AdminPower::getListPO($where) ) ;
		View::assign('page', input('page') );
		View::assign('uri', $this->uri );
		return view();
	}

	//新增
	public function powerAdd(){
		if($this->isPost){
			return AdminPower::add( $this->postData ) ;
		}
		View::assign('uri', $this->uri );
		View::assign('cateTree', AdminService::cateTree2());
		return view();
	}

	//编辑
	public function powerEdit(){
		if($this->isPost){
			return AdminPower::edit( $this->postData ) ;
		}
		$data = AdminPower::getOne(input('id')) ;
		View::assign('data', $data );
		View::assign('uri', $this->uri );
		View::assign('cateTree', AdminService::cateTree2($data['cate_id']));
		return view();
	}

	//删除
	public function powerDel(){
		if($this->isPost){
			$arr = $this->postData ;
			if(gettype($arr['id']) == 'string'){
				$params[] = $arr['id'] ;
			}else{
				$params = $arr['id'] ;
			}
			return AdminPower::del($params) ;
		}
	}

	//状态
	public function powerStatus(){
		if($this->isPost){
			return AdminPower::status(input('id')) ;
		}
	}



	/**
	 * 参数 
	 */
	public function parameter(){

		$where = [] ;
		if(input('key')){
			$where[] = ['title', 'like' , '%'.input('key').'%'] ;
		}
		View::assign('key', input('key') );

		View::assign('list', AdminParameter::getListPO($where) ) ;
		View::assign('page', input('page') );
		View::assign('uri', $this->uri );
		return view();
	}

	//新增
	public function parameterAdd(){
		if($this->isPost){
			return AdminParameter::add( $this->postData ) ;
		}
		View::assign('uri', $this->uri );
		return view();
	}

	//编辑
	public function parameterEdit(){
		if($this->isPost){
			return AdminParameter::edit( $this->postData ) ;
		}
		View::assign('data', AdminParameter::getOne(input('id')));
		View::assign('uri', $this->uri );
		return view();
	}

	//删除
	public function parameterDel(){
		if($this->isPost){
			$arr = $this->postData ;
			if(gettype($arr['id']) == 'string'){
				$params[] = $arr['id'] ;
			}else{
				$params = $arr['id'] ;
			}
			return AdminParameter::del($params) ;
		}
	}

	//状态
	public function parameterStatus(){
		if($this->isPost){
			return AdminParameter::status(input('id')) ;
		}
	}


	//数据备份
	public function back(){

		//数据备份目录
		$dir = $_SERVER['DOCUMENT_ROOT'].'/backup' ;

		
		$list = array();
		$data = scandir($dir);
		foreach ($data as $k => $value){
		    if($value != '.' && $value != '..'){
		      $list[$k]['id'] = $k;
		      $list[$k]['title'] = $value;
		    }
		}

		View::assign('list', $list);
		View::assign('uri', $this->uri );
		View::assign('page', 1 );
		return view();
	}

	//备份
	public function back_up(){

		// $dir = dirname(__FILE__) ;
		$dir = $_SERVER['DOCUMENT_ROOT'];

		//自行判断文件夹
		$backupdir = '';
		if (isset($_POST['backdir']) && $_POST['backdir'] != '') {
		    $backupdir = $_POST['backdir'];
		} else {
		    $backupdir = $dir . DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR . date('Ymdhis');
		}
		if (!is_dir($backupdir)) {
		    mkdir($backupdir, 0777, true);
		}

		//获取配置
		$hostname = env('database.hostname');
		$database = env('database.database');
		$username = env('database.username');
		$password = env('database.password');


		$backup = BackupFactory::instance('mysql', $hostname.':3306', $database , $username , $password );
		$result = $backup->setbackdir($backupdir)
		    ->setvolsize(0.2)
		     // ->setonlystructure(true) //设置是否只备份数据结构
		    // ->setstructuretable(['md_api_group']) //设置哪些表只备份结构不备份数据 。设置了setonlystructure，这行不起作用
		    ->ajaxbackup($_POST);

		return trueMsg('ok',$result);


	}

	//恢复
	public function recovey(){

		// $dir = dirname(__FILE__) ;
		$dir = $_SERVER['DOCUMENT_ROOT'];

		//获取配置
		$hostname = env('database.hostname');
		$database = env('database.database');
		$username = env('database.username');
		$password = env('database.password');

		//要恢复的文件夹名
		$id = input('id') ;
		unset($_POST['id']);

		$recovery = RecoveryFactory::instance('mysql', $hostname.':3306', $database , $username , $password );
		$result = $recovery->setSqlfiledir( $dir . DIRECTORY_SEPARATOR . 'backup'.DIRECTORY_SEPARATOR.$id)
        ->ajaxrecovery($_POST);
        $result['id'] = $id ;
		return trueMsg('ok',$result);

	}

	//下载zip
	public function database_zip(){

		$card = input('id') ;
		if(!$card){
			return errMsg('备份id不存在！');
		}


		$file_template = $_SERVER['DOCUMENT_ROOT'].'/backup/cand_database.zip';
		//在此之前你的项目目录中必须新建一个空的zip包（必须存在）

		$downname = $card.'.zip'; 
		//你即将打包的zip文件名称
		$file_name = $_SERVER['DOCUMENT_ROOT'].'/backup/'.$downname ; 
		//把你打包后zip所存放的目录
		$result = copy( $file_template, $file_name );
		//把原来项目目录存在的zip复制一份新的到另外一个目录并重命名（可以在原来的目录）
		$zip = new \ZipArchive();

		//新建一个对象
		if ($zip->open($file_name, ZipArchive::CREATE) === TRUE) { 
			//打开你复制过后空的zip包
			$zip->addEmptyDir($card);
			//在zip压缩包中建一个空文件夹，成功时返回 TRUE， 或者在失败时返回 FALSE
			//下面是我的场景业务处理，可根据自己的场景需要去处理（我的是将所有的图片打包
			// $i = 1;
			// foreach ($cand_photo as $key3 => $value3) {
			// $file_ext = explode('.',$value3['cand_face']);//获取到图片的后缀名
			// $zip->addFromString($card.'/'.$card.'_'.$i.'.'.$file_ext[3] , file_get_contents($value3['cand_face']));//（图片的重命名，获取到图片的二进制流）
			// 	$i++;
			// }
			$zip->close();


			// $fp=fopen($file_name,"r"); 
			// $file_size=filesize($file_name);//获取文件的字节
			//下载文件需要用到的头 
			// Header("Content-type: application/octet-stream"); 
			// Header("Accept-Ranges: bytes"); 
			// Header("Accept-Length:".$file_size);
			// Header("Content-Disposition: attachment; filename=$downname"); 
			// $buffer=1024; //设置一次读取的字节数，每读取一次，就输出数据（即返回给浏览器） 
			// $file_count=0; //读取的总字节数 
			//向浏览器返回数据 如果下载完成就停止输出，如果未下载完成就一直在输出。根据文件的字节大小判断是否下载完成
			// while(!feof($fp) && $file_count<$file_size){
			// 	$file_con=fread($fp,$buffer);
			// 	$file_count+=$buffer;
			// 	echo $file_con;
			// }
			// fclose($fp);
			//下载完成后删除压缩包，临时文件夹
			// if($file_count >= $file_size) {
			// 	unlink($file_name);
			// }
		}

	}


}








