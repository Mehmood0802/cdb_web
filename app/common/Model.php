<?php
declare (strict_types = 1);

namespace app\common;

use think\Model as TPModel;
use think\model\concern\SoftDelete;
use think\Config ;

class Model extends TPModel
{

	protected $pk = 'id';   //主键
    // protected $name = 'user';    //表名

    protected $autoWriteTimestamp = true;   //自定写入日期 create_time update_time

    // protected $readonly = [];   //只读字段 

    use SoftDelete;
    protected $deleteTime = 'delete_time';   //软删除

    //自动类型转换  integer  float  boolean  array  object  serialize  json  timestamp
    // protected $type = [];

    protected $dateFormat = 'Y-m-d H:i:s';   //时间格式化 自定义

    //字段信息
    // protected $schema = [];


    /**
     * 模型基类初始化
     */
    public static function init()
    {
        parent::init();

    }


    /**
     * 增加 
     */
    protected static function addData($data){
        $m = new static ;
        try {
            $m->save($data);
            return $m->id;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 编辑 
     */
    protected static function editData($data){
        try {
            return (new static)->update($data);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *  删除
     */
    protected static function delData($arr,$type=0){
        try {
            if($type == 1){
                return (new static)->destroy($arr,true) ;    //真实删除
            }else{
                return (new static)->useSoftDelete('delete_time',time()) ->delete($arr) ;   //软删除
            }
        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * 单条
     */
    public static function getOne($id=null,$field="*"){
        try {
            $data = (new static)->where('id',$id)->field($field)->find();
            return $data ? $data->toArray() : '' ;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 单条 有条件
     */
    public static function getOneW($where=[],$field="*"){
        try {
            $data = (new static)->where($where)->field($field)->order('id asc')->find();
            return $data ? $data->toArray() : '' ;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 列表分页
     */
    public static function getList($where=[],$field='*',$order="sort desc,id desc",$limit=0 ){
        try {
            if($limit > 0){
                $list = (new static)->field($field)->where($where)->order($order)->limit($limit)->select();
            }else{
                $list = (new static)->field($field)->where($where)->order($order)->select();
            }
            return $list ? $list->toArray() : [] ;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 列表分页
     */
    public static function getListP($where=[],$field='*',$order="sort desc,id desc",$limit=10,$page=1){

        try {
            $list = (new static)->field($field)->where($where)->order($order)->paginate([$limit, 'query' => request()->param()] );
            return $list ? $list->toArray() : [] ;
        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * 列表分页
     */
    public static function getListJ($where=[],$field='*',$order="sort desc,id desc",$limit=10,$page=1){

        try {
            $list = (new static)->field($field)->where($where)->order($order)->paginate($limit);
            if($list){
                $a = $list->toArray();
                $a['pageSize'] = $limit ;
                $a['current'] = $a['current_page'] ;
                unset($a['current_page']);
                unset($a['per_page']);
                unset($a['last_page']);
                $list = $a ;
            }

            return $list ;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 列表分页
     */
    public static function getListPO($where=[],$field='*',$order="sort desc,id desc",$limit=10,$page=1){
        try {
            return (new static)->field($field)->where($where)->order($order)->paginate([$limit, 'query' => request()->param()] );
        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * 列表所有 
     */
    public static function getAll($where=[],$field='*',$order="sort desc,id desc"){
        try {
            $list = (new static)->field($field)->where($where)->order($order)->select();
            return $list ? $list->toArray() : [] ;
        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * 批量插入 
     */
    protected static function addAll($arr){
        $m = new static ;
        try {
            return $m->replace()->insertAll($arr);
        } catch (Exception $e) {
            return false;
        }
    }


    public static function total($where=[]){
        try {
            return $re = (new static)->where($where)->count();
        } catch (Exception $e) {
            return false;
        }
    }






}