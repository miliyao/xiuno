<?php

define('FONT_PATH', APP_PATH . 'plugin/fox_luckdraw/oddfox/static/img/xmlt.ttf');

define('ZPBG_PATH', APP_PATH . 'plugin/fox_luckdraw/oddfox/static/img/bg.png');

define('BCBG_PATH', APP_PATH . 'plugin/fox_luckdraw/oddfox/static/img/');



function fox_luckdraw_getrand($arr){

    $r = '';

    $sum = array_sum($arr);

    foreach($arr as $key => $x){

        $rand = mt_rand(1, $sum);

        if($rand <= $x){

            $r = $key;

            break;

        }else{

            $sum -= $x;

        }

    }

    unset($arr);

    return $r;

}



function fox_luckdraw_exptype_fmt($exptype){

    if(empty($exptype)) return NULL;

    switch($exptype){

        case 'credits': $str = '经验'; break;    

        case 'golds': $str = '金币'; break;

        case 'rmbs': $str = 'RMB'; break;

    }

    return $str;

}



function fox_luckdraw__create($arr){

    $r = db_create('fox_luckdraw', $arr);

    return $r;

}



function fox_luckdraw_create($uid, $prize, $amount, $num, $exptype){

    global $time;

    $r = fox_luckdraw__create(array('uid'=>$uid, 'prize'=>$prize, 'amount'=>$amount, 'num'=>$num, 'exptype'=>$exptype, 'create_date'=>$time));

    return $r;

}



function fox_luckdraw_read($uid){

    $r = db_find_one('fox_luckdraw', array('uid'=>$uid), array('id'=>-1));

    return $r;

}



function fox_luckdraw_count($cond = array()){

    $n = db_count('fox_luckdraw', $cond);

    return $n;

}



function fox_luckdraw_find($cond = array(), $orderby = array(), $page = 1, $pagesize = 20){

    $r = db_find('fox_luckdraw', $cond, $orderby, $page, $pagesize);

    foreach($r as &$v){

        fox_luckdraw_find_format($v);

    }

    return $r;

}



function fox_luckdraw_find_format(&$arr){

    if(empty($arr)) return array();

    $__user = user_read($arr['uid']);

    $arr['user_url'] = '<a href="'.url("user-$arr[uid]").'" class="text-danger" target="_blank">'.$__user['username'].'</a>';

    $arr['expname'] = fox_luckdraw_exptype_fmt($arr['exptype']);

    $arr['create_date_fmt'] = date('Y-m-d', $arr['create_date']);

    $arr['create_date_fmt2'] = date('Y-m-d H:i:s', $arr['create_date']);

}



function fox_luckdraw_autowrap($string){

    $content = "";

    for($i = 0; $i < mb_strlen($string, 'UTF-8'); $i++){

        $letter[] = mb_substr($string, $i, 1, 'UTF-8');

    }

    foreach($letter as $l){

        $teststr = $content . " " . $l;

        $testbox = imagettfbbox(30, 0, FONT_PATH, $teststr);

        if(($testbox[2] > 30) && ($content !== "")){

            $content.= "\n";

        }

        $content.= $l;

    }

    return $content;

}



function fox_luckdraw_create_img($arr){

    $text01 = fox_luckdraw_autowrap($arr['fox_v1_name']);

    $text02 = fox_luckdraw_autowrap($arr['fox_v2_name']);

    $text03 = fox_luckdraw_autowrap($arr['fox_v3_name']);

    $text04 = fox_luckdraw_autowrap($arr['fox_v4_name']);

    $text05 = fox_luckdraw_autowrap($arr['fox_v5_name']);

    $text06 = fox_luckdraw_autowrap($arr['fox_v6_name']);

    $text07 = fox_luckdraw_autowrap($arr['fox_v7_name']);

    $config = array(

        'text' => array(

            array('text' => $text01, 'left' => 255, 'top' => 520, 'fontColor' => '255,255,255', 'angle' => 163,),

            array('text' => $text02, 'left' => 470, 'top' => 445, 'fontColor' => '255,255,255', 'angle' => -135,),

            array('text' => $text03, 'left' => -92, 'top' => 225, 'fontColor' => '255,255,255', 'angle' => -75,),

            array('text' => $text04, 'left' => 340, 'top' => 80, 'fontColor' => '255,255,255', 'angle' => -15,),

            array('text' => $text05, 'left' => 128, 'top' => 155, 'fontColor' => '255,255,255', 'angle' => 45,),

            array('text' => $text06, 'left' => 88, 'top' => 375, 'fontColor' => '255,255,255', 'angle' => 105,),

            array('text' => $text07, 'left' => 225, 'top' => 90, 'angle' => 16,),

            array('text' => $text07, 'left' => 80, 'top' => 260, 'angle' => 75,),

            array('text' => $text07, 'left' => 153, 'top' => 470, 'angle' => 135,),

            array('text' => $text07, 'left' => 370, 'top' => 511, 'angle' => 193,),

            array('text' => $text07, 'left' => 518, 'top' => 341, 'angle' => 255,),

            array('text' => $text07, 'left' => 442, 'top' => 130, 'angle' => -45,)

        )

    );

    return fox_luckdraw_bg($config);

}



function fox_luckdraw_bg($config = array() , $filename = "start.png"){

    $textDefault = array('text'=>'', 'left'=>0, 'top'=>0, 'fontSize'=>28, 'fontColor'=>'255,46,0', 'angle'=>0,'fontPath'=>FONT_PATH);

    //处理背景

    $bg = imagecreatefrompng(ZPBG_PATH);

    $imagew = imagesx($bg);

    $imageh = imagesy($bg);

    $image = imagecreatetruecolor($imagew, $imageh);

    $bgimage = imagecolorallocatealpha($image, 0, 0, 0, 127);

    imagealphablending($image, TRUE);

    imagefill($image, 0, 0, $bgimage);

    imagecopy($image, $bg, 0, 0, 0, 0, $imagew, $imageh);

    

    //处理文字

    if(!empty($config['text'])){

        foreach($config['text'] as $key => $val){

            $val = array_merge($textDefault, $val);

            list($R, $G, $B) = explode(',', $val['fontColor']);

            $fontColor = imagecolorallocate($image, $R, $G, $B);

            $val['left'] = $val['left'] < 0 ? $imagew - abs($val['left']) : $val['left'];

            $val['top'] = $val['top'] < 0 ? $imageh - abs($val['top']) : $val['top'];

            imagefttext($image, $val['fontSize'], $val['angle'], $val['left'], $val['top'], $fontColor, $val['fontPath'], $val['text'], array("linespacing" => 0.75));

            imagesavealpha($image, TRUE);

        }

    }

    //生成图片

    $res = imagepng($image, BCBG_PATH.$filename);

    imagedestroy($image);

    if(!$res) return FALSE;

    return TRUE;

}

?>
