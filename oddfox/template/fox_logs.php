<?php !defined('DEBUG') AND exit('Access Denied.');include _include(ADMIN_PATH.'view/htm/header.inc.htm');?>
<div class="row">
    <div class="col-lg-12">
        <div class="btn-group mb-3" role="group">
            <a class="btn btn-secondary" href="<?php echo url("plugin");?>">本地插件</a>
            <a class="btn btn-secondary" href="<?php echo url("plugin-setting-{$dir}");?>">抽奖设置</a>
            <a class="btn btn-secondary active" href="<?php echo url("plugin-setting-{$dir}-logs");?>">抽奖日志</a>
        </div>
        <div class="btn-group mb-3 hidden-sm float-right" role="group">
            <a class="btn btn-danger" target="_blank" href="https://bbs.oddfox.cn"><i class="icon-firefox"></i></a>
            <a class="btn btn-dark" href="javascript:void(0);" onclick="javascript:location.reload();"><i class="icon-refresh"></i></a>
            <a class="btn btn-dark" href="<?php echo url("plugin");?>"><i class="icon-times"></i></a>
        </div>
        <div class="card">
            <div class="card-header"><i class="icon-list"></i> 抽奖日志</div>
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

<?php include _include(ADMIN_PATH.'view/htm/footer.inc.htm');?>
<script>$('.list-group-item').base_href('../');</script>