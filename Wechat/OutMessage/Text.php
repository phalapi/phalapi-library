<?php

class Wechat_OutMessage_Text extends Wechat_OutMessage{
    
    protected $content;

    protected function doResponse()
    {
        $template = "<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>";

        return sprintf($template, $this->content);
    }

    public function setContent($content) 
    {
        $this->content = $content;
        return $this;
    }
}
