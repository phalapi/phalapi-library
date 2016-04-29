#基于PhalApi的视图控制器

### 1.安装和配置

#### 1.1 扩展包下载
```
//从 PhalApi-Library 扩展库中下载获取 View 扩展包，如使用：
git clone https://git.oschina.net/dogstar/PhalApi-Library.git

//然后把 View 目录复制到 ./PhalApi/Library/ 下，即：
cp ./PhalApi-Library/View/ ./PhalApi/Library/ -R

//到此处安装完毕！
```

### 2.入门使用
#### 2.1 入口注册
```
//此处的入口注册不是在init.php中注册，而是在每个项目的index.php中注册

//系统自带的代码
DI()->loader->addDirs('Demo');

//其他代码...

//视图控制器 需要预设2个参数，第一个参数为该项目名称，第二个参数为视图类型(也就是你有多套模板使用哪一套)
DI()->view = new View_Lite('Demo', 'Default');
```

### 3.目录的配置方式
***举个栗子：***  
比如你的项目为```Demo```你需要在Demo项目下新建目录```View/Default```这里就是你以后存放HTML模板的地方了  

需要用到JS CSS怎么办？  

你可以在```Public/demo/```下新建目录```view/default/css```或者```view/default/js```这2个目录下就可以存放JS或者CSS了  

### 4.模板的使用方法
这个视图机制其实是最简单的方式，在做接口时，视图用的也不是很多，但是也会用到，所以我们没有必要去弄一些很复杂的视图模块来做这快。  
在模板中我们还是使用最原始的PHP代码来写。

4.1、在接口中载入视图并使用
```
//比如一个方法 Demo.php
class Api_Demo extends PhalApi_Api{
    public function index() {
        $output = array();
        $output['test'] = '标题';
        $output['list'] = array(
            array(
                'name' => '张三',
                'age' => '15',
            ),
            array(
                'name' => '李四',
                'age' => '22',
            ),
            array(
                'name' => '王五',
                'age' => '35',
            ),
        );

        // 我们现在需要做的事情是在模板中使用，我们先需要在Demo/View/Default中新建一个index.htm的文件

        //抛出变量
        DI()->view->assign($output);

        //抛出多个变量
        $output_two = '第二个变量';
        DI()->view->assign(array('two' => $output_two));

        //引入模板
        DI()->view->show('index');
    }
}
```
这样在接口里面就已经写完了，接下来我们需要在模板中使用  

比如我们有3个文件 index.html,head.htm,foot.htm

head.htm
```
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>demo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">

    <!-- 这里可以写公共的CSS、JS -->
</head>
<body>
```

index.htm
```
<!--载入head模板-->
<?php DI()->view->load('head');?>

<h1>这是一个<?php echo $test?></h1>
<h2>遍历</h2>
<ul>
    <?php foreach ($list as $k => $v) { ?>
        <li>名字：<?php echo $v['name']?>，年龄：<?php echo $v['age']?></li>
    <?php } ?>
</ul>

<!--载入foot模板-->
<?php DI()->view->load('foot');?>
```

foot.htm
```
</body>
</html>
```

###5.总结

在此希望本扩展能给大家带来解决实际问题的思路，如果出现问题或者是有BUG可以直接联系我**QQ7579476**也可加入PhalApi交流群一同交流探讨

注:笔者能力有限有说的不对的地方希望大家能够指出,也希望多多交流!

**官网QQ交流群:421032344  欢迎大家的加入!**
