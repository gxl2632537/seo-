<?php


namespace App\HttpController;


use App\Models\PermissionCreategory;

class Admin extends Base
{
    /**
     * 个人信息
     */
   public function admin_edit(){
       $static_url = $this->getStaticUrl();
       $data = [
           'static_url'=>$static_url,
       ];
       $this->fetch('admin/admin-edit',$data);
   }

    /**
     * 管理员列表
     */
   public function admin_list(){
       $static_url = $this->getStaticUrl();
       $data = [
           'static_url'=>$static_url,
       ];
       $this->fetch('admin/admin-list',$data);
   }

   /**
    * 角色管理
    */
   public function admin_role(){
       $static_url = $this->getStaticUrl();
       $data = [
           'static_url'=>$static_url,
       ];
       $this->fetch('admin/admin-role',$data);
   }

   /**
    * 权限分类
    */
   public function admin_cate($page=1){
       $page = $this->request()->getRequestParam('page');
       if(!$page){
           $page =1;
       }
       $static_url = $this->getStaticUrl();
       $pg_all = json_decode($this->permission_creategory_select($page),true);
       $data = [
           'static_url'=>$static_url,
           'pg_all'=>$pg_all,
           'page'=>intval($page)
       ];
       $this->fetch('admin/admin-cate',$data);
   }

   /**
    * 权限管理
    */

   public function admin_rule(){
       $static_url = $this->getStaticUrl();
       $data = [
           'static_url'=>$static_url,
       ];
       $this->fetch('admin/admin-rule',$data);
   }

   /**
    * 权限分类添加
    */
   public function permission_creategory_add(){
      $method = $this->request()->getMethod();
      if($method == 'POST'){
          $data =$this->request()->getRequestParam('data');
          if(!$data){
                  $this->writeJson(500,'','参数错误');
          }
          $params = json_decode($data,true);
          try{
              go(function () use($params){
                  $pc = new PermissionCreategory();
                  $pc->pg_save($params['title']);
              });
              $this->writeJson(200,'','添加成功！');
          }catch (\Exception $e){
              $this->writeJson(500,$e->getMessage(),'参数错误');
          }


      }else{
          $this->writeJson(500,'','参数错误');
      }
   }

    /**
     * 权限分类查询  Channel 协程通信，可以将协程里的值返回出来到管道中
     */
   public function permission_creategory_select($page){
       $chan = new \Swoole\Coroutine\Channel(1);
       go(function () use($chan,$page){
           $pc = new PermissionCreategory();
           //查询所有的权限分类
           $result = $pc->pg_select($page);
           $result= json_encode($result);
           //向通道写入数据
           $chan->push($result);
       });
       return  $chan->pop();
   }

    /**
     * 权限分类更改
     */
   public function permission_creategory_edit(){
       $method = $this->request()->getMethod();
       if($method == 'GET'){
           $id = intval($this->request()->getRequestParam('id'));
           if($id){
               $pc = new PermissionCreategory();
               $res = $pc->pg_get_one($id);
           }else{
               $this->writeJson(500,'','参数错误');
           }
           $static_url = $this->getStaticUrl();
           $data = [
               'static_url'=>$static_url,
               'res'=>$res,
               'id'=>$id
           ];
           $this->fetch('admin/pg-edit',$data);
       }
       if($method == 'POST'){
           $data_arr = $this->request()->getRequestParam('data');

           $datas = json_decode($data_arr,true);
           if(!$datas){
               $this->writeJson(500,'','参数错误');
           }
          $pc = new PermissionCreategory();
          $result = $pc->pg_update_title($datas);
          if(!$result){
              $this->writeJson(500,'','更新失败');
          }
           $this->writeJson(200,'','更新成功！');
       }
   }

    /**
     * 权限分类删除
     */
   public function permission_creategory_del(){
        $id =intval($this->request()->getRequestParam('id'));
        if(!$id){
            $this->writeJson(500,'','参数错误');
        }
        $pc = new PermissionCreategory();
        $result = $pc->pg_del_id($id);
        if(!$result){
               $this->writeJson(500,'','删除失败');
        }
        $this->writeJson(200,'','删除成功！');
   }
    /**
     * 权限分类批量删除
     */

   public function permission_creategory_delAll(){
        $method = $this->request()->getMethod();
        if($method == 'POST'){
            $ids = $this->request()->getRequestParam('ids');
            $pc = new PermissionCreategory();
            $result = $pc->pg_del_id($ids);
            if(!$result){
                $this->writeJson(500,'','删除失败');
            }
            $this->writeJson(200,'','删除成功！');
        }
   }
}