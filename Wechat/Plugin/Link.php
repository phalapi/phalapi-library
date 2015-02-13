<?php

interface Wechat_Plugin_Link
{
    public function handleLink($inMessage, &$outMessage);
}
