<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 16-11-2
 * Time: 上午9:42
 */

/**
 * Class URI
 * @purpose 解析url,并将url 分解为对应的片段,存储到segments数组中,
 * queryString 分解后 存到 $_GET数组中
 * ROUTER类在之后的解析路由动作中,也主要依靠URI类的segments属性数组来获取当前上下文的URI请求信息
 */
class CI_URI {
	//缓存uri片段
	public $keyval = array();
	//当前的uri片段
	public $uri_string = '';
	//URI片段数组 数组键值从0开始
	public $segments = array();
	//重建索引的片段数组,数组键从1开始
	public $rsegments = array();
	//URI段允许PCRE字符组
	protected $_permitted_uri_chars;

	//构造函数,获取需要config文件中的配置
	public function __construct()
	{
		$this->config = DI()->config->get('App.CIRoute.config');
		/**
		 * @purpose 如果启用了 query_string,我们不解析任何部分
		 *
		 */
		if(is_cli() OR true !== $this->config{'enable_query_strings'})
		{
			$this->_permitted_uri_chars =  $this->config{'permitted_uri_chars'};
			//如果是一个CLI配置请求,则忽视
			if(is_cli())
			{
				$uri = $this->_parse_argv();
			}
			else
			{
				/**
				 * @purpose 下面的 uri_protocol 是在config中的路由配置,其实是询问以那种方式解析url
				 *     		默认为AUTO,自动检测For BC purposes only
				 */
				$protocol = $this->config{'uri_protocol'};
				empty($protocol) && $protocol = 'REQUEST_URI';
				/**
				 *@purpose 开始尝试以各种方式解析主要有: AUTO,REQUEST_URI,PATH_INFO,QUERY_STRING
				 * 下面会多次出现 $this->_set_uri_string($str) 这个方法,这个方法没别的,就是把$str经过过滤和修剪后赋值给$this->uri_string
				 * 这个属性,在这里暂时理解为赋值,如果脚本是在在命令行想运行的,那么参数就需要通过4_SERVER['argv']来传递
				 * 下面的$this->_parse_cli_args();就是拿到复合我们需要的路由相关的一些参数了
				 */
				switch($protocol)
				{
					case '	AUTO'://For BC purposes only
					case 'REQUEST_URI'://这种REQUEST_URI方式相对复杂一点,因此封装在$this->_parse_request_uri();里面.
						//其实大部分情况下,利用 REQUEST_URI和SCRIPT_NAME 都会得到我们想要的结果
						$uri = $this->_parse_request_uri();
						break;
					case 'QUERY_STRING'://如果是用QUERY_STRING的话，路径格式一般为index.php?c=xx&m=xx
						$uri = $this->_parse_query_uri();
						break;
					case 'PATH_INFO':
						//PATH_INFO方式,个人觉得这种方式最经济了,只是不是每次请求都有$_SERVER['PATH_INFO']
					default:
						//上面的方法都没有的话,从$_SERVER中取出键名
						$uri = isset($_SERVER[$protocol])?$_SERVER[$protocol] :$this->_parse_request_uri();
						break;
					/**
					 * @purpose _parse_request_uri方法处理 AUTO,REQUEST_URI,PATH_INFO
					 * _parse_query_string方法只处理 QUERY_STRING
					 */
				}
				$this->_set_uri_string($uri);
			}
		}


	}

	/**
	 * @purpose 给uri_string赋值
	 * 解析到$uri填充到$this->segments数组中去
	 */
	protected function _set_uri_string($str)
	{
		//移除$str不可见字符:$this->uri_string=trim(remove_invisible_characters($str, FALSE), '/')
		//防止字符中间夹入空字符造成漏洞
		$this->uri_string = trim($this->remove_invisible_characters($str, FALSE), '/');
		if('' !== $this->uri_string)
		{
			//移除url后缀,如果在配置文件中设置过的话.
			if('' !== ($suffix = (string)$this->config{'url_suffix'}))
			{
				$slen = strlen($suffix);
				if(substr($this->uri_string,-$slen) === $suffix)
				{
					$this->uri_string = substr($this->uri_string,0,-$slen);
				}
			}
			$this->segments[0] = null;
			/**
			 * @purpose 解析uri,用'/'分段,填充到$this->segments数组中去
			 */
			foreach(explode('/',trim($this->uri_string)) as $val)
			{
				$val = trim($val);
				$this->filter_uri($val);
				if('' !== $val)
				{
					$this->segments[] = $val;
				}
			}
			unset($this->segments[0]);
		}
	}


