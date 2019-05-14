<?php

namespace Glam;

class GlamSms
{
    /**
     * @var SMS
     */
    protected $sms;

    /**
     * @var string
     */
    protected $id;

    function __construct()
    {
        global $config;

        if (!isset($config)) {
            die('Cannot found GNU $config');
        }

        $ip = $config['cf_icode_server_ip'] ?? die('Cannot found icode server ip');
        $id = $config['cf_icode_id'] ?? die('Cannot found icode server id');
        $password = $config['cf_icode_pw'] ?? die('Cannot found icode server password');
        $port = $config['cf_icode_server_port'] ?? die('Cannot found icode server port');

        $this->id = $id;

        require G5_LIB_PATH . '/icode.sms.lib.php';


        $sms = new \SMS;
        $sms->SMS_con($ip, $id, $password, $port);

        $this->sms = $sms;
    }

    function write($from, $to, $content)
    {
        $from = removeNotNumbers($from);
        if (strlen($from) < 10) {
            die($from . ' is not a number');
        }
        $to = removeNotNumbers($to);
        if (strlen($to) < 10) {
            die($to . ' is not a number');
        }

        if (is_array($content)) {
            $content = implode(PHP_EOL, $content);
        }

        $content = iconv("utf-8", "euc-kr", stripslashes($content));

        $error = $this->sms->Add(
            $to,
            $from,
            $this->id,
            $content,
            ""
        );

        if ($error) {
            die($error);
        }

        return $this;
    }

    function send()
    {
        return $this->sms->Send();
    }
}

function removeNotNumbers(string $value)
{
    return preg_replace('#[^\d]#', '', $value);
}