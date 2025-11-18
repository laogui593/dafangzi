<?php

// +----------------------------------------------------------------------
// | ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2019 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://demo.thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | gitee 代码仓库3：https://gitee.com/zoujingli/ThinkAdmin
// | github 代码仓库3：https://github.com/zoujingli/ThinkAdmin
// +----------------------------------------------------------------------

namespace think;

// ========== 添加 CORS 头 - 解决跨域问题 ==========
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Max-Age: 86400');

// 处理 OPTIONS 预检请求
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}
// ========== CORS 配置结束 ==========

$_GET['s'] = isset($_GET['s']) ? ltrim($_GET['s'], '/') : null;
// if (preg_match('/index\/index\/+(order|product)/is', $_GET['s'])) {
//     if (!preg_match('/cli/i', php_sapi_name())) {
//         http_response_code(500);
//         die;
//     }
// }
require __DIR__ . '/../thinkphp/base.php';
$container = Container::get('app');
$adminModule = 'admin';
$adminModuleFile = __DIR__ . '/../admin_module.php';
if (file_exists($adminModuleFile)) {
    $adminModule = include $adminModuleFile;
}
define('ADMIN_MODULE', $adminModule);
$preg = '/^' . ADMIN_MODULE . '/';
if (preg_match($preg, $_GET['s'])) {
    $_GET['s'] = preg_replace($preg, '', $_GET['s']);
    $container->bind('akszadmin');
}
$container->run()->send();