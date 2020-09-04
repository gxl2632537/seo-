<?php
return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '192.168.1.179',
        'PORT' => 9501,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER,EASYSWOOLE_REDIS_SERVER
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 8,
            'reload_async' => true,
            'max_wait_time'=>3
        ],
        'TASK'=>[
            'workerNum'=>4,
            'maxRunningNum'=>128,
            'timeout'=>15
        ]
    ],
    'document_root' => EASYSWOOLE_ROOT.'/Static/',
    'enable_static_handler' => true,
    'TEMP_DIR' => null,
    'LOG_DIR' => null,
    'PASSWORLD_SOLT' =>'lsg_',
    'DEFAULT_STATIC_URL'=>'http://seo.lusanguo.cn/Static/',
    /*################ MYSQL CONFIG ##################*/
    'MYSQL' => [
        'host'          => '192.168.1.179',
        'port'          => '3306',
        'user'          => 'root',
        'timeout'       => '5',
        'charset'       => 'utf8mb4',
        'password'      => 'root',
        'database'      => 'seo',
        'POOL_MAX_NUM'  => '20',
        'POOL_TIME_OUT' => '0.1',
    ],
];
