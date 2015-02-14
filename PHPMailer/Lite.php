<?php
/**
 * 邮件工具类
 *
 * @author dogstar 2014-11-03
 */

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'PHPMailer' . DIRECTORY_SEPARATOR . 'PHPMailerAutoload.php';

class PHPMailer_Lite
{
    /**
     * 发送邮件
     * @param array/string $addresses 待发送的邮箱地址
     * @param sting $title 标题
     * @param string $content 内容
     * @param boolean $isHtml 是否使用HTML格式，默认是
     * @return boolean 是否成功
     */
    public static function send($addresses, $title, $content, $isHtml = true)
    {
        $mail = new PHPMailer;

        $cfg = DI()->config->get('app.email');

        $mail->isSMTP();
        $mail->Host = $cfg['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $cfg['username'];
        $mail->Password = $cfg['password'];
        $mail->CharSet = 'utf-8';

        $mail->From = $cfg['username'];
        $mail->FromName = $cfg['fromName'];
        $addresses = is_array($addresses) ? $addresses : array($addresses);
        foreach ($addresses as $address) {
            $mail->addAddress($address);
        }

        $mail->WordWrap = 50;
        $mail->isHTML($isHtml);

        $mail->Subject = trim($title);
        $mail->Body = $content . $cfg['sign'];

        if (!$mail->send()) {
            DI()->logger->debug('Fail to send email with error: ' . $mail->ErrorInfo);
            return false;
        }
        DI()->logger->debug('succedd to send email', array('addresses' => $addresses, 'title' => $title));

        return true;
    }
}

