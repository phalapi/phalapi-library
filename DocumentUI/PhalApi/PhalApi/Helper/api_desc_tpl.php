<?php
echo <<<EOT
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$service} - 接口文档 - {$projectName}</title>
    <link rel="icon" href="/resources/images/logo.svg" size="any" mask>
    <link type="text/css" href="/resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="/resources/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link type="text/css" href="/resources/css/theme.css" rel="stylesheet">
    <link type="text/css" href="/resources/images/icons/css/font-awesome.css" rel="stylesheet">
</head>
<body>
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="listAllApis.php"><img class="logo" src="/resources/images/logo.svg"></a>
            <div class="nav-collapse collapse navbar-inverse-collapse">
                <ul class="nav nav-icons">
                    <li class="active"><a href="#"><i class="icon-envelope"></i></a></li>
                    <li><a href="#"><i class="icon-eye-open"></i></a></li>
                    <li><a href="#"><i class="icon-bar-chart"></i></a></li>
                </ul>
                <form class="navbar-search pull-left input-append" action="#">
                <input type="text" class="span3">
                <button class="btn" type="button">
                    <i class="icon-search"></i>
                </button>
                </form>
                <ul class="nav pull-right">
                    <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown
                        <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Item No. 1</a></li>
                            <li><a href="#">Don't Click</a></li>
                            <li class="divider"></li>
                            <li class="nav-header">Example Header</li>
                            <li><a href="#">A Separated link</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Support </a></li>
                    <li class="nav-user dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="/resources/images/user.png" class="nav-avatar" />
                        <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Your Profile</a></li>
                            <li><a href="#">Edit Profile</a></li>
                            <li><a href="#">Account Settings</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- /.nav-collapse -->
        </div>
    </div>
    <!-- /navbar-inner -->
</div>
EOT;
$set_service = isset($_GET['service']) ? $_GET['service'] : '';
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) ? 'https://' : 'http://';
$url = $url . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost');
$url .= substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/') + 1);
echo <<<EOT
<div class="wrapper">
            <div class="container">
                <div class="row">
                    <div class="span3">
                        <div class="sidebar">
                            <ul class="widget widget-menu unstyled">
                                <li><a 
EOT;
if(empty($set_service)){
    echo 'class="active"';
}
echo <<<EOT
href="listAllApis.php">接口服务列表</a></li>
                            </ul>
EOT;
$uri = $env ? '' : str_ireplace('listAllApis.php', 'checkApiParams.php', $_SERVER['PHP_SELF']);
$num = 0;
foreach($menus as $menu){
    echo '<ul class="widget widget-menu unstyled parent"><li><a class="collapsed '.'" data-toggle="collapse" href="#api-'.$num.'"><i class="icon-chevron-down pull-right"></i><i class="icon-chevron-up pull-right">
                                </i>'.$menu['title'].'</a><ul id="api-'.$num.'" class="collapse unstyled child">';
    foreach($menu['methods'] as $method){
        if ($env) {
            ob_start();
            $_REQUEST['service'] = $method['service'];
            include ($webRoot . D_S . 'checkApiParams.php');
            $string = ob_get_clean();
            saveHtml($webRoot, $method['service'], $string);
            $link = $method['service'] . '.html';
        } else {
            $concator = strpos($uri, '?') ? '&' : '?';
            $link = $uri . $concator . 'service=' . $method['service'];
        }
        echo '<li><a href="'.$link.'"'.($_GET['service'] == $method['service'] ? 'class="active"' : '').'>'.$method['title'].'</a></li>';
    }
    echo '</ul></li></ul>';
    $num++;
}
echo <<<EOT
                            <!--/.widget-nav-->
                        </div>
                        <!--/.sidebar-->
                    </div>
                    <!--/.span3-->
                    <div class="span9">
                        <div class="content">
                            <div class="module">
                                <div class="module-head">
                                    <h3>接口：{$service}</h3>
                                </div>
                                <div class="module-body">
                                    <p>
									接口描述：<strong>{$description}</strong>
                                    <br/>
									<small>接口说明：{$descComment}</small>
                                    <br/>
                                    <small>接口地址:{$url}</small>
                                    <br/><br/>
                                    <strong>接口参数</strong>
								</p>
								<table class="table table-striped table-bordered table-condensed">
								  <thead>
									<tr><th>参数名字</th><th>类型</th><th>是否必须</th><th>默认值</th><th>其他</th><th>说明</th></tr>
								  </thead>
								  <tbody>
EOT;

$typeMaps = array(
    'string' => '字符串',
    'int' => '整型',
    'float' => '浮点型',
    'boolean' => '布尔型',
    'date' => '日期',
    'array' => '数组',
    'fixed' => '固定值',
    'enum' => '枚举类型',
    'object' => '对象'
);

