<?php

interface Wechat_Plugin_Text
{
    public function handleText($inMessage, &$outMessage);
}
