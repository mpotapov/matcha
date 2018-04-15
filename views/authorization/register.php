<?php include ROOT . '/views/layouts/header.php' ?>

<div class="p-x-1 p-y-3">
    <form class="card card-block m-x-auto bg-faded form-width" action="#" method="post">
        <legend class="m-b-1 text-xs-center">Registration</legend>
        <?php if ($result == true): ?>
            <p style="color: green">You are registered!</p>
        <?php endif; ?>
        <?php if (isset($errors) && is_array($errors)) : ?>
            <?php foreach ($errors as $error): ?>
                <p style="color: red"><?php echo $error;?></p>
            <?php endforeach; ?>
        <?php endif; ?>
        <div class="form-group input-group">

 <span class="has-float-label">
 <input class="form-control" name="first" type="text" placeholder="First name" value="<?php echo $firstname?>"/>
 <label for="first">First name</label>
 </span>
            <span class="has-float-label">
 <input class="form-control" name="last" type="text" placeholder="Last name" value="<?php echo $lastname?>"/>
 <label for="last">Last name</label>
 </span>
        </div>
        <div class="form-group has-float-label">
            <input class="form-control" name="username" type="text" placeholder="Username" value="<?php echo $username?>"/>
            <label for="password">Username</label>
        </div>
        <div class="form-group input-group">
            <span class="input-group-addon">@</span>
            <span class="has-float-label">
 <input class="form-control" name="email" type="email" placeholder="name@example.com" value="<?php echo $email?>"/>
 <label for="email">E-mail</label>
 </span>
        </div>
        <div class="form-group has-float-label">
            <input class="form-control" name="password1" type="password" placeholder="••••••••" value="<?php echo $password1?>"/>
            <label for="password">Password</label>
        </div>
        <div class="form-group has-float-label">
            <input class="form-control" name="password2" type="password" placeholder="••••••••" value="<?php echo $password2?>"/>
            <label for="password">Confirm Password</label>
        </div>

        <div class="text-xs-center">
            <button class="btn btn-block btn-primary" name="submit" type="submit">Register</button>
        </div>
        <a href="/authorization/login/">Log in</a>
    </form>
</div>

<?php include ROOT . '/views/layouts/footer.php';