	protected  function _parse_request_uri()
	{
     	if(!isset($_SERVER['REQUEST_URI'],$_SERVER['SCRIPT_NAME']))
		{
			return '';
		}
		//从$_SERVER['REQUEST_URI']取值,分解成$uri和$query两个字符串,分别存储请求的路径和get请求参数
		$uri = parse_url('http://dummy'.$_SERVER['REQUEST_URI']);
		$query = isset($uri['query']) ? $uri['query'] : '';
		$uri = isset($uri['path'])?$uri['path']:'';
		//去掉uri中包括$_SERVER['SCRIPT_NAME'];
		//比如uri是http://www.citest.com/index.php/news/view/crm，经过处理后就变成/news/view/crm了
		if(isset($_SERVER['SCRIPT_NAME'][0]))
		{
			if(strpos($uri,$_SERVER['SCRIPT_NAME']) === 0)
			{
				$uri = (string)substr($uri,strlen($_SERVER['SCRIPT_NAME']));
			}
			elseif(strpos($uri,dirname($_SERVER['SCRIPT_NAME'])) ===0)
			{
				$uri = (string)substr($uri,strlen(dirname($_SERVER['SCRIPT_NAME'])));
			}
		}
		//对于请求服务器的具体URI包含在查询字符串这种情况。
		//例如$uri以?/开头的 ，实际上if条件换种写法就是if(strncmp($uri, '?/', 2) === 0))，类似：
		//http://www.citest.com/index.php?/welcome/index
		/**
		 * @purpose 有可能因为链接错误而获取到错误的参数
		 */
		if(trim($uri,'/') === '' && strncmp($query,'/',1) ===0)
		{
			$query = explode('?',$query,2);
			$uri = $query[0];
			$_SERVER['QUERY_STRING'] = isset($query[1]) ? $query[1] : '';
		}
		else
		{
			//其他情况直接$_SERVER['QUERY_STRING'] = $query; 如下面的请求的uri
			//http://www.citest.com/mall/lists?page=7
			$_SERVER['QUERY_STRING'] = $query;
		}
		//将查询的字符串按 键名存入$_GET
		parse_str($_SERVER['QUERY_STRING'],$_GET);
		if($uri === '/' || $uri === '')
		{
			return '/';
		}
		//调用_remove_relative_directory($uri)函数做安全处理
		//移除$uri中的../相对路径字符和反斜杠/
		return $this->_remove_relative_directory($uri);

	}

	/**
	 *@purpose 特用于query_string
	 */
	protected function _parse_query_string()
	{
		//从$_SERVER['QUERY_STRING']取值
		$uri = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');
		//对于没有实际内容的,直接返回空
		if('' === trim($uri,'/'))
		{
			return '';
		}
		elseif(0 === strncmp($uri,'/',1))
		{
			//对应生成$_SERVER['QUERY_STRING'] 和$uri
			//最后将$_SERVER['QUERY_STRING']解析于_GET数组parse_str($_SERVER['QUERY_STRING',$_GET]);
			$uri = explode('?',$uri,2);
			$_SERVER['QUERY_STRING'] = isset($uri[1]) ? $uri[1] : '';
			$uri = $uri[0];
		}
		//将查询的字符串按键名存入_GET数组中
		parse_str($_SERVER['QUERY_STRING'],$_GET);
		//调用 _remove_relative_directory($uri)函数作安全处理
		//移除$uri中的../相对路径字符和反斜杠/
		return $this->_remove_relative_directory($uri);
	}

	/**
	 * @purpose 把每一个命令行参数,变成uri段
	 *
	 */
	protected function _parse_argv()
	{
		$args = array_slice($_SERVER['argv'], 1);
		return $args ? implode('/', $args) : '';
	}

	/**
	 *@purpose 安全处理函数 移除$uri中的../相对路径字符和反斜杠////
	 * @tip 暂时看不懂
	 */
	protected function _remove_relative_directory($uri)
	{
		$uris = array();
		$tok = strtok($uri,'/');
		while($tok !== false)
		{
			if((!empty($tok) OR $tok === '0') && $tok !== '..')
			{
				$uris[] = $tok;
			}
			$tok = strtok('/');
		}
		return implode('/',$uris);
	}
	//过滤不合法的url字符，允许的uri是你的配置$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';
	public function filter_uri(&$str)
	{
		if (!empty($str) && !empty($this->_permitted_uri_chars) && !preg_match('/^[' . $this->_permitted_uri_chars . ']+$/i' . (UTF8_ENABLED ? 'u' : ''), $str)) {
			show_error('The URI you submitted has disallowed characters.', 400);
		}
	}

