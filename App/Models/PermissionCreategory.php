<?php


namespace App\Models;


use EasySwoole\ORM\AbstractModel;
use Swoole\Coroutine\Channel;
use Swoole\IDEHelper\StubGenerators\Swoole;


class PermissionCreategory extends AbstractModel
{
    protected $tableName = 'permission_creategory';

    /**
     * 插入一条权限分类
     */
    public function pg_save($title){
        self::create()->data([
            'title' => $title,
            'createtime'  => time(),
        ], false)->save();
    }

    /**
     * 查询所有的权限分类
     */

    public function pg_select($page){
        try {
            $limit = 5;        // 每页多少条数据
            $model = self::create()->limit($limit * ($page - 1), $limit)->withTotalCount();
            // 列表数据 倒序（最新更新的在前面）
            $list = $model->order('id','desc')->all();

            $result = $model->lastQueryResult();
            // 总条数
            $total = $result->getTotalCount();

            return ['list' => $list, 'total' => $total];
        }catch (\Exception $e){

        }
    }

    /**
     * 查找指定id 的权限分类数据
     * @param $id
     */
    public function pg_get_one($id){
        try{
            $res = self::create()->get($id)->toArray();
            return ['data'=>$res];
        }catch (\Exception $e){

        }
    }

    /**
     * 更新权限分类名
     * @param $data
     */
    public function pg_update_title($data){
        try{
           $obj = self::create()->get($data['id']);
           $obj->update([
               'title'=>$data['title'],
               'updatetime'=>time()
           ]) ;
           return $obj->lastQueryResult()->getAffectedRows();
        }catch (\Exception $e){

        }
    }

    /**
     * 删除单个权限分类名称
     */
    public function pg_del_id($id){
        try{
           return self::create()->destroy($id);
        }catch (\Exception $e){

        }
    }

    /**
     * 批量删除权限分类名称
     */
    public function pg_del_ids($ids){
        try{
           return self::create()->destroy($ids);
        }catch (\Exception $e){

        }
    }

}