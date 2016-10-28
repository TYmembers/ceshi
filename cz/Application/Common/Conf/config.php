<?php
return array(
    //'配置项'=>'配置值'
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_NAME'               =>  'cz',   // 数据库名

    'MODULE_ALLOW_LIST'    =>    array('Home','Admin'),

    'DEFAULT_MODULE'        =>  'Admin',     //默认访问后台
    'DEFAULT_CONTROLLER'    =>  'Login',     //默认控制器
    'DEFAULT_ACTION'        =>  'index',     //默认方法

    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '',          // 密码

    'LOG_RECORD'            =>  true,   // 默认不记录日志
    'LOG_TYPE'              =>  'File', // 日志记录类型 默认为文件方式
    'LOG_LEVEL'             =>  "EMERG,ALERT,CRIT,ERR,WARN,NOTICE",//允许记录的日志级别
    'LOG_EXCEPTION_RECORD'  =>  true, // 是否记录异常信息日志

    'URL_MODEL'             =>   2,
    'SHOW_PAGE_TRACE'=>True,

    //运营商
    'OPERATOR_TYPE' => array(
        1 => '中国移动',
        2 => '中国联通',
        3 => '中国电信',
    ),

    //订单状态
    'ORDER_TYPE' => array(
        1 => '未充值',
        2 => '充值成功',
        3 => '充值失败',
    ),

    //充值类型
    'MODE_TYPE' => array(
        1 => '话费',
        2 => '流量',
    ),

    //支付类型
    'PAY_TYPE' => array(
        1 => '支付宝',
        2 => '微信',
    ),

    //到期类型
    'TIME_TYPE' => array(
        1 => '24小时内',
        2 => '48小时内',
    ),

    //是否导出
    'EXPORT_TYPE' => array(
        1 => '未导出',
        2 => '已导出',
    ),

    //充值列表-话费
    'PRICE_TYPE' => array(
        1 => '30.00',
        2 => '50.00',
    ),

    //充值列表-流量
    'NUM_TYPE' => array(
        1 => '10M',
        2 => '20M',
    ),
);
