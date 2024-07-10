<?php 
declare(strict_types = 1);

namespace app\admin\controller ;

use app\admin\Controller ;
use think\facade\View ;

use app\admin\model\Site as SiteModel ;
use app\admin\model\SiteArticle ;
use app\admin\model\SiteCate ;
use app\admin\model\SiteOnepage ;
use app\admin\model\SiteFlash ;
use app\admin\model\SiteFeedback ;


use app\admin\service\Site as SiteService ;


Class Site extends Controller{


		/**
	 * 文章
	 */
	public function site(){

		$where = [] ;
		if(input('key')){
			$where[] = ['a.title', 'like' , '%'.input('key').'%'] ;
		}
		View::assign('key', input('key') );

		View::assign('list', SiteModel::getlistPO($where) ) ;
		View::assign('page', input('page') );
		View::assign('uri', $this->uri );
		return view();
	}

	//新增
	public function siteAdd(){
		if($this->isPost){
			return SiteModel::add( $this->postData ) ;
		}
		View::assign('uri', $this->uri );
		return view();
	}

	//编辑
	public function siteEdit(){
		if($this->isPost){
			return SiteModel::edit( $this->postData ) ;
		}
		$data = SiteModel::getOne(input('id'));
		View::assign('data', $data);
		View::assign('uri', $this->uri );
		return view();
	}

	//删除
	public function siteDel(){
		if($this->isPost){
			return SiteModel::del( $this->postData ) ;
		}
	}

	//状态
	public function siteStatus(){
		if($this->isPost){
			$re = $this->postData ;
			return SiteModel::status( $re['id'] ) ;
		}
	}




	/**
	 * 文章
	 */
	public function article(){

		$where = [] ;
		if(input('key')){
			$where[] = ['a.title', 'like' , '%'.input('key').'%'] ;
		}
		View::assign('key', input('key') );

		if(input('cate_id')){
			$where[] = ['a.cate_id', '=' , input('cate_id') ] ;
		}
		View::assign('tree', SiteService::cateTree3(input('cate_id')) ) ;


		View::assign('list', SiteArticle::getlistPOAsInfo($where) ) ;
		View::assign('page', input('page') );
		View::assign('uri', $this->uri );
		return view();
	}

	//新增
	public function articleAdd(){
		if($this->isPost){
			return SiteArticle::add( $this->postData ) ;
		}
		View::assign('cateTree', SiteService::cateTree());
		View::assign('uri', $this->uri );
		return view();
	}

	//编辑
	public function articleEdit(){
		if($this->isPost){
			return SiteArticle::edit( $this->postData ) ;
		}
		$data = SiteArticle::getOne(input('id'));
		View::assign('data', $data);
		View::assign('cateTree', SiteService::cateTree($data['cate_id']));
		View::assign('uri', $this->uri );
		return view();
	}

	//删除
	public function articleDel(){
		if($this->isPost){
			return SiteArticle::del( $this->postData ) ;
		}
	}

	//状态
	public function articleStatus(){
		if($this->isPost){
			$re = $this->postData ;
			return SiteArticle::status( $re['id'] ) ;
		}
	}




	/**
	 * 分类
	 */
	public function cate(){

		$where = [] ;
		if(input('key')){
			$where[] = ['title', 'like' , '%'.input('key').'%'] ;
		}
		View::assign('key', input('key') );

		View::assign('list', SiteCate::getAllPath($where) ) ;
		View::assign('page', input('page') );
		View::assign('uri', $this->uri );
		return view();
	}

	//新增
	public function cateAdd(){
		if($this->isPost){
			return SiteCate::add( $this->postData ) ;
		}
		// View::assign('cateTree', SiteService::cateTree());
		View::assign('uri', $this->uri );
		return view();
	}

	//编辑
	public function cateEdit(){
		if($this->isPost){
			return SiteCate::edit( $this->postData ) ;
		}
		$data = SiteCate::getOne(input('id'));
		View::assign('data', $data);
		// View::assign('cateTree', SiteService::cateTree($data['pid']));
		View::assign('uri', $this->uri );
		return view();
	}

	//删除
	public function cateDel(){
		if($this->isPost){
			return SiteCate::del( $this->postData ) ;
		}
	}

	//状态
	public function cateStatus(){
		if($this->isPost){
			$re = $this->postData ;
			return SiteCate::status( $re['id'] ) ;
		}
	}




	/**
	 * 主题页
	 */
	public function onepage(){

		$where = [] ;
		if(input('key')){
			$where[] = ['title', 'like' , '%'.input('key').'%'] ;
		}
		View::assign('key', input('key') );

		View::assign('list', SiteOnepage::getlistPO($where) ) ;
		View::assign('page', input('page') );
		View::assign('uri', $this->uri );
		return view();
	}

	//新增
	public function onepageAdd(){
		if($this->isPost){
			return SiteOnepage::add( $this->postData ) ;
		}
		View::assign('uri', $this->uri );
		return view();
	}

	//编辑
	public function onepageEdit(){
		if($this->isPost){
			return SiteOnepage::edit( $this->postData ) ;
		}
		$data = SiteOnepage::getOne(input('id'));
		View::assign('data', $data);
		View::assign('uri', $this->uri );
		return view();
	}

	//删除
	public function onepageDel(){
		if($this->isPost){
			$arr = $this->postData ;
			if(gettype($arr['id']) == 'string'){
				$params[] = $arr['id'] ;
			}else{
				$params = $arr['id'] ;
			}
			return SiteOnepage::del($params) ;
		}
	}

	//状态
	public function onepageStatus(){
		if($this->isPost){
			return SiteOnepage::status(input('id')) ;
		}
	}






	/**
	 * 轮播
	 */
	public function flash(){

		$where = [] ;
		if(input('key')){
			$where[] = ['title', 'like' , '%'.input('key').'%'] ;
		}
		View::assign('key', input('key') );

		View::assign('list', SiteFlash::getListPO($where) ) ;
		View::assign('page', input('page') );
		View::assign('uri', $this->uri );
		return view();
	}

	//新增
	public function flashAdd(){
		if($this->isPost){
			return SiteFlash::add( $this->postData ) ;
		}
		View::assign('uri', $this->uri );
		return view();
	}

	//编辑
	public function flashEdit(){
		if($this->isPost){
			return SiteFlash::edit( $this->postData ) ;
		}
		View::assign('data', SiteFlash::getOne(input('id')));
		View::assign('uri', $this->uri );
		return view();
	}

	//删除
	public function flashDel(){
		if($this->isPost){
			$arr = $this->postData ;
			if(gettype($arr['id']) == 'string'){
				$params[] = $arr['id'] ;
			}else{
				$params = $arr['id'] ;
			}
			return SiteFlash::del($params) ;
		}
	}

	//状态
	public function flashStatus(){
		if($this->isPost){
			return SiteFlash::status(input('id')) ;
		}
	}







	/**
	 * 反馈
	 */
	public function feedback(){

		$where = [] ;
		if(input('key')){
			$where[] = ['title', 'like' , '%'.input('key').'%'] ;
		}
		View::assign('key', input('key') );

		View::assign('list', SiteFeedback::getListPO($where) ) ;
		View::assign('page', input('page') );
		View::assign('uri', $this->uri );
		return view();
	}

	//新增
	public function feedbackAdd(){
		if($this->isPost){
			return SiteFeedback::add( $this->postData ) ;
		}
		View::assign('uri', $this->uri );
		return view();
	}

	//编辑
	public function feedbackEdit(){
		if($this->isPost){
			return SiteFeedback::edit( $this->postData ) ;
		}
		View::assign('data', SiteFeedback::getOne(input('id')));
		View::assign('uri', $this->uri );
		return view();
	}

	//删除
	public function feedbackDel(){
		if($this->isPost){
			$arr = $this->postData ;
			if(gettype($arr['id']) == 'string'){
				$params[] = $arr['id'] ;
			}else{
				$params = $arr['id'] ;
			}
			return SiteFeedback::del($params) ;
		}
	}

	//状态
	public function feedbackStatus(){
		if($this->isPost){
			return SiteFeedback::status(input('id')) ;
		}
	}






}






