<?php

interface Wechat_Plugin_Image
{
    public function handleImage($inMessage, &$outMessage);
}
