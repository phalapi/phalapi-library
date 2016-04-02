#PhalApi-Zip -- 压缩文件处理类

![](http://webtools.qiniudn.com/master-LOGO-20150410_50.jpg)

##前言

这是笔者在工作中,同事找到的一个比较不错的文件压缩类,也经过了实际的使用很不错所以分享处理,特此鸣谢:@牧鱼人

附上:

官网地址:[http://www.phalapi.net/](http://www.phalapi.net/ "PhalApi官网")

开源中国Git地址:[http://git.oschina.net/dogstar/PhalApi/tree/release](http://git.oschina.net/dogstar/PhalApi/tree/release "开源中国Git地址")

开源中国拓展Git地址:[http://git.oschina.net/dogstar/PhalApi-Library](http://git.oschina.net/dogstar/PhalApi-Library "开源中国Git地址")


##1. 安装使用

此扩展只需要简单的把文件放到Library目录下即可使用使用方法如下:

     $zip = new Zip_Lite();


遍历指定文件夹
    
     $zip  = new Zip_Lite();
     $filelist = $zip->visitFile(文件夹路径);
     print "当前文件夹的文件:<p>\r\n";
     foreach($filelist as $file)
         printf("%s<br>\r\n", $file);
         
压缩到服务器

    $zip = new Zip_Lite();
    $zip->Zip("需压缩的文件所在目录", "ZIP压缩文件名");
    
    
压缩并直接下载
    
    $zip = new Zip_Lite();
    $zip->ZipAndDownload("需压缩的文件所在目录");
        
        
        
解压文件
    
     $zip   = new Zip_Lite();
     $zipfile   = "ZIP压缩文件名";
     $savepath  = "解压缩目录名";
     $zipfile   = $unzipfile;
     $savepath  = $unziptarget;
     $array     = $zip->GetZipInnerFilesInfo($zipfile);
     $filecount = 0;
     $dircount  = 0;
     $failfiles = array();
     set_time_limit(0);  // 修改为不限制超时时间(默认为30秒)
    
     for($i=0; $i<count($array); $i++) {
         if($array[$i][folder] == 0){
             if($zip->unZip($zipfile, $savepath, $i) > 0){
                 $filecount++;
             }else{
                 $failfiles[] = $array[$i][filename];
             }
         }else{
             $dircount++;
         }
     }
     set_time_limit(30);
    printf("文件夹:%d&nbsp;&nbsp;&nbsp;&nbsp;解压文件:%d&nbsp;&nbsp;&nbsp;&nbsp;失败:%d<br>\r\n", $dircount, $filecount, count($failfiles));
    if(count($failfiles) > 0){
        foreach($failfiles as $file){
            printf("&middot;%s<br>\r\n", $file);
        }
    }
            
            
            
获取被压缩文件的信息
        
    $zip = new Zip_Lite();
    $array = $zip->GetZipInnerFilesInfo(ZIP压缩文件名);
    for($i=0; $i<count($array); $i++) {
        printf("<b>&middot;%s</b><br>\r\n", $array[$i][filename]);
        foreach($array[$i] as $key => $value)
           printf("%s => %s<br>\r\n", $key, $value);
        print "\r\n<p>------------------------------------<p>\r\n\r\n";
    }
    
        
##2. 总结

希望此拓展能够给大家带来方便以及实用!

注:笔者能力有限有说的不对的地方希望大家能够指出,也希望多多交流!

**官网QQ交流群:421032344  欢迎大家的加入!**