foreach ($rules as $key => $rule) {
    $name = $rule['name'];
    if (! isset($rule['type'])) {
        $rule['type'] = 'string';
    }
    $type = isset($typeMaps[$rule['type']]) ? $typeMaps[$rule['type']] : $rule['type'];
    $require = isset($rule['require']) && $rule['require'] ? '<font color="red">必须</font>' : '可选';
    $default = isset($rule['default']) ? $rule['default'] : '';
    if ($default === NULL) {
        $default = 'NULL';
    } else if (is_array($default)) {
        $default = json_encode($default);
    } else if (! is_string($default)) {
        $default = var_export($default, true);
    }
    
    $other = array();
    if (isset($rule['min'])) {
        $other[] = '最小：' . $rule['min'];
    }
    if (isset($rule['max'])) {
        $other[] = '最大：' . $rule['max'];
    }
    if (isset($rule['range'])) {
        $other[] = '范围：' . implode('/', $rule['range']);
    }
    if (isset($rule['source'])) {
        $other[] = '数据源：' . strtoupper($rule['source']);
    }
    $other = implode('；', $other);
    
    $desc = isset($rule['desc']) ? trim($rule['desc']) : '';
    
    echo "<tr><td>$name</td><td>$type</td><td>$require</td><td>$default</td><td>$other</td><td>$desc</td></tr>\n";
}

/**
 * 返回结果
 */
echo <<<EOT
                </tbody>
            </table>
            <h3>返回结果</h3>
            <table class="table table-striped table-bordered table-condensed" >
                <thead>
                    <tr><th>返回字段</th><th>类型</th><th>说明</th></tr>
                </thead>
                <tbody>
EOT;

foreach ($returns as $item) {
    $name = $item[1];
    $type = isset($typeMaps[$item[0]]) ? $typeMaps[$item[0]] : $item[0];
    $detail = $item[2];
    
    echo "<tr><td>$name</td><td>$type</td><td>$detail</td></tr>";
}

echo <<<EOT
            </tbody>
        </table>
EOT;

/**
 * 异常情况
 */
if (! empty($exceptions)) {
    echo <<<EOT
            <h3>异常情况</h3>
            <table class="table table-striped table-bordered table-condensed" >
                <thead>
                    <tr><th>错误码</th><th>错误描述信息</th>
                </thead>
                <tbody>
EOT;
    
    foreach ($exceptions as $exItem) {
        $exCode = $exItem[0];
        $exMsg = isset($exItem[1]) ? $exItem[1] : '';
        echo "<tr><td>$exCode</td><td>$exMsg</td></tr>";
    }
    
    echo <<<EOT
            </tbody>
        </table>
EOT;
}

/**
 * 返回结果
 */
echo <<<EOT
<h3>
    请求模拟 &nbsp;&nbsp;
</h3>
EOT;

echo <<<EOT
<table class="table table-striped table-bordered table-condensed" >
    <thead>
        <tr><th>参数</th><th>是否必填</th><th>值</th></tr>
    </thead>
    <tbody id="params">
        <tr>
            <td>service</td>
            <td><font color="red">必须</font></td>
            <td><input name="service" value="{$service}" style="width:90%;margin-bottom: 0;" class="C_input" type="text"/></td>
        </tr>
EOT;
foreach ($rules as $key => $rule) {
    $name = $rule['name'];
    $require = isset($rule['require']) && $rule['require'] ? '<font color="red">必须</font>' : '可选';
    $default = isset($rule['default']) ? $rule['default'] : '';
    $desc = isset($rule['desc']) ? trim($rule['desc']) : '';
    $inputType = (isset($rule['type']) && $rule['type'] == 'file') ? 'file' : 'text';
    echo <<<EOT
    <tr>
        <td>{$name}</td>
        <td>{$require}</td>
        <td><input name="{$name}" value="{$default}" placeholder="{$desc}" style="width:90%;margin-bottom: 0;" class="C_input" type="$inputType" multiple="multiple"/></td>
    </tr>
EOT;
}
echo <<<EOT
    </tbody>
EOT;

echo <<<EOT
<tfoot>
<tr>
<td>
<select name="request_type" style="margin-bottom: 0;">
        <option value="POST">POST</option>
        <option value="GET">GET</option>
    </select>
</td>
<td>
<input name="request_url" type="text" value="{$url}" style="margin-bottom: 0;"/>
</td>
<td>
<input type="submit" name="submit" value="发送" id="submit" class="btn btn-small btn-success" style="margin-bottom: 0;"/>
</td>
</tr>
</tfoot>
EOT;
/**
 * JSON结果
 */
echo <<<EOT
								  </tbody>
								</table>
                                <div id="json_output" style="margin-top: 15px;"></div>
                                </div>
                            </div>
                            <!--/.module-->
                        </div>
                        <!--/.content-->
                    </div>
                    <!--/.span9-->
                </div>
            </div>
            <!--/.container-->
        </div>
        <!--/.wrapper-->
      
        <div class="footer">
            <div class="container">
                <b class="copyright">&copy; 2014 Edmin - EGrappler </b>All rights reserved. More Templates <a href="http://www.cssmoban.com/" target="_blank" title=""></a> - Collect from <a href="http://www.cssmoban.com/" title="" target="_blank"></a>
            </div>
        </div>
        <script src="/resources/scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
        <script src="/resources/scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
        <script src="/resources/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="/resources/scripts/flot/jquery.flot.js" type="text/javascript"></script>
        <script src="/resources/scripts/flot/jquery.flot.resize.js" type="text/javascript"></script>
        <script src="/resources/scripts/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="/resources/scripts/common.js" type="text/javascript"></script>
        <script src="/resources/scripts/api_desc.js" type="text/javascript"></script>
        <script stype="text/javascript">
            $('a.active').parents('ul.child').addClass('in').parents('ul.parent').addClass('active');
        </script>
    </body>
</html>
EOT;


