<?php
/**
 * 微信机器人抽象类
 *
 *  require_once './Wechat/Robot.php';
 *
 *  class Wechat_Robot_Impl extends Wechat_Robot {
 *      // TODO: ...
 *  }
 *  
 *  $robot = new Wechat_Robot_Impl('token');
 *
 *  $rs = $robot->run();
 *
 *  echo $rs->response();
 *
 * @link: http://www.oschina.net/p/lanewechat
 * @author: dogstar 2014-12-28
 */

/**
require_once dirname(__FILE__) . '/InMessage.php';
require_once dirname(__FILE__) . '/OutMessage.php';

require_once dirname(__FILE__) . '/Plugin/DeviceEvent.php';
require_once dirname(__FILE__) . '/Plugin/DeviceText.php';
require_once dirname(__FILE__) . '/Plugin/Event.php';
require_once dirname(__FILE__) . '/Plugin/Image.php';
require_once dirname(__FILE__) . '/Plugin/Link.php';
require_once dirname(__FILE__) . '/Plugin/Location.php';
require_once dirname(__FILE__) . '/Plugin/Text.php';
require_once dirname(__FILE__) . '/Plugin/Video.php';
require_once dirname(__FILE__) . '/Plugin/Voice.php';

require_once dirname(__FILE__) . '/OutMessage/Image.php';
require_once dirname(__FILE__) . '/OutMessage/Music.php';
require_once dirname(__FILE__) . '/OutMessage/News.php';
require_once dirname(__FILE__) . '/OutMessage/News/Item.php';
require_once dirname(__FILE__) . '/OutMessage/Text.php';
require_once dirname(__FILE__) . '/OutMessage/Video.php';
require_once dirname(__FILE__) . '/OutMessage/Voice.php';
 */

abstract class Wechat_Robot {

    protected $debug;

    public function __construct($token, $debug = false) 
    {
        $this->debug = $debug;
    }

    /**
     * 按类型分发消息给子类执行响应
     * @return Wechat_OutMessage实例
     */
    public function run() 
    {
        // 未通过消息真假性验证
        if (!$this->debug && (!$this->isValid() || !$this->validateSignature($token))) {
            throw new PhalApi_Exception_BadRequest(
                T('wrong token')
            );
        }

        $inMessage = new Wechat_InMessage();
        $outMessage = null;

        switch ($inMessage->getMsgType()) {
            case Wechat_InMessage::MSG_TYPE_EVENT:
                $this->handleEvent($inMessage, $outMessage);
                break;
            case Wechat_InMessage::MSG_TYPE_TEXT:
                $this->handleText($inMessage, $outMessage);
                break;
            case Wechat_InMessage::MSG_TYPE_IMAGE:
                $this->handleImage($inMessage, $outMessage);
                break;
            case Wechat_InMessage::MSG_TYPE_VOICE:
                $this->handleVoice($inMessage, $outMessage);
                break;
            case Wechat_InMessage::MSG_TYPE_VIDEO:
                $this->handleVideo($inMessage, $outMessage);
                break;
            case Wechat_InMessage::MSG_TYPE_LOCATION:
                $this->handleLocation($inMessage, $outMessage);
                break;
            case Wechat_InMessage::MSG_TYPE_LINK:
                $this->handleLink($inMessage, $outMessage);
                break;
            case Wechat_InMessage::MSG_TYPE_DEVICE_EVENT:
                $this->handleDeviceEvent($inMessage, $outMessage);
                break;
            case Wechat_InMessage::MSG_TYPE_DEVICE_TEXT:
                $this->handleDeviceText($inMessage, $outMessage);
                break;
            default:
                break;
        }

        if ($outMessage !== null) {
            $outMessage->setFromUserName($inMessage->getFromUserName());
            $outMessage->setToUserName($inMessage->getToUserName());
        }

        return $outMessage;
    }

    abstract protected function handleText($inMessage, &$outMessage);

    abstract protected function handleImage($inMessage, &$outMessage);

    abstract protected function handleVoice($inMessage, &$outMessage);

    abstract protected function handleVideo($inMessage, &$outMessage);

    abstract protected function handleLocation($inMessage, &$outMessage);

    abstract protected function handleLink($inMessage, &$outMessage);

    abstract protected function handleEvent($inMessage, &$outMessage);

    abstract protected function handleDeviceEvent($inMessage, &$outMessage);

    abstract protected function handleDeviceText($inMessage, &$outMessage);

    /**
     * 判断此次请求是否为验证请求
     * @return boolean
     */
    protected function isValid() {
        return isset($_GET['echostr']);
    }

    /**
     * 判断验证请求的签名信息是否正确
     * @param  string $token 验证信息
     * @return boolean
     */
    protected function validateSignature($token) {
        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        $signatureArray = array($token, $timestamp, $nonce);
        sort($signatureArray, SORT_STRING);
        return sha1(implode($signatureArray)) == $signature;
    }
}



