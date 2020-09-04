<?php


namespace App\HttpController;

use App\Models\Admin;
use EasySwoole\Component\Di;
use EasySwoole\VerifyCode\Conf;
use EasySwoole\Validate\Validate;
use EasySwoole\Session\Session;
/**
 * 
 * Class Login
 * @package App\HttpController
 */
class Login extends Base
{
    public function login()
    {
        $result_data = $this->request()->getRequestParam('data');
        if($result_data){
            $datas = json_decode($result_data,true);
            $valitor = new Validate();
            $valitor->addColumn('username', '名字不为空')->required('名字不为空');
            $valitor->addColumn('password')->alphaNum('密码只能是大小写字母和数字的组合/(ㄒoㄒ)/~~')->betweenLen(6, 18, '密码长度需要6-18位/(ㄒoㄒ)/~~');
            $bool = $valitor->validate($datas);
               if($bool){
                    //验证验证码
                 $code_contents = Session::getInstance()->get('code');
                   if(strtolower($datas['captcha']) == strtolower($code_contents)){
                       //验证密码
                    $adminModel = new Admin();
                    $result = $adminModel->checkPassword($datas['username'],$datas['password']);
                    if($result){
                        Session::getInstance()->set('username',$datas['username']);
                        return  $this->writeJson(200,'','登陆成功！');

                    }else{
                        return  $this->writeJson(500,'','参数错误登陆失败！');
                    }
                   }else{
                       return  $this->writeJson(500,'','验证码不正确！');
                   }
               }else{
                   return  $this->writeJson(500,'',$valitor->getError()->__toString());
               }
        }


        $static_url = $this->getStaticUrl();
        $data = [
            'static_url'=>$static_url,
            'code_base64'=>$this->getBase64()
        ];
        $reloadCode = $this->request()->getRequestParam('reloadCode');
        //刷新验证码
        if($reloadCode == 1){
            return   $this->writeJson(200,$this->getBase64());
        }
        return $this->fetch('',$data);
    }

    /**
     * 获取base64验证码
     * @return string
     */
   public function getBase64(){
        $config = new Conf();
        $code = new \EasySwoole\VerifyCode\VerifyCode($config);
        $img_code  =$code->DrawCode();
       Session::getInstance()->set('code',$img_code->getImageCode());
        return $img_code->getImageBase64();
    }

    /**
     * 退出登录
     */
    public function logout(){
        $user = Session::getInstance()->get('username');
        if(!$user){
            $this->writeJson(500,'','用户不存在');
        }
        Session::getInstance()->del('username');
        $this->response()->redirect('/login/login');
    }

    function des()
    {
        Session::getInstance()->destroy();;
    }
}