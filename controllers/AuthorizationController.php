<?php

include_once ROOT . '/models/User.php';
include_once ROOT . '/models/Profile.php';

class AuthorizationController
{

    public static function actionRedirect()
    {
        return true;
    }

    public static function actionActivation($code)
    {
        Profile::activateAccount($code);
        return true;
    }

    public function actionRestore_password()
    {
        $errors = false;

        $email = $_POST['email'];
        if (!User::checkEmail($email))
            $errors[] = "Email is invalid";
        if (!User::checkIfExistEmailUsername($email, null))
            $errors[] = "No such email";

        if ($errors == false) {
            User::sendRestoreEmail($email);
            echo "Success";
        } else {
            foreach ($errors as $error)
                echo $error . "<br>";
        }
        return true;
    }

    public function actionLogin()
    {

//        if (!empty($_SESSION))
//            header('location: /profile/' . $_SESSION['userName'] . '/');
        $username = '';
        $password = '';
        $email = '';

        $errors = false;

        if (isset($_POST['submit1'])) {
            $username = htmlentities($_POST['username']);
            $password = htmlentities($_POST['password']);

            if (!User::checkUserName($username))
                $errors[] = "Username can be between 3 and 8 symbols";
            else if (!User::checkPassword($password))
                $errors[] = "Password must be more then 4 symbols";
            else if (!User::checkStatus($username))
                $errors[] = "You must activate your account by clicking on link on your email";
            $user = User::checkUserData($username, $password);
            if (!$user)
                $errors[] = "There aren't any user with this data";
            else if (!$errors) {
                session_start();
                $_SESSION['userId'] = $user['id'];
                $_SESSION['userName'] = $user['username'];

                header('location: /profile/' . $_SESSION['userName']);
            }
        }

        require_once(ROOT . '/views/authorization/login.php');

        return true;
    }

    public function actionLogout()
    {

        session_destroy();
        header('location: /authorization/login/');
        return true;
    }

    public function actionRegistration()
    {

        $firstname = '';
        $lastname = '';
        $username = '';
        $email = '';
        $password1 = '';
        $password2 = '';

        $result = false;

        if (isset($_POST['submit'])) {
            $firstname = htmlentities($_POST['first']);
            $lastname = htmlentities($_POST['last']);
            $username = htmlentities($_POST['username']);
            $email = htmlentities($_POST['email']);
            $password1 = htmlentities($_POST['password1']);
            $password2 = htmlentities($_POST['password2']);

            $errors = false;

            if (!User::checkName($firstname))
                $errors[] = "First and last name can be between 1 and 20 symbols";
            else if (!User::checkName($lastname))
                $errors[] = "First and last name can be between 1 and 20 symbols";
            else if (!ctype_alpha($firstname) || !ctype_alpha($lastname) || !ctype_alpha($username))
                $errors[] = "First name, last name and username must contain only letters";
            else if (!User::checkUserName($username))
                $errors[] = "Username can be between 3 and 8 symbols";
            else if (!User::checkEmail($email))
                $errors[] = "Email is invalid";
            else if (!User::checkPassword($password1))
                $errors[] = "Password must be more then 4 symbols";
            else if ($password1 != $password2)
                $errors[] = "Passwords do not match";
            else if (empty($firstname) || empty($lastname))
                $errors[] = "First name and Last name can't be empty";
            else if (User::checkIfExistEmailUsername($email, $username))
                $errors[] = "There are user with such email or username";

            if ($errors == false)
                $result = User::register($username, $firstname, $lastname, $email, $password1);
        }

        require_once(ROOT . '/views/authorization/register.php');

        return true;
    }

}