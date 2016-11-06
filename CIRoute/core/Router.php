<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 16-11-2
 * Time: 上午9:41
 */


class CI_Router {
	/**
	 * @purpose Router类:将URI映射到对应的控制器及方法
	 * Router类大量代码处理的是自定义路由,该类主要支撑以下几个功能点
	 * 1.自定义路由规则
	 * 2,支持回调函数
	 * 3,支持HTTP动词
	 */
	public $config;
	public $routes = array();
	public $class = '';
	public $method = 'index';
	public $directory ;
	public $default_controller;
	public $translate_uri_dashes = FALSE;
	public $enable_query_strings = FALSE;

	/**
	 * @purpose 构造函数
	 */
	public function __construct($routing=NULL)
	{
		//加载内部依赖类
		$this->config = DI()->config->get('App.CIRoute.config');
		$this->uri = new CI_URI();
		//确认是否开启querystring模式,如果这个模式开启那就用index.php?c=mall&a=list这样去访问控制器和方法了
		$this->enable_query_strings = (!is_cli() && true === $this->config{'enable_query_strings'});
		/**
		 * @purpose 如果在 index.php里指定控制器目录,那么动态路由之前都将这个设置作为控制器的目录
		 * 通俗点说是路由器在找控制器和方法时,会在"controller/"设置的目录/下找
		 * 而且这个设置会覆盖URI(三段)的目录
		 */
		//$routing = DI()->config->get('App.CIRoute.routes');
		is_array($routing) && isset($routing['directory']) && $this->set_directory($routing['directory']);
		//核心:解析URI到$this->directory,$this->class,$this->method
		$this->_set_routing();
		/**
		 * @purpose 如果在index.php中设置了控制器和方法,则覆盖
		 * 比如服务器维护时,设置了一个方法用来显示维护中的静态页面,就可以让任何URI的请求都进入到这个方法中显示静态页面
		 */
		if(is_array($routing))
		{
			empty($routing['controller'])  || $this->set_class($routing['controller']);
			empty($routing['function'])  || $this->set_class($routing['function']);
		}
	}

	/**
	 * @purpose 动态路由设置
	 */
	protected function _set_routing()
	{
		//加载路由配置文件  App.CIRoute.routes
		$route= DI()->config->get('App.CIRoute.config');
		/**
		 * @purpose 读取默认控制器配置 $route['default_controller']
		 * 读取$route['translate_uri_dashes'] .如果设置为TRUE,则可将URI中的破折号-转换成类名的下划线_
		 * 如 my-controller/index -> my_controller/index
		 * 读取所有自定义路由策略赋值给$this->routes
		 */
		if(isset($route) && is_array($route))
		{
			isset($route['default_controller']) && $this->default_controller = $route['default_controller'];
			isset($route['translate_uri_dashes']) && $this->default_controller = $route['translate_uri_dashes'];
			unset($route['default_controller'],$route['translate_uri_dashes']);
			$this->routes = $route;
		}
		//在querystring模式下获取 directory/class/method
		//index.php?d=admin&c=mall&m=list
		//$config['controller_trigger'] = 'c'; 控制器变量
		//$config['function_trigger'] = 'm'; 方法变量
		//$config['directory_trigger'] = 'd'; 目录变量
		if($this->enable_query_strings) {
			//获取$this->directory.配置文件中 'directory_trigger'代表$_GET中用什么变量名作为传递directory的键值
			//同样的还有设置控制器的传递参数键名controller_trigger,方法的传递参数键名function_trigger
			if(!isset($this->directory))
			{
				$_d = $this->config{'directory_trigger'};
				$_d = isset($_GET[$_d]) ? trim($_GET[$_d]," \t\n\r\0\x0B/") : '';
				if('' !== $_d)
				{
					//filter_uri是验证组成字符是否在白名单(配置文件中permitted_uri_chsrs设置)中
					$this->uri->filter_uri($_d);
					$this->set_directory($_d);
				}
			}
			/**
			 * @purpose 获取控制器和方法,并设置 $this->uri->rsegments
			 */
			$_c = trim($this->config{'controller_trigger'});
			if(!empty($_GET[$_c]))
			{
				$this->uri->filter_uri($_GET[$_c]);
				$this->set_class($_GET[$_c]);
				$_f = trim($this->config{'function_trigger'});
				if(!empty($_GET[$_f]))
				{
					$this->uri->filter_uri($_GET[$_f]);
					$this->set_method($_GET[$_f]);
				}
				$this->uri->rsegments = array(
					1 => $this->class,
					2 => $this->method
				);
			}
			else
			{
				//方法没有可以允许,调用默认控制器和方法
				$this->_set_default_controller();
			}
			return;
		}

		/**
		 * @purpose 非 querystring模式的程序
		 */
		if($this->uri->uri_string !== '')
		{
			//解析自定义路由规则,并调用_set_requests 函数 设置目录 ,控制器.方法
			$this->_parse_routes();
		}
		else
		{
			//uri_string 为空,一般情况下就是域名后面没有任何字符,调用默认控制器
			$this->_set_default_controller();
		}

	}


