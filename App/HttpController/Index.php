<?php


namespace App\HttpController;

use App\Models\Admin;
use EasySwoole\Session\Session;

class Index extends Base
{
    //用户姓名
    protected $username;
    /**
     *首页
     */
    public function index()
    {
        $static_url = $this->getStaticUrl();
        $admin = new Admin();
        $user = $admin->getUser(Session::getInstance()->get('username'));
        if(!$user){
          $this->writeJson(500,'','用户不存在');
        }

        $data = [
            'static_url'=>$static_url,
            'username'=>$user['username'],

        ];
        $this->fetch('index/index',$data);
    }

    /**
     * 后台主面板页
     */
    public function welcome(){
        $static_url = $this->getStaticUrl();
        $admin = new Admin();
        $user = $admin->getUser(Session::getInstance()->get('username'));
        if(!$user){
            $this->writeJson(500,'','用户不存在');
        }

        $data = [
            'static_url'=>$static_url,
            'username'=>$user['username'],

        ];
        $this->fetch('index/welcome',$data);
    }


    protected function actionNotFound(?string $action)
    {
        $this->response()->withStatus(404);
        $file = EASYSWOOLE_ROOT.'/vendor/easyswoole/easyswoole/src/Resource/Http/404.html';
        if(!is_file($file)){
            $file = EASYSWOOLE_ROOT.'/src/Resource/Http/404.html';
        }
        $this->response()->write(file_get_contents($file));
    }



}