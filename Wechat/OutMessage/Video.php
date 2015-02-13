<?php

class Wechat_OutMessage_Vedio extends Wechat_OutMessage {

    protected $mediaId;

    protected $title;

    protected $description;

    protected function doResponse()
    {
        $template = "<MsgType><![CDATA[video]]></MsgType>
<Video>
<MediaId><![CDATA[%s]]></MediaId>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
</Video>";
        
        return sprintf($template, $this->mediaId, $this->title, $this->description);
    }

    public function setMediaId($mediaId)
    {
        $this->mediaId = $mediaId;
        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
}