	/**
	 * @purpose 路由设置
	 */
	protected function _set_request($segments = array())
	{

		/**
		 * @purpose 看这里调用Router::_validate_request();而Router::_validate_request()的作用是检测寻找出一个
		 * 正确存在的路由,并确定它,确定后的值分别放到Rtouer::$class这些属性里面,所以使得_set_request()也有确定路由的功能
		 *
		 * 注:$segments = $this
		 *
		 */
		$segments = $this->_validate_request($segments);
		if(empty($segments)) {
			/**
			 * @purpose 如果上面返回了空数组,就会进到这里
			 */
			$this->_set_default_controller();
			return;
		}
		if($this->translate_uri_dashes === TRUE) {
			$segments[0] = str_replace('-','_',$segments[0]);
			if(isset($segments[1])){
				$segments[1] = str_replace('-','_',$segments[1]);
			}
		}
		//设置控制器类
		$this->set_class($segments[0]);
		if(isset($segments[1]))
		{
			//设置控制器方法
			$this->set_method($segments[1]);
		}
		else
		{
			//设置默认方法片段
			$segments[1] = 'index';
		}
		//这里要说一下，现在是在ROUTER里面为URI赋值，URI里面的这个URI::$rsegments是经过处理，并确定路由后，实质调用的路由的段信息。
		//而URI::$segments （前面少了个r），则是原来没处理前的那个，即直接由网址上面得出来的那个。
		//将整个数组元素往后推一格，保持和没有shift掉目录时的数组原素存放序列一致，
		//如array ( 0 => 'news', 1 => 'view', 2 => 'crm', )经过这两行后变成array ( 1 => 'news', 2 => 'view', 3 => 'crm', )
		//不过要是多级目录的话，这样推有什么用呢？
		array_unshift($segments,NULL);
		unset($segments[0]);
		$this->uri->rsegments = $segments;


	}

	/**
	 * @purpose _set_request 迭代由正则提取出动态路由
	 * 迭代器模式
	 */
	protected function _parse_routes()
	{
		//知道 _set_request()是干嘛的之后,下面的条理就比较清晰了.
		$uri = implode('/',$this->uri->segments);
		$http_verb = isset($_SERVER['REQUEST_METHOD'])? strtolower($_SERVER['REQUEST_METHOD']) :'cli';
		$routes = $route= DI()->config->get('App.CIRoute.routes');
		/**
		 * @purpose CI路由重定向功能实现
		 */
		//routes 为配置文件中的route
		foreach($routes as $key=>$val)
		{
			if(is_array($val))//匹配http动词的技术tip
			{
				$val = array_change_key_case($val,CASE_LOWER);
				if(isset($val[$http_verb]))
				{
					$val = $val[$http_verb];//$route['products']['put'] = 'product/insert';

				}
				else
				{
					continue;
				}
			}
				//将通配表达式
				/**
				 * @purpose 配置  $route['products/([a-z]+)/(\d+)'] = '$1/id_$2';
				 */
			$key = str_replace(array(':any',':num'),array('[^/]+', '[0-9]+'),$key);
			/**
			 * @purpose 路由匹配技术的实现
			 */
			if(preg_match('#^'.$key.'$#',$uri,$matches))
			{
				//利用回调过程反向引用
				if(!is_string($val) && is_callable($val))
				{
					//从匹配的数组中删除原始字符串
					array_shift($matches);
					//使用在匹配中的值执行回调函数作为参数
					$val = call_user_func_array($val,$matches);
				}
				elseif(strpos($val,'$') !==FALSE && strpos($key,'(') !== FALSE)
				{
					$val = preg_replace('#^'.$key.'$#',$val,$uri);
				}
				$this->_set_request(explode('/',$val));
				return;
			}
		}
		$this->_set_request(array_values($this->uri->segments));
	}
	protected function _set_default_controller()
	{
		//在Router::_set_routing()函数里面有一个操作，是从配置文件里面读取默认控制器名
		if(empty($this->default_controller))
		{
			exit('暂无默认控制器');
		}
		//如果有我们下面就把默认的控制器设置为当前要找的路由
		//这里只是分“有指定默认方法”和“没有指定”两种情况而已。不过要弄点下面那个$this->_set_request($x);
		//CI这几个函数也许写得很妙，但是让人看得纠结。
		if (sscanf($this->default_controller, '%[^/]/%s', $class, $method) !== 2) {
			$method = 'index';
		}
		/*if (!file_exists(APPPATH . 'controllers/' . $this->directory . ucfirst($class) . '.php')) {
			return;
		}*/
		$this->set_class($class);
		$this->set_method($method);
		$this->uri->rsegments = array(
			1 => $class,
			2 => $method
		);
	}
	protected function _validate_request($segments)
	{
		/**
		 * @purpose 物理文件校验由派框架自己完成
		 */
		// This means that all segments were actually directories
		return $segments;
	}
	//设置类
	public function set_class($class)
	{
		$this->class = str_replace(array('/', '.'), '', $class);
	}
//获取当前类
	public function fetch_class()
	{
		return $this->class;
	}

	//设置方法名
	public function set_method($method)
	{
		$this->method = $method;
	}

	//获取当前方法
	public function fetch_method()
	{
		return $this->method;
	}

	//设置目录名称
	public function set_directory($dir, $append = FALSE)
	{
		if ($append !== TRUE OR empty($this->directory)) {
			$this->directory = str_replace('.', '', trim($dir, '/')) . '/';
		} else {
			$this->directory .= str_replace('.', '', trim($dir, '/')) . '/';
		}
	}

	//获取目录
	public function fetch_directory()
	{
		return $this->directory;
	}
} 