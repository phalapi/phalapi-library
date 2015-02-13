<?php

class Wechat_OutMessage_Voice extends Wechat_OutMessage {

    protected $mediaId;

    protected function doResponse()
    {
        $template = "<MsgType><![CDATA[voice]]></MsgType>
<Voice>
<MediaId><![CDATA[%s]]></MediaId>
</Voice>";

        return sprintf($template, $this->mediaId);
    }

    public function setMediaId($mediaId)
    {
        $this->mediaId = $mediaId;
        return $this;
    }
}
