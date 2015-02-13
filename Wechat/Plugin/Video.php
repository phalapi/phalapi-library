<?php

interface Wechat_Plugin_Video
{
    public function handleVideo($inMessage, &$outMessage);
}
