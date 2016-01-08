<?php

//初始化SMS_Lite传入配置文件地址,第二个参数为是否开启debug模式开启debug模式(默认false)会把返回结果打印出来(生产环境请不要进行设置)
$SMS = new SMS_Lite("app.SMSService");

//发送模板短信
$SMS->sendTemplateSMS("手机号码", "内容数据", "模板Id");

//短信模板查询
$SMS->QuerySMSTemplate("模板ID");

//语音验证码
$SMS->voiceVerify("验证码内容", "循环播放次数", "接收号码", "显示的主叫号码", "营销外呼状态通知回调地址", '语言类型', '第三方私有数据');

//语音文件上传
$SMS->MediaFileUpload("文件名", "文件二进制数据");

//话单下载 前一天的数据（从00:00 – 23:59）
$SMS->billRecords("话单规则", "客户的查询条件");

//IVR外呼
$SMS->ivrDial("待呼叫号码", "用户数据", "是否录音");

//外呼通知
$SMS->landingCall("被叫号码", "语音文件名称", "文本内容", "显示的主叫号码", "循环播放次数", "外呼通知状态通知回调地址", '用户私有数据', '最大通话时长', '发音速度', '音量', '音调', '背景音编号');

//主帐号信息查询
$SMS->queryAccountInfo();

//呼叫状态查询
$SMS->QueryCallState("callid", "查询结果通知的回调url地址");



