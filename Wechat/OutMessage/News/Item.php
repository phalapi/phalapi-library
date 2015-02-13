<?php

class Wechat_OutMessage_News_Item{

    protected $title;

    protected $description;

    protected $picUrl;

    protected $url;

    public function response()
    {
        $template = "<item>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<PicUrl><![CDATA[%s]]></PicUrl>
<Url><![CDATA[%s]]></Url>
</item>";

        return sprintf($template, $this->title, $this->description, $this->picUrl, $this->url);
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

    public function setPicUrl($picUrl)
    {
        $this->picUrl = $picUrl;
        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function __tostring()
    {
        return $this->response();
    }
}
