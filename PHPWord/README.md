_本扩展类库转自[rootxy/PhalApi-PHPWord](https://github.com/rootxy/PhalApi-PHPWord)。_

#PhalApi-PHPWord扩展第一版  
在接口开发中遇到需要生成Word文档的需求，发现一个PHPWord的第三方库能满足大部分需求，于是整合到了PhalApi框架中。  
作者:624770448@qq.com  

地址:  
官网地址:http://www.phalapi.net/   
Github地址:https://github.com/rootxy/PhalApi-PHPWord  

#1. 安装  

1.把扩展下载下来放入Library文件夹内。 
      
      git clone https://github.com/rootxy/PhalApi-PHPWord  
      
2.注册扩展，init.php中加入：

    DI()->PHPWord = function() {
    return new PHPWord_Lite();
    };


#2. 使用

//实例化Word  

    $PHPWord = DI()->PHPWord;

//操作Word... 

    $section = $PHPWord->createSection();
    $PHPWord->addLinkStyle('myOwnLinkStyle', array('bold'=>true, 'color'=>'808000'));

//保存  

    $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
    $objWriter->save('Text.docx');

#3. 功能  
1.支持插入表格，页眉页脚，图片，列表，超链接等。  
2.支持模版的使用，类似smarty模版引擎使用。  
3.原插件地址http://phpword.codeplex.com/  
具体使用PHPWord生成Word演示 请参考二级目录下DEMO文件夹下个各类，放入Domain文件夹实例化调用即可看到生成效果。  
