<?php
include ROOT . '/views/layouts/header.php';

?>

    <div class="p-x-1 p-y-3">
        <form class="card card-block m-x-auto bg-faded form-width" id="login" action="#" method="post">
            <legend class="m-b-1 text-xs-center">Log in</legend>
            <?php if (isset($errors) && is_array($errors)) : ?>
                <?php foreach ($errors as $error): ?>
                    <p style="color: red"><?php echo $error; ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
            <div class="form-group has-float-label">
                <input class="form-control" id="username" type="text" name="username" placeholder="Username"
                       value="<?php echo $username ?>"/>
                <label for="password">Username</label>
            </div>
            <div class="form-group has-float-label">
                <input class="form-control" id="password1" type="password" name="password"
                       value="<?php echo $password ?>" placeholder="••••••••"/>
                <label for="password">Password</label>
            </div>

            <div class="text-xs-center">
                <button class="btn btn-block btn-primary" name="submit1" type="submit">Sign in</button>
            </div>
            <a href="/authorization/registration/" style="text-align: right">Registration</a>
            <br>
            <a href="#" onclick="forgotpass();">Forgot password</a>
        </form>
        <form class="card card-block m-x-auto bg-faded form-width" style="display: none" id="restore">
            <legend class="m-b-1 text-xs-center">Enter email to restore password</legend>
            <p style="color: green" id="restore_success"></p>
            <p style="color: red" id="restore_error"></p>
            <div class="form-group input-group">
                <span class="input-group-addon">@</span>
                <span class="has-float-label">
 <input class="form-control" id="email" type="email" name="email" value="<?php echo $email ?>"
        placeholder="name@example.com"/>
 <label for="email">E-mail</label>
 </span>
            </div>

            <div class="text-xs-center">
                <button id="forgot_pass" class="btn btn-block btn-primary" name="submit2" type="submit">Restore</button>
            </div>
            <a href="#" onclick="log_in();">Log in</a>
        </form>
    </div>

    <div class="fb-login-button" data-max-rows="1" data-size="small" data-button-type="login_with"
         data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="true"></div>

    <script>
        function forgotpass() {
            document.getElementById("login").style.display = "none";
            document.getElementById("restore").style.display = "block";
        }

        function log_in() {
            document.getElementById("login").style.display = "block";
            document.getElementById("restore").style.display = "none";
        }

        $(document).ready(function () {
            $("#email").click(function () {
                $("#restore_success").html("");
                $("#restore_error").html("");
            })
        });

        $(document).ready(function () {
            $("#forgot_pass").click(function () {
                $("#restore_success").html("");
                $("#restore_error").html("");
                var email = {
                    email: $("#email").val()
                };
                $.post("/authorization/restore_password/", email, function (data) {
                    data = data.toString();
                    if (data == "Success") {
                        $("#restore_success").html(data);
                    }
                    else {
                        $("#restore_error").html(data);
                    }
                });
                return false;
            })
        });
    </script>

<?php include ROOT . '/views/layouts/footer.php';