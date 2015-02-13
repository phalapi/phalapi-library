<?php

interface Wechat_Plugin_DeviceEvent
{
    public function handleDevice_event($inMessage, &$outMessage);
}
