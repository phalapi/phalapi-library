<?php

interface Wechat_Plugin_Location
{
    public function handleLocation($inMessage, &$outMessage);
}
