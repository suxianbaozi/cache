<?php

$config['domain'] = 'reco.cn';
//针对专辑，歌手木有 群星类型
$config['author_type'][1] = '男艺人';
$config['author_type'][2] = '女艺人';
$config['author_type'][3] = '乐队/组合';
$config['author_type'][4] = '群星';

$config['singer_type'] = $config['author_type'];
unset($config['singer_type'][4]);



$config['region_domain']['cn'] = 1;

$config['user_search_table'][1] = 'user_search';
$config['album_search_table'][1] = 'album_search';

$config['region'] = array(
    '1'=>'中国大陆',
    '2'=>'香港',
    '3'=>'台湾',
    '19'=>'新加坡/马来西亚',
    //'4'=>'日本',
    //'5'=>'韩国',
    /*'7'=>'亚洲其他',
    '8'=>'英国',
    '9'=>'法国',
    '10'=>'欧洲其他',
    '11'=>'美国/加拿大',
    '12'=>'美洲其他',
    '13'=>'非洲',
    '14'=>'大洋洲'*/
);

$config['region_assoc'] = array(
    '1'=>1,
    '2'=>2,
    '3'=>3,
    '19'=>array(19,20)
);

$config['album_type'] = array(
    '1'=>'录音室专辑',
    '2'=>'EP单曲',
    '3'=>'现场专辑',
    '4'=>'精选集',
    '5'=>'原声带'
);
$config['signature'] = 'RECO888';
$config['memcache'] = '192.168.1.2:11211';
$config['redis'] = '127.0.0.1:6379';
$config['uploadimage'] = 'http://upload.reco.cn/uploadimage';
$config['uploadape'] = 'http://upload.reco.cn/uploadape';
$config['uploadlrc'] = 'http://upload.reco.cn/uploadlrc';
$config['file_url'] = 'http://file.reco.cn';
$config['uploadurl'] = 'http://upload.reco.cn/uploadurl';
$config['uploadmp3'] = 'http://upload.reco.cn/uploadmp3';


$config['region_user_search_table'][1] = 'user_search';
#$config['region_user_search_table'][3] = 'user_search_tw';

$config['region_album_search_table'][1] = 'album_search';
#$config['region_album_search_table'][3] = 'album_search_tw';

$config['region_music_search_table'][1] = 'music_search';
#$config['region_music_search_table'][3] = 'music_search_tw';


$config['cache'] = 1;
$config['download_url'] = 'http://file.reco.cn/download/';

$config['cut_image'] = 'http://upload.reco.cn/cutimage';



$config['currency'] = array(
    '1'=>array(
        'sign'=>'¥',
        'name'=>'人民币'
    ),
);
$config['pay_method'][] = array(
    'name'=>'alipay',
    'label'=>'支付宝',
    'logo'=>'http://static.qiushi.dev.reco.cn/images/payment/alipay.png'
);


$config['share']['raise'] = 0.9;
$config['share']['sell'] = 0.7;
$config['tax'] = 0;
$config['alipay_tool_pay'] = 0.012;




