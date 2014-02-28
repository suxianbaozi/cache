<?php
rsf_require_class('Mail_Mailer');
rsf_require_class('Bll_Web_MailQueue');
class GlobalFun {
    /**
     *  获取拼音信息
     *
     * @access    public
     * @param     string  $str  字符串
     * @param     int  $ishead  是否为首字母
     * @return    string
     */
    public static function pinyin($str, $ishead = 0) {
        $str = iconv('utf-8', 'gb18030', $str);
        $pinyins = array();
        $restr = '';
        $str = trim($str);
        $slen = strlen($str);
        if ($slen < 2) {
            return $str;
        }
        if (count($pinyins) == 0) {

            $list_str = RSF::get_instance() -> get_config('pinyin', 'pinyin');
            $list_pinyin = explode("\n", $list_str);
            foreach ($list_pinyin as $line) {
                $line = iconv('utf-8', 'gb18030', $line);
                $pinyins[$line[0] . $line[1]] = substr($line, 3, strlen($line) - 3);
            }
        }
        for ($i = 0; $i < $slen; $i++) {
            if (ord($str[$i]) > 0x80) {
                $c = $str[$i] . $str[$i + 1];
                $i++;
                if (isset($pinyins[$c])) {
                    if ($ishead == 0) {
                        $restr .= $pinyins[$c];
                    } else {
                        $restr .= $pinyins[$c][0];
                    }
                } else {
                    $restr .= "_";
                }
            } else if (preg_match("/[a-z0-9]/i", $str[$i])) {
                $restr .= $str[$i];
            } else {
                $restr .= "_";
            }
        }
        return $restr;
    }

    /**
     * 截取字符串
     * @param $str String
     * @param $length Integer
     * @param $ext String
     * @return String
     * */
    public static function cut_string($str, $len, $ext = '') {
        return mb_substr($str, 0, $len, 'utf-8') . $ext;
    }

    /**
     * 通过tile的竖线来获取原始名称和地区名称
     * @param $title String
     * @return Array
     * */
    public static function format_title($title) {
        $names = explode('|', $title);
        return array('region_name' => $names[0] ? $names[0] : $names[1], 'origin_name' => $names[1] ? $names[1] : $names[0]);
    }

    /**
     * 通过用户列表来拼一个作者名字
     * @param $user_list Array
     * @return String
     * */
    public static function build_author_name($user_list) {
        $title = '';
        if ($user_list) {
            foreach ($user_list as $k => $v) {
                $title .= $v['region_name'] . ',';
            }
        }
        return substr($title, 0, -1);
    }

    /**
     * 将url加密
     * @param $url String
     * @return String
     * */
    public static function encode_mp3_url($url) {
        $len = strlen($url);
        $result = '';
        for ($i = $len - 1; $i >= 0; $i--) {
            $result .= chr(ord($url[$i]) + 102);
        }
        return urlencode($result);
    }

    /**
     * 签名函数
     * @param
     * */
    public static function sign($str) {
        $signature = RSF::get_instance() -> get_config('signature');
        return md5($str . $signature);
    }

    /**
     * 生成一个关于用户的key
     * @param $type String
     * @return String
     * */
    public static function build_user_key($type) {
        $guid = RSF::get_instance() -> get_request() -> get_guid();
        return md5($type . $guid);
    }

    /*
     * 获取当前周期  待删除
     */
    public static function get_current_rank_cycle() {
        $day = date('z') + 2;
        if (date('Y') == 2013) {
            $cycle = ceil($day / 7) - 21;
        } else {
            $cycle = ceil($day / 7);
        }
        $dateindex_array = array();
        $dateindex_array['year'] = date('Y');
        $dateindex_array['cycle'] = $cycle;
        return $dateindex_array;
    }

    public static function format_desc($desc) {
        //$desc = str_replace(' ', '&nbsp', $desc);
        $desc = str_replace("\n", '<br />', $desc);
        return $desc;
    }

    public static function build_author_url($user_list) {
        $url = '';
        foreach ($user_list as $v) {
            $url .= "<a title=" . $v['region_name'] . " href=" .Url_Singer::build_view_url($v['user_id']) . '>' . $v['region_name'] . '</a>,';
        }
        return substr($url, 0, -1);
    }

    public static function get_sn() {
        $sn = date("YmdHis", time());
        $sn .= sprintf("%04d", rand(0, 9999));
        return $sn;
    }

    public static function get_section() {
        $dateindex = 20130630; //第一期
        if ($dateindex) {
            $that_zero_time = strtotime($dateindex.'000000')+24*3600;
            $now = time();
            $passed = floor(($now-$that_zero_time) /(7*24*3600));
            return $section = 1+$passed;     
        } else {
            return 1;
        }
    }

    /**
     * 生成一个黄色的tag
     * */
    public static function build_user_tag($sns_name, $type) {

        if ($type == 3) {
            return '<span class="singername">' . $sns_name . '</span>';
        } else if ($type == 4) {
            return '<span class="singername">' . $sns_name . '</span>';
        } else if ($type == 2) {
            return '<span class="companyname">' . $sns_name . '</span>';
        } else {
            return $sns_name;
        }

    }

    /**
     * 日期
     * */
    public static function format_date($time) {
        $date = date("Ymd", $time);
        $cur = date("Ymd", time());
        if ($date == $cur) {
            return date('今天H:i', $time);
        } else if ($date == ($cur - 1)) {
            return date('昨天H:i', $time);
        } else {
            return date('Y-m-d H:i:s', $time);
        }
    }

    /**
     * 支持富文本的过滤防xss函数
     * */
    public static function no_xss($html_string) {
        //第一步过滤script标签
        $reg1 = "/<\/*script[^>]*>/i";
        //忽略大小写
        while (preg_match($reg1, $html_string)) {
            $html_string = preg_replace($reg1, '', $html_string);
        }
        //第二步过滤 expression
        $reg2 = "/expression/i";
        while (preg_match($reg2, $html_string)) {
            $html_string = preg_replace($reg2, '', $html_string);
        }
        //第三步 过滤属性 onerror onload
        $reg3 = "/ (on[a-zA-Z]+)/i";
        while (preg_match($reg3, $html_string)) {
            $html_string = preg_replace($reg3, ' ', $html_string);
        }
        //第四步 过滤协议类型 src=jav scr="" href="" href=
        $reg4 = "/(href|src) *\= *('|\"){0,1}([^>^ ^\"^']+)('|\"){0,1}/i";
        preg_match_all($reg4, $html_string, $result);
        $url_list = $result[3];
        foreach ($url_list as $v) {
            if (!preg_match("/^http\:\/\//i", $v)) {
                $html_string = str_replace($v, '', $html_string);
            }
        }
        //第五步 过滤 link标签
        $reg5 = "/<\/*link[^>]*>/i";
        //忽略大小写
        while (preg_match($reg5, $html_string)) {
            $html_string = preg_replace($reg5, '', $html_string);
        }
        //第六步 过滤iframe 标签
        $reg6 = "/<\/*iframe[^>]*>/i";
        //忽略大小写
        while (preg_match($reg6, $html_string)) {
            $html_string = preg_replace($reg6, '', $html_string);
        }
        return $html_string;
    }
    public static function build_price($price) {
        $m_h = '';
        $num = '';
        for($i=0;$i<strlen($price);$i++) {
            $n = substr($price, $i,1);
            if($n=='.') {
                $num = 'dot';
            } else {
                $num = $n;
            }
            $m_h .='<span class="costNum cost_'.$num.'">'.$n.'</span>';
        }
        return $m_h;
    }
    public static function send_email($email,$subject,$body,$sender='noreply') {
    	//拦截数据存入数据库 会在job里面发邮件
    	$mail_bll = new Bll_Web_MailQueue();
    	$mail_bll->add_email_to_queue($email,$subject,$body);
    	return false;
    }
    public static function post($url,$datas) {
        $c = '';
        foreach($datas as $k=>$v) {
            $c.=$k."=".urlencode($v).'&';
        }
        $c = substr($c, 0,-1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $c);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
    public static function get_time_from_duration($duration) {
        $t = explode('.', $duration);
        $t = $t[0];
        $t = explode(':', $t);
        return 3600*intval($t[0])+60*intval($t[1])+intval($t[2]);
    }
    public static function get_transfer_pay($pay) {
        return round($pay*0.005>1?($pay*0.005>25?25:$pay*0.005):1,2);
    }
    
}
