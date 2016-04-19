<div class="container">

    <?php if (!isset($errors)) {
        $errors = array();
    } ?>
    <?php echo htmlspecialchars_decode($title).' '.$username; ?>
    <div class="row">
        <div class="col-md-5 profile_block">
            <span class="pull-left profile glyphicon glyphicon-user" aria-hidden="true"></span>
            <a href="<?php echo $getRoute('change_pwd')?>" type="button" class="fancybox edit btn btn-primary">Change password</a>
            <dl class="user-info col-md-3">
                <dt>User id:</dt>
                <dd><?=$user->id?></dd>
                <dt>User role:</dt>
                <dd><?=$user->role?></dd>
                <dt>User email:</dt>
                <dd><?=$user->email?></dd>
            </dl>
        </div>

    </div>
    <?php $generateToken()?>

</div>