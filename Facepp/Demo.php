<?php

//face++ 扩展

//初始化
$facepp = new Facepp_Lite(array(
		'api_key'=>' your api_key ',
        'api_secret'=>'your api_secret',
));
//以上这句话也可以写成如下 默认app.Facepp配置
$facepp = new Facepp_Lite();

//识别本地图片
$params['img']          = '{image file path}';
$params['attribute']    = 'gender,age,race,smiling,glass,pose';
$response               = $facepp->execute('/detection/detect',$params);
print_r($response);

//识别远程图片
$params['url']          = 'http://www.faceplusplus.com.cn/wp-content/themes/faceplusplus/assets/img/demo/1.jpg';
$response               = $facepp->execute('/detection/detect',$params);
print_r($response);

if($response['http_code'] == 200) {
    #json decode
    $data = json_decode($response['body'], 1);
    
    #获取人脸坐标
    foreach ($data['face'] as $face) {
        $response = $facepp->execute('/detection/landmark', array('face_id' => $face['face_id']));
        print_r($response);
    }
    
    #创建人物
    $response = $facepp->execute('/person/create', array('person_name' => 'unique_person_name'));
    print_r($response);

    #删除人物
    $response = $facepp->execute('/person/delete', array('person_name' => 'unique_person_name'));
    print_r($response);
}