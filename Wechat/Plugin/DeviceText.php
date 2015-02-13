<?php

interface Wechat_Plugin_DeviceText
{
    public function handleDevice_text($inMessage, &$outMessage);
}
