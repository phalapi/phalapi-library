<?php

class Wechat_OutMessage_News extends Wechat_OutMessage {

    const MAX_ALLOW_ARTICLE_ITEM_NUM = 10;

    protected $articleItems = array();

    /**
     * 多条图文消息信息，默认第一个item为大图,注意，如果图文数超过10，则将会无响应
     */
    protected function doResponse()
    {
        $template = "<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>%s</ArticleCount>
<Articles>
%s
</Articles>";

        if (count($this->articleItems) > self::MAX_ALLOW_ARTICLE_ITEM_NUM) {
            $this->articleItems = array_slice($this->articleItems, 0, self::MAX_ALLOW_ARTICLE_ITEM_NUM);
        }

        $articlesXml = array();
        foreach ($this->articleItems as $item) {
            $articlesXml[] = $item->response();
        }

        return sprintf($template, count($this->articleItems), implode("\n", $articlesXml));
    }

    public function addItem(Wechat_OutMessage_News_Item $item)
    {
        $this->articleItems[] = $item;
        return $this;
    }
}
