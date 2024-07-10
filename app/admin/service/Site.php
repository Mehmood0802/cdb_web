<?php
declare (strict_types = 1);

namespace app\admin\service ;

use think\facade\Cache;
use think\Config ;

use app\admin\model\SiteCate ;


class Site{

	//文章分类只开放2级
	
	//菜单上级(一级可选)
	public static function cateTree($id=null){
		$list = SiteCate::getAll(['pid'=>0]) ;
		$html = '<option value="0">请选择</option>' ;
		foreach($list as $k => $v){
			if($id == $v['id']){
				$html .= '<option selected value="'.$v['id'].'">'.$v['title'].'</option>';
			}else{
				$html .= '<option value="'.$v['id'].'">'.$v['title'].'</option>';
			}
		}
		return $html ;
	}


	//文章菜单(二级可选)
	public static function cateTree2($id=null){
		$list = SiteCate::getAll(['pid'=>0]) ;
		$html = '<option value="0">请选择</option>' ;
		foreach($list as $k => $v){
			$html .= '<option value="'.$v['id'].'" disabled >'.$v['title'].'</option>';
			$children = SiteCate::getAll(['pid'=>$v['id']]) ;
			foreach($children as $kk => $vv){
				if($id == $vv['id']){
					$html .= '<option selected value="'.$vv['id'].'">&nbsp;&nbsp;&nbsp;<sup style="color:#999">L</sup> '.$vv['title'].'</option>';
				}else{
					$html .= '<option value="'.$vv['id'].'">&nbsp;&nbsp;&nbsp;<sup style="color:#999">L</sup> '.$vv['title'].'</option>';
				}
			}

		}
		return $html ;
	}



	//菜单上级(一级可选)
	public static function cateTree3($id=null){
		$list = SiteCate::getAll(['pid'=>0]) ;
		$html = '<option value="0">全部</option>' ;
		foreach($list as $k => $v){
			if($id == $v['id']){
				$html .= '<option selected value="'.$v['id'].'">'.$v['title'].'</option>';
			}else{
				$html .= '<option value="'.$v['id'].'">'.$v['title'].'</option>';
			}
		}
		return $html ;
	}



}