<?php

interface Wechat_Plugin_Voice
{
    public function handleVoice($inMessage, &$outMessage);
}