	/**
	 * 用于从 URI 中获取指定段。参数 n 为你希望获取的段序号，
	 * URI 的段从左到右进行编号。
	 */
	public function segment($n, $no_result = NULL)
	{
		return isset($this->segments[$n]) ? $this->segments[$n] : $no_result;
	}

	//返回确定路由后的一个uri片段
	public function rsegment($n, $no_result = NULL)
	{
		return isset($this->rsegments[$n]) ? $this->rsegments[$n] : $no_result;
	}

	/**
	 * 该方法用于将 URI 的段转换为一个包含键值对的关联数组
	 */
	public function uri_to_assoc($n = 3, $default = array())
	{
		return $this->_uri_to_assoc($n, $default, 'segment');
	}

	//相同的ci_uri：：uri_to_assoc()，只有通过重新路由段阵列。
	public function ruri_to_assoc($n = 3, $default = array())
	{
		return $this->_uri_to_assoc($n, $default, 'rsegment');
	}


	//生成的URI字符串或重新路由URI字符串键-值对
	protected function _uri_to_assoc($n = 3, $default = array(), $which = 'segment')
	{
		if (!is_numeric($n)) {
			return $default;
		}
		if (isset($this->keyval[$which], $this->keyval[$which][$n])) {
			return $this->keyval[$which][$n];
		}
		$total_segments = "total_{$which}s";
		$segment_array = "{$which}_array";
		if ($this->$total_segments() < $n) {
			return (count($default) === 0)
				? array()
				: array_fill_keys($default, NULL);
		}
		$segments = array_slice($this->$segment_array(), ($n - 1));
		$i = 0;
		$lastval = '';
		$retval = array();
		foreach ($segments as $seg) {
			if ($i % 2) {
				$retval[$lastval] = $seg;
			} else {
				$retval[$seg] = NULL;
				$lastval = $seg;
			}

			$i++;
		}
		if (count($default) > 0) {
			foreach ($default as $val) {
				if (!array_key_exists($val, $retval)) {
					$retval[$val] = NULL;
				}
			}
		}

		isset($this->keyval[$which]) OR $this->keyval[$which] = array();
		$this->keyval[$which][$n] = $retval;
		return $retval;
	}

	//很明显，它是将数组中的信息翻转成uri_string
	public function assoc_to_uri($array)
	{
		$temp = array();
		foreach ((array)$array as $key => $val) {
			$temp[] = $key;
			$temp[] = $val;
		}
		return implode('/', $temp);
	}

	//通过第二个参数看是否给uri前后加上“/”线
	public function slash_segment($n, $where = 'trailing')
	{
		return $this->_slash_segment($n, $where, 'segment');
	}

	//取一个URI路由段斜线
	public function slash_rsegment($n, $where = 'trailing')
	{
		return $this->_slash_segment($n, $where, 'rsegment');
	}

	/**
	 * 该方法和 segment() 类似，只是它会根据第二个参数在返回结果的前面或/和后面添加斜线。
	 * 如果第二个参数未设置，斜线会添加到后面根据源代码看，
	 * 如果第二个参数不是trailing,也不是leading,将会在头尾都加斜杠。
	 */
	protected function _slash_segment($n, $where = 'trailing', $which = 'segment')
	{
		$leading = $trailing = '/';
		if ($where === 'trailing') {
			$leading = '';
		} elseif ($where === 'leading') {
			$trailing = '';
		}
		return $leading . $this->$which($n) . $trailing;
	}

	/**
	 * 返回 URI 所有的段组成的数组。
	 */
	public function segment_array()
	{
		return $this->segments;
	}

	/**
	 * 返回 URI 所有的段组成的数组。
	 */
	public function rsegment_array()
	{
		return $this->rsegments;
	}

	/**
	 * 返回 URI 的总段数
	 */
	public function total_segments()
	{
		return count($this->segments);
	}

	/**
	 * 返回 URI 的总段数
	 */
	public function total_rsegments()
	{
		return count($this->rsegments);
	}

	//返回一个相对的 URI 字符串
	public function uri_string()
	{
		return $this->uri_string;
	}
	static public function remove_invisible_characters($str, $url_encoded = TRUE)
	{
		$non_displayables = array();

		// every control character except newline (dec 10),
		// carriage return (dec 13) and horizontal tab (dec 09)
		if ($url_encoded)
		{
			$non_displayables[] = '/%0[0-8bcef]/i';	// url encoded 00-08, 11, 12, 14, 15
			$non_displayables[] = '/%1[0-9a-f]/i';	// url encoded 16-31
		}

		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

		do
		{
			$str = preg_replace($non_displayables, '', $str, -1, $count);
		}
		while ($count);

		return $str;
	}

} 