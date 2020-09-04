<?php


namespace App\Models;
use EasySwoole\EasySwoole\Config;
use EasySwoole\ORM\AbstractModel;
use mysql_xdevapi\Exception;

class Admin extends AbstractModel
{
    protected $tableName = 'admin';

    /**
     * 检查密码是否正确
     */
    public function checkPassword($user,$passwd){
        try{
             $admin = $this->get(['username'=>$user])->toArray();
             if(!$admin){
                 return false;
             }
             $solt =Config::getInstance()->getConf('PASSWORLD_SOLT');
             if($admin['password'] != md5($passwd.$solt)){
                 return false;
             }else{
                 return true;
             }
        }catch (\Exception $e){

        }
    }

    /**
     * 获取登陆后的用户信息
     */
    public function getUser($user){
        try{
            $admin = $this->get(['username'=>$user])->toArray();
            return $admin;
        }catch (\Exception $e){

        }
    }


}