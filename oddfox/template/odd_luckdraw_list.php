<?php !defined('DEBUG') AND exit('Access Denied.');include _include(APP_PATH.'view/htm/header.inc.htm');?>
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-0">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item"><a class="nav-link font-weight-bold <?php echo $action == 'list' ? 'active' : '';?>" href="<?php echo url("luckdraw-list");?>">中奖用户</a></li>
                    <li class="nav-item"><a class="nav-link font-weight-bold <?php echo $action == 'my' ? 'active' : '';?>" href="<?php echo url("luckdraw-my");?>">我的奖品</a></li>
                </ul>
            </div>
            <ul class="list-group list-group-flush">
            <?php if($list){?>
                <?php foreach($list as $value){?>
                <li class="list-group-item"><span class="float-right text-grey hidden-sm"><?php echo $value['create_date_fmt2'];?></span>用户 <?php echo $value['user_url'];?> 在幸运抽奖中抽到 <span class="text-danger"><?php echo $value['prize'];?></span> <?php if($value['amount']>0){?>获得 <span class="text-danger"><?php echo $value['amount'];?><?php echo $value['expname'];?></span><?php }else{?>与大奖擦肩而过！<?php }?></li>
                <?php }?>
            <?php }else{?>
                <li class="list-group-item">无</li>
            <?php }?>
            </ul>
            <?php if($pagination){?>
            <ul class="pagination justify-content-center flex-wrap my-3"><?php echo $pagination;?></ul>
            <?php }?>

        </div>        
    </div>
</div>

<?php include _include(APP_PATH.'view/htm/footer.inc.htm');?>