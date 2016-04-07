<div class="container">

    <?php if (!isset($errors)) {
        $errors = array();
    } ?>
    <?php echo htmlspecialchars_decode($title).' '.$username; ?>
    <form class="form-signin" role="form" method="post" action="<?php echo $getRoute('profile')?>">
        <h2 class="form-signin-heading">Change password</h2>

        <?php foreach ($errors as $error) { ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <strong>Error!</strong> <?php echo $error ?>
            </div>
        <?php } ?>
        <input type="email" class="form-control" placeholder="Email address" required autofocus name="email" disabled value="<?php echo $user->email?>"><br/>
        <input type="password" class="form-control" placeholder="Password" required name="password">
        <input type="password" class="form-control" placeholder="New password" required name="newpassword1">
        <input type="password" class="form-control" placeholder="Confirm new password" required name="newpassword2">
        <button class="btn btn-lg btn-primary btn-block" type="submit">Update password</button>
        <?php $generateToken()?>
    </form>

</div>