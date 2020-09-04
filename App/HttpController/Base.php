<?php


namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Session\Session;
use EasySwoole\Template\Render;


/** 基础控制器
 *
 * Class Base
 * @package App\HttpController
 */
class Base extends Controller
{
    public function onRequest(?string $action): ?bool
    {

        //判断当前是否登陆 login/login除外
        $username = Session::getInstance()->get('username');
        if($username || $action =='login' ){
            return true;
        }else{
            $this->response()->redirect("http://seo.lusanguo.cn:9501/login/login");
            return false;
        }
    }


    /**
     * 重新定义仿tp5的模板
     * views下的目录和文件名要一致且保持小写
     */

    public function fetch($tpl = '',$data =[]){

       if($tpl == ''){
           $tpl = $this->getActionName() .'/'.$this->getActionName();
       }
      $this->response()->write(Render::getInstance()->render($tpl,$data));
    }

    /**
     * 获取到默认的静态文件的url
     * @return array|mixed|null
     */
    public function getStaticUrl(){
        $instance = \EasySwoole\EasySwoole\Config::getInstance();
        // 获取配置 按层级用点号分隔
        $default_static_url = $instance->getConf('DEFAULT_STATIC_URL');
        return $default_static_url;
    }

}