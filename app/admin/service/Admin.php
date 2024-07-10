<?php
declare (strict_types = 1);

namespace app\admin\service ;

use think\facade\Cache;
use think\Config ;

use app\admin\model\AdminCate ;
use app\admin\model\AdminRole ;
use app\admin\model\AdminPower ;

use think\facade\Db ;

class Admin{


	//菜单icon
	public static function emnuIcon($tag=null){
		$list = Config('set.icon') ;
		$html = '<option value="0">请选择</option>' ;
		foreach($list as $k => $v){
			if($tag == $v['tag']){
				$html .= '<option selected value="'.$v['tag'].'">'.$v['tag'].'</option>';
			}else{
				$html .= '<option value="'.$v['tag'].'">'.$v['tag'].'</option>';
			}
		}
		return $html ;
	}


	//菜单方法
	public static function emnuAction($actionStr=null){
		$list = Config('set.action') ;
		$html = '' ;
		if(empty($actionStr)){
			foreach ($list as $k => $v) {
	            $html .= '<input type="checkbox" value="'.$v['id'].'" name="action[]" title="'.$v['title'].'" lay-skin="primary">' ;
	        }
		}else{
			$arr = explode(',', $actionStr ) ;
			foreach ($list as $k => $v) {
				$state = false ;
				foreach($arr as $kk => $vv){
					if($vv == $v['id'] ){
	            		$state = true ;	
					}
				}
				if($state){
					$html .= '<input type="checkbox" checked="" value="'.$v['id'].'" name="action[]" title="'.$v['title'].'" lay-skin="primary">' ;
				}else{
					$html .= '<input type="checkbox" value="'.$v['id'].'" name="action[]" title="'.$v['title'].'" lay-skin="primary">' ;
				}
	        }

	    }
		return $html ;
	}


	//菜单上级
	public static function cateTree($id=null){
		$list = AdminCate::getAll(['pid'=>0]) ;
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

	//菜单上级 2层
	public static function cateTree2($id=null){
		$list = AdminCate::getAll(['pid'=>0]) ;
		$html = '<option value="0">请选择</option>' ;
		foreach($list as $k => $v){
			$html .= '<option value="'.$v['id'].'" disabled >'.$v['title'].'</option>';
			$children = AdminCate::getAll(['pid'=>$v['id']]) ;
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


	//角色
	public static function roleTree($id=null){
		$list = AdminRole::getAll() ;
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



	//权限
	public static function powerTree($id_str=''){
		//菜单
        $list = AdminCate::getAll(['pid'=>0,'status'=>1]) ;
        $html = '' ;
        foreach($list as $k => $v){
         	$list[$k]['children'] = AdminCate::getAll(['pid'=>$v['id']]) ;
        }

        foreach($list as $k => $v){
         	$html .= '<div class="layui-form-item"><label class="layui-form-label">'.$v['title'].'</label>';
         	foreach($v['children'] as $kk => $vv){
         		$html .= '<div style="margin-bottom:20px"><div class="layui-input-block"><p style="line-height:38px;color:#999">'.$vv['title'].'</p></div>';
         		$html .= '<div class="layui-input-block">';
         		//获取权限中的方法
         		$actions = static::_getCatePower($vv['id'],$id_str);
         		foreach($actions as $kkk=>$vvv){
         			if($vvv['change']){
         				$html .= '<input type="checkbox" checked="true" class="m-cc" value="'.$vvv['id'].'" name="power_ids[]" title="'.$vvv['title'].'" lay-skin="primary">' ;
         			}else{
         				$html .= '<input type="checkbox" class="m-cc" value="'.$vvv['id'].'" name="power_ids[]" title="'.$vvv['title'].'" lay-skin="primary">' ;
         			}
         		}
         		$html .= '</div></div>' ;
         	}
         	$html .= '</div>' ;
        }

        return $html ;

	}



	//菜单(暂不带权限....)
	public static function emnuList($roleid=0){

		if($roleid){
			$role = AdminRole::getOne($roleid) ;

			//合并分类ID
			$sql = 'select * from `tp_admin_cate` where id in ('.$role['cate_ids'].') GROUP BY pid';

			$arr = Db::query($sql);
			$list = [] ;
			foreach($arr as $k=>$v){
				$list[] = Db::name('admin_cate') -> where('id','=', $v['pid']) -> find() ;
			}


			$list2 = Db::name('admin_cate') -> where('id','in', $role['cate_ids'] ) -> select() -> toArray() ;

			foreach($list2 as $k=>$v){
				foreach($list as $kk => $vv){
					if($vv['id'] == $v['pid'] ){
						$list[$kk]['children'][] = $v ;
					}
				}
			}

		}else{
			$list = AdminCate::getAll(['pid'=>0,'status'=>1]) ;

			foreach($list as $k=>$v){
				$list[$k]['children'] = AdminCate::getAll(['pid'=>$v['id'],'status'=>1]) ;
			}
		}

		return $list ;
	}


	//权限 是否选择
	protected static function _getCatePower($id,$str=null){
		$list = AdminPower::getAll(['cate_id'=>$id]) ;

			$arr = explode(',', $str) ;
			foreach($list as $k => $v){
				if(in_array($v['id'], $arr)){
					$list[$k]['change'] = true ;					
				}else{
					$list[$k]['change'] = false ;
				}
			}
		return $list ;
	}


}