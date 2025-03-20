<?php

!defined('DEBUG') AND exit('Access Denied.');

$get_luckdraw = kv_cache_get('fox_luckdraw');

include _include(APP_PATH.'plugin/fox_luckdraw/oddfox/model/luckdraw.func.php');

$action = param(1);

$page = param(2, 1);

$pagesize = $conf['pagesize'];

if($action == 'list'){

    $method != 'GET' AND message(-1, 'Access Denied.');

    $totalnum = fox_luckdraw_count(array('amount'=>array('>'=>0)));

    $pagination = pagination(url("luckdraw-list-{page}"), $totalnum, $page, $pagesize);

    $list = fox_luckdraw_find(array('amount'=>array('>'=>0)), array('id'=>-1), $page, $pagesize);

    $header['mobile_title'] = '中奖用户';    

    $header['title'] = '中奖用户 - 幸运抽奖 - ' . $conf['sitename'];

    $header['description'] = '中奖用户,幸运抽奖,' . $conf['sitename'];

    include _include(APP_PATH.'plugin/fox_luckdraw/oddfox/template/odd_luckdraw_list.php');

}

elseif($action == 'my'){

    $method != 'GET' AND message(-1, 'Access Denied.');

    $totalnum = fox_luckdraw_count(array('uid'=>$uid));

    $pagination = pagination(url("luckdraw-my-{page}"), $totalnum, $page, $pagesize);

    $list = fox_luckdraw_find(array('uid'=>$uid), array('id'=>-1), $page, $pagesize);

    $header['mobile_title'] = '我的奖品';

    $header['title'] = '我的奖品 - 幸运抽奖 - ' . $conf['sitename'];

    $header['description'] = '我的奖品,幸运抽奖,' . $conf['sitename'];

    include _include(APP_PATH.'plugin/fox_luckdraw/oddfox/template/odd_luckdraw_list.php');

}

else{

    if($method == 'GET'){

        $list = fox_luckdraw_find(array('amount'=>array('>'=>0)), array('id'=>-1), 1, 12);

        $header['mobile_title'] = '幸运抽奖';    

        $header['title'] = '幸运抽奖 - ' . $conf['sitename'];

        $header['description'] = '幸运抽奖,' . $conf['sitename'];

        include _include(APP_PATH.'plugin/fox_luckdraw/oddfox/template/odd_luckdraw.php');

    }

    elseif($method == 'POST'){

        empty($uid) AND message(-1, '请登陆后再抽奖！');

        $user_luckdraw = fox_luckdraw_read($uid);

        $luck_date = date("Y-m-d", $user_luckdraw['create_date']);

        $nows_date = date("Y-m-d", $time);        

        if($luck_date == $nows_date && $user_luckdraw['num'] >= $get_luckdraw['fox_spinnum']){

            message(-1, "每日最多抽奖$get_luckdraw[fox_spinnum]次");

        }

        $free_num = ($luck_date != $nows_date) ? $get_luckdraw['fox_freenum'] : ($get_luckdraw['fox_freenum'] - $user_luckdraw['num']);

        $fox_expname = fox_luckdraw_exptype_fmt($get_luckdraw['fox_exptype']);

        if($action == 'start'){

            $message = $free_num > 0 ? "还可免费抽奖 $free_num 次，确定抽奖？" : "是否扣除1次机会抽奖？( 当前抽奖次数有 {$user['chouj']}次 )";

            message(0, $message);

        }else{

            $expfees = 0;

            if($free_num <= 0){
                

                $user['chouj'] < 1 AND message(-1, '抽奖次数不足，无法抽奖。');

                $expfees = $user['chouj'];

                $expfees > 0 AND user_update($uid, array('chouj-'=>1));

            }

            $fox_ratio_arr = array(

                '1' => $get_luckdraw['fox_v1_ratio'],

                '2' => $get_luckdraw['fox_v2_ratio'],

                '3' => $get_luckdraw['fox_v3_ratio'],

                '4' => $get_luckdraw['fox_v4_ratio'],

                '5' => $get_luckdraw['fox_v5_ratio'],

                '6' => $get_luckdraw['fox_v6_ratio'],

                '7' => $get_luckdraw['fox_v7_ratio']

            );

            $result['fox_spintime'] = $get_luckdraw['fox_spintime'] * 1000;

            $result['fox_rotates'] = $get_luckdraw['fox_rotates'] * 360;

            $fox_luck_id = fox_luckdraw_getrand($fox_ratio_arr);

            switch($fox_luck_id){

                case '1':

                    $prize = $get_luckdraw['fox_v1_name']; 

                    $amount = $get_luckdraw['fox_v1_exps'];

                    $exptype = $get_luckdraw['fox_v1_type'];

                    $result['prize'] = "菊花瞬间一紧恭喜你抽中$prize"; 

                    $result['angle'] = 165;

                    break;

                case '2':

                    $prize = $get_luckdraw['fox_v2_name']; 

                    $amount = $get_luckdraw['fox_v2_exps'];

                    $exptype = $get_luckdraw['fox_v2_type'];

                    $result['prize'] = "人品大爆发!恭喜你抽中$prize"; 

                    $result['angle'] = 225;

                    break;

                case '3':

                    $prize = $get_luckdraw['fox_v3_name']; 

                    $amount = $get_luckdraw['fox_v3_exps'];

                    $exptype = $get_luckdraw['fox_v3_type'];

                    $result['prize'] = "人品大爆发!恭喜你抽中$prize"; 

                    $result['angle'] = 285;

                    break;

                case '4':

                    $prize = $get_luckdraw['fox_v4_name']; 

                    $amount = $get_luckdraw['fox_v4_exps'];

                    $exptype = $get_luckdraw['fox_v4_type'];

                    $result['prize'] = "运气真好！恭喜你抽中$prize"; 

                    $result['angle'] = 345;

                    break;

                case '5':

                    $prize = $get_luckdraw['fox_v5_name']; 

                    $amount = $get_luckdraw['fox_v5_exps'];

                    $exptype = $get_luckdraw['fox_v5_type'];

                    $result['prize'] = "运气真好！恭喜你抽中$prize";

                    $result['angle'] = 45;

                    break;

                case '6':

                    $prize = $get_luckdraw['fox_v6_name'];

                    $amount = $get_luckdraw['fox_v6_exps'];

                    $exptype = $get_luckdraw['fox_v6_type'];

                    $result['prize'] = "恭喜你抽中$prize"; 

                    $result['angle'] = 105;

                    break;

                case '7':

                    $prize = $get_luckdraw['fox_v7_name'];

                    $amount = $get_luckdraw['fox_v7_exps'];

                    $exptype = $get_luckdraw['fox_v7_type'];

                    $result['prize'] = "太可惜了居然与大奖擦肩而过！";

                    $i = mt_rand(0, 5);

                    $num_arr = array(15, 75, 135, 195, 255, 315);

                    $result['angle'] = $num_arr[$i];

                    break;

            }            

            if(!empty($amount)){

                user_update($uid, array($exptype.'+'=>$amount));

            }

            $spinnum = ($luck_date != $nows_date) ? 1 : ($user_luckdraw['num'] + 1);

            fox_luckdraw_create($uid, $prize, $amount, $spinnum, $exptype);

            message(0, $result);

        }

    }

}?>
