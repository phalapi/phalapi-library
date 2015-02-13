<?php

class Wechat_OutMessage_Music extends Wechat_OutMessage {

    protected $title;

    protected $description;

    protected $musicUrl;

    protected $hQMusicUrl;

    protected $thumbMediaId;

    protected function doResponse()
    {
        $template = "<MsgType><![CDATA[music]]></MsgType>
<Music>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<MusicUrl><![CDATA[%s]]></MusicUrl>
<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
</Music>";

        return sprintf($template, 
            $this->title, $this->description, $this->musicUrl, $this->hQMusicUrl, $this->thumbMediaId);
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

    public function setMusicUrl($musicUrl)
    {
        $this->musicUrl = $musicUrl;
        return $this;
    }

    public function setHQMusicUrl($hQMusicUrl)
    {
        $this->hQMusicUrl = $hQMusicUrl;
        return $this;
    }

    public function setThumbMediaId($thumbMediaId)
    {
        $this->thumbMediaId = $thumbMediaId;
        return $this;
    }
}

