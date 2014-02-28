<?php
rsf_require_class('Url_CommonUrl');


$config['sns_open']['sina'] = array(
    'type'=>'sina',
    'name'=>'新浪微博'
);
$config['sns_open']['tx'] = array(
    'type'=>'tx',
    'name'=>'腾讯微博'
);


$config['sns_open']['renren'] = array(
    'type'=>'renren',
    'name'=>'人人',
);
$config['sns_open']['douban'] = array(
    'type'=>'douban',
    'name'=>'豆瓣',
);


$config['sns_open']['qq'] = array(
    'type'=>'qq',
    'name'=>'QQ空间'
);

$config['sina']['WB_AKEY'] = '3118388006';
$config['sina']['WB_SKEY'] = '1c4fcbd29fc97fcc37d5a15d92308e4a';
$config['sina']['return_url'] = Url_CommonUrl::build_music_url('/share/returnurl/sina/');


$config['qq']['appid'] = '100484897';
$config['qq']['appkey'] = '15cd88068fd11660eb5fd5c076d97e1a';
$config['qq']['return_url'] = Url_CommonUrl::build_music_url('/share/returnurl/qq/');
$config['qq']['scope'] = "get_user_info,add_share,get_info,get_other_info,get_fanslist,get_idolist,add_idol,del_idol,get_tenpay_addr";
$config['qq']['errorReport'] = true;




$config['tx']['App_Key'] = '801386984';
$config['tx']['App_Secret'] = '7f663f6286d469c2c88efcf6f86b70a9';
$config['tx']['return_url'] = Url_CommonUrl::build_music_url('/share/returnurl/tx/');



$config['renren']['App_Key'] = '04cd590e37764d0a98cdf91fa518e1f5';
$config['renren']['App_Secret'] = 'a6bde81daab54e4db694641ca71cc523';
$config['renren']['return_url'] = Url_CommonUrl::build_music_url('/share/returnurl/renren/');



$config['douban']['App_Key'] = '074f866fce47aa8915ee934f6ad6ff9b';
$config['douban']['App_Secret'] = 'b5594115d95f5225';
$config['douban']['return_url'] = Url_CommonUrl::build_music_url('/share/returnurl/douban/');
