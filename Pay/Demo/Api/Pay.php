<?php
/*
 * +----------------------------------------------------------------------
 * | 支付
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 summer All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: summer <aer_c@qq.com> <qq7579476>
 * +----------------------------------------------------------------------
 * | This is not a free software, unauthorized no use and dissemination.
 * +----------------------------------------------------------------------
 * | Date
 * +----------------------------------------------------------------------
 */

class Api_Pay extends PhalApi_Api {

	public function getRules() {
        return array(
            'index' => array(
                'type' 	=> array('name' => 'type', 'type' =>'string', 'require' => true, 'desc' => '引擎类型，比如alipay')
            ),
        );
	}

	/**
	 * 支付接口
	 * @return [type] [description]
	 */
	public function index(){
		//获取对应的支付引擎
        DI()->pay->set($this->type);

        //抛出错误
        if(DI()->pay->getError()){
            throw new PhalApi_Exception_BadRequest($info, $code);
        }

        $data = array();
        $data['order_no'] = DI()->pay->createOrderNo();
        $data['title'] = '测试的订单';
        $data['body'] = '测试的订单';
        $data['price'] = '0.01';
        echo DI()->pay->buildRequestForm($data);
        exit;
	}
}