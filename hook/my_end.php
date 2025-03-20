elseif($action == 'xzraffle') {
    if($method == 'GET')
        
        include _include(APP_PATH.'plugin/fox_luckdraw/oddfox/template/my_xzraffle.htm');
        elseif($method=='POST'){
        
        $cjnum = param('cjnum');
        $choujs = setting_get('choujs');
        $gocj = $cjnum*$choujs;
        $user = user_read($uid);
        if($user['golds'] < $gocj ){message(-1,'您的余额不足，请先充值~');die();}
        db_update('user',array('uid'=>$uid),array('golds-'=>$gocj,'chouj+'=>$cjnum));
        message(-1, "购买成功!");die();
        
        }
}elseif($action == 'cjianfo') {
    if($method == 'GET')
        include _include(APP_PATH.'plugin/fox_luckdraw/oddfox/template/my_cjianfo.htm');
        
}