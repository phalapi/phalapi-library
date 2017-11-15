<?php
$env && ob_start();
$table_color_arr = explode(" ", "red orange yellow olive teal blue violet purple pink grey black");
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $apiDirName; ?> - 接口列表 - <?php echo $projectName; ?></title>
<link rel="icon" href="/resources/images/logo.svg" size="any" mask>
<link type="text/css" href="/resources/bootstrap/css/bootstrap.min.css"
	rel="stylesheet">
<link type="text/css"
	href="/resources/bootstrap/css/bootstrap-responsive.min.css"
	rel="stylesheet">
<link type="text/css" href="/resources/css/theme.css" rel="stylesheet">
<link type="text/css"
	href="/resources/images/icons/css/font-awesome.css" rel="stylesheet">
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
						<li class="dropdown"><a href="#" class="dropdown-toggle"
							data-toggle="dropdown">Dropdown <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="#">Item No. 1</a></li>
								<li><a href="#">Don't Click</a></li>
								<li class="divider"></li>
								<li class="nav-header">Example Header</li>
								<li><a href="#">A Separated link</a></li>
							</ul></li>
						<li><a href="#">Support </a></li>
						<li class="nav-user dropdown"><a href="#" class="dropdown-toggle"
							data-toggle="dropdown"> <img src="/resources/images/user.png"
								class="nav-avatar" /> <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="#">Your Profile</a></li>
								<li><a href="#">Edit Profile</a></li>
								<li><a href="#">Account Settings</a></li>
								<li class="divider"></li>
								<li><a href="#">Logout</a></li>
							</ul></li>
					</ul>
				</div>
				<!-- /.nav-collapse -->
			</div>
		</div>
		<!-- /navbar-inner -->
	</div>
	<div class="wrapper">
		<div class="container">
			<div class="row">
				<div class="span3">
					<div class="sidebar">
						<?php
                            $uri = $env ? '' : str_ireplace('listAllApis.php', 'checkApiParams.php', $_SERVER['PHP_SELF']);
                            $methodTotal = 0;
                            foreach ($allApiS as $item) {
                                $methodTotal += count($item['methods']);
                            }
                        ?>
						<ul class="widget widget-menu unstyled">
							<li><a href="#" class="active">接口服务列表<b class="label pull-right"><?php echo $methodTotal;?></b></a></li>
						</ul>
						
                                <?php
                                $num = 0;
                                foreach ($allApiS as $key => $item) {
                                    echo '<ul class="widget widget-menu unstyled"><li><a class="collapsed" data-toggle="collapse" href="#api-'.$num.'"><i class="menu-icon icon-cog">
                                </i><i class="icon-chevron-down pull-right"></i><i class="icon-chevron-up pull-right">
                                </i>'.$item['title'].'</a><ul id="api-'.$num.'" class="collapse unstyled">';
                                    foreach($item['methods'] as $method){
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
                                        echo '<li><a href="'.$link.'">'.$method['title'].'</a></li>';
                                    }
                                ?>
                                </ul>
                                </li>
                                </ul>
                                <?php $num++; } ?>
						<!--/.widget-nav-->
					</div>
					<!--/.sidebar-->
				</div>
				<!--/.span3-->
				<div class="span9">
					<div class="content">
						<!--/.module-->
						<div class="module">
							<div class="module-head">
								<h3>接口服务列表</h3>
							</div>
							<div class="module-body table">
								<?php
        if (! empty($errorMessage)) {
            echo '<div class="ui error message">
                                        <strong>错误：' . $errorMessage . '</strong> 
                                        </div>';
        }
        ?>
								<table class="table">
									<thead>
										<tr>
											<th>#</th>
											<th>接口服务</th>
											<th>接口名称</th>
											<th>更多说明</th>
										</tr>
									</thead>
									<tbody>
								  	<?php
        // 展开时，将全部的接口服务，转到第一组
        $mergeAllApiS = array(
            'all' => array(
                'methods' => array()
            )
        );
        foreach ($allApiS as $key => $item) {
            if (! isset($item['methods']) || ! is_array($item['methods'])) {
                continue;
            }
            foreach ($item['methods'] as $mKey => $mItem) {
                $mergeAllApiS['all']['methods'][$mKey] = $mItem;
            }
        }
        $allApiS = $mergeAllApiS;
        ?>
		<?php
        $uri = $env ? '' : str_ireplace('listAllApis.php', 'checkApiParams.php', $_SERVER['REQUEST_URI']);
        $num2 = 0;
        foreach ($allApiS as $key => $item) {
            ?>
                                                    <?php
            $num = 1;
            foreach ($item['methods'] as $mKey => $mItem) {
                if ($env) {
                    ob_start();
                    $_REQUEST['service'] = $mItem['service'];
                    include ($webRoot . D_S . 'checkApiParams.php');
                    $string = ob_get_clean();
                    saveHtml($webRoot, $mItem['service'], $string);
                    $link = $mItem['service'] . '.html';
                } else {
                    $concator = strpos($uri, '?') ? '&' : '?';
                    $link = $uri . $concator . 'service=' . $mItem['service'];
                }
                $NO = $num ++;
                echo "<tr><td>{$NO}</td><td><a href=\"$link\" target='_blank'>{$mItem['service']}</a></td><td>{$mItem['title']}</td><td>{$mItem['desc']}</td></tr>";
            }
            ?>
                                            <?php
            $num2 ++;
        }
        ?>
								  </tbody>
								</table>
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
			<b class="copyright">&copy; 2014 Edmin - EGrappler </b>All rights
			reserved. More Templates <a href="http://www.cssmoban.com/"
				target="_blank" title="模板之家">模板之家</a> - Collect from <a
				href="http://www.cssmoban.com/" title="网页模板" target="_blank">网页模板</a>
		</div>
	</div>
	<script src="/resources/scripts/jquery-1.9.1.min.js"
		type="text/javascript"></script>
	<script src="/resources/scripts/jquery-ui-1.10.1.custom.min.js"
		type="text/javascript"></script>
	<script src="/resources/bootstrap/js/bootstrap.min.js"
		type="text/javascript"></script>
	<script src="/resources/scripts/flot/jquery.flot.js"
		type="text/javascript"></script>
	<script src="/resources/scripts/flot/jquery.flot.resize.js"
		type="text/javascript"></script>
	<script src="/resources/scripts/datatables/jquery.dataTables.js"
		type="text/javascript"></script>
	<script src="/resources/scripts/common.js" type="text/javascript"></script>
	<script src="/resources/scripts/api_desc.js" type="text/javascript"></script>
</body>
</html>
<?php
if ($env) {
    $string = ob_get_clean();
    saveHtml($webRoot, 'index', $string);
    $str = "
脚本执行完毕！离线文档保存路径为：";
    if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
        $str = iconv('utf-8', 'gbk', $str);
    }
    $str .= $webRoot . D_S . 'doc';
    echo $str, PHP_EOL, PHP_EOL;
    exit(0);
}