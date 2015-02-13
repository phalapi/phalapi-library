<?php

class Wechat_OutMessage_Image extends Wechat_OutMessage {

    protected $mediaId;

    /**
     * @param $mediaId 通过上传多媒体文件，得到的id。
     */
    protected function doResponse() {
        $template = "<MsgType><![CDATA[image]]></MsgType>
<Image>
<MediaId><![CDATA[%s]]></MediaId>
</Image>";

        return sprintf($template, $this->mediaId);
    }

    public function setMediaId($mediaId)
    {
        $this->mediaId = $mediaId;
    }
}
