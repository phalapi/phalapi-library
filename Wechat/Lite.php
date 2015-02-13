<?php
/**
 * 微信机器人轻聊版
 *
 * - 读取配置中注册的插件，依次短路处理
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-12-29
 */

class Wechat_Lite extends Wechat_Robot {

    protected function handleText($inMessage, &$outMessage)
    {
        $this->handleWhat(Wechat_InMessage::MSG_TYPE_TEXT, $inMessage, $outMessage);
    }

    protected function handleImage($inMessage, &$outMessage)
    {
        $this->handleWhat(Wechat_InMessage::MSG_TYPE_IMAGE, $inMessage, $outMessage);
    }

    protected function handleVoice($inMessage, &$outMessage)
    {
        $this->handleWhat(Wechat_InMessage::MSG_TYPE_VOICE, $inMessage, $outMessage);
    }

    protected function handleVideo($inMessage, &$outMessage)
    {
        $this->handleWhat(Wechat_InMessage::MSG_TYPE_VIDEO, $inMessage, $outMessage);
    }

    protected function handleLocation($inMessage, &$outMessage)
    {
        $this->handleWhat(Wechat_InMessage::MSG_TYPE_LOCATION, $inMessage, $outMessage);
    }

    protected function handleLink($inMessage, &$outMessage)
    {
        $this->handleWhat(Wechat_InMessage::MSG_TYPE_LINK, $inMessage, $outMessage);
    }

    protected function handleEvent($inMessage, &$outMessage)
    {
        $this->handleWhat(Wechat_InMessage::MSG_TYPE_EVENT, $inMessage, $outMessage);
    }

    protected function handleDeviceEvent($inMessage, &$outMessage)
    {
        $this->handleWhat(Wechat_InMessage::MSG_TYPE_DEVICE_EVENT, $inMessage, $outMessage);
    }

    protected function handleDeviceText($inMessage, &$outMessage)
    {
        $this->handleWhat(Wechat_InMessage::MSG_TYPE_DEVICE_TEXT, $inMessage, $outMessage);
    }

    protected function handleWhat($msgType, $inMessage, &$outMessage)
    {
        $plugins = DI()->config->get('app.plugins.' . $msgType);

        if (empty($plugins) || !is_array($plugins)) {
            return;
        }

        foreach ($plugins as $pluginName) {
            $pluginInstance = DI()->get(strtolower($pluginName), $pluginName);

            $func = 'handle' . ucfirst($msgType);
            if (is_callable(array($pluginInstance, $func))) {
                $pluginInstance->$func($inMessage, $outMessage);
                //call_user_func(array($pluginInstance, $func), $inMessage, &$outMessage);
            }

            if ($outMessage !== null) {
                break;
            }
        }
    }

}

