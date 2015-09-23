<?php
/**
 * FastRoute_Lite 扩展类库 - 快速路由
 *
 * - 基于FastRoute实现自定义路由
 * - FastRoute需要PHP 5.4.0 以上版本
 * - 支持路由匹配失败时，自定义处理器
 *
 * 示例：
 *
 * Step 1. Nginx下需要添加以下配置：
```
 *   if (-f $request_filename) {
 *       expires max;
 *       break;
 *   }
 *   if (!-e $request_filename) {
 *       rewrite ^/(.*)$ /index.php/$1 last;
 *   }
```
 *
 * Step 2. 添加路由配置到./Config/app.php
```
 *   'FastRoute' => array(
 *       'routes' => array(
 *           array('GET', '/user/get_base_info/{user_id:\d+}', 'User.GetBaseInfo'),
 *           array('GET', '/user/get_multi_base_info/{user_ids:[0-9,]+}', 'User.GetMultiBaseInfo'),
 *       ),
 *   ),
 *
``` 
 *
 * Step 3. index.php入口文件扩展调用
``` 
 * DI()->fastRoute = new FastRoute_Lite();
 * DI()->fastRoute->dispatche();
 *
```
 *
 * Step 4. 页面访问测试
```
 * http://library.phalapi.com/user/get_base_info/1
 * 等效于：http://library.phalapi.com/?service=User.GetBaseInfo&user_id=1
 *
 * http://library.phalapi.com/user/get_multi_base_info/1,2
 * 等效于：http://library.phalapi.com/?service=User.GetMultiBaseInfo&user_ids=1,2
```
 *
 *     
 * @link https://github.com/nikic/FastRoute
 * @author dogstar 20150907
 */

require_once dirname(__FILE__) . implode(DIRECTORY_SEPARATOR, array('', 'FastRoute', 'src', 'functions.php'));

class FastRoute_Lite {

    public function __construct() {
        PhalApi_Translator::addMessage(API_ROOT . DIRECTORY_SEPARATOR . 'Library' . DIRECTORY_SEPARATOR . 'FastRoute');

        spl_autoload_register(function($class) {
            if (strpos($class, 'FastRoute\\') === 0) {
                $name = substr($class, strlen('FastRoute'));
                require_once dirname(__FILE__) . '/FastRoute/src/' . strtr($name, '\\', DIRECTORY_SEPARATOR) . '.php';
            }
        });
    }

    /**
     * 自定义路由分发
     * 
     * - 会根据配置的路由规则，进行URI转换
     * 
     * @param FastRoute_Handler $errorHandler 对失败/异常的处理回调
     * @return null
     */   
    public function dispatche(FastRoute_Handler $errorHandler = null) {
        if ($errorHandler === null) {
            $errorHandler = new FastRoute_Handler_Error();
        }

        //装载配置的自定义路由规则，异常时直接抛出以提示开发同学
        $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
            foreach (DI()->config->get('app.FastRoute.routes') as $routeCfg) {
                $r->addRoute($routeCfg[0], $routeCfg[1], $routeCfg[2]);
            }
        });

        // Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                // ... 404 Not Found (兼容无路由)
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                $response = DI()->response;
                $response->setRet(405);
                $response->setMsg(T('FastRoute Method Not Allowed, It Should be: {methods}', 
                    array('methods' => implode('/', $routeInfo[1]))));

                // ... 405 Method Not Allowed (异常处理)
                $errorHandler->excute($response);
                break;
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                // ... call $handler with $vars (交由PhalApi处理响应)
                $vars['service'] = $handler;
                DI()->request = new PhalApi_Request($vars);
                break;
        }
    }
}
