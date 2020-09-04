<?php
namespace EasySwoole\EasySwoole;


use App\Utility\Template;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Message\Status;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Template\Render;
use EasySwoole\Session\Session;
use EasySwoole\Session\SessionFileHandler;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;
class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
       // 注册mysql数据库连接池
        $config = new \EasySwoole\ORM\Db\Config(Config::getInstance()->getConf('MYSQL'));
        $config->setMaxObjectNum(20);//配置连接池最大数量
        DbManager::getInstance()->addConnection(new Connection($config));


    }

    public static function mainServerCreate(EventRegister $register)
    {
        // TODO: Implement mainServerCreate() method.
        // 热重启
        $hotReloadOptions = new \EasySwoole\HotReload\HotReloadOptions;
        $hotReload = new \EasySwoole\HotReload\HotReload($hotReloadOptions);
        $hotReloadOptions->setMonitorFolder([EASYSWOOLE_ROOT . '/App']);
        $server = ServerManager::getInstance()->getSwooleServer();
        $hotReload->attachToServer($server);
        //think模板在全局的主服务中创建事件中，实例化该Render,并注入你的驱动配置
        Render::getInstance()->getConfig()->setRender(new Template());
        Render::getInstance()->attachServer(ServerManager::getInstance()->getSwooleServer());

        //可以自己实现一个标准的session handler
        $handler = new SessionFileHandler(EASYSWOOLE_TEMP_DIR);
        //表示cookie name   还有save path
        Session::getInstance($handler,'easy_session','session_dir');



    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        $cookie = $request->getCookieParams('easy_session');
        if(empty($cookie)){
            $sid = Session::getInstance()->sessionId();
            $response->setCookie('easy_session',$sid);
        }else{
            Session::getInstance()->sessionId($cookie);
        }

        // TODO: Implement onRequest() method.
//        $response->withHeader('Access-Control-Allow-Origin', '*');
//        $response->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
//        $response->withHeader('Access-Control-Allow-Credentials', 'true');
//        $response->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
//        if ($request->getMethod() === 'OPTIONS') {
//            $response->withStatus(Status::CODE_OK);
//            return false;
//        }
        return true;

    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}