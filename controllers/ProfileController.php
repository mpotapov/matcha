<?php

include_once ROOT . '/models/Profile.php';
include_once ROOT . '/models/User.php';

class ProfileController
{

    public function actionSetLogTime()
    {
        if (empty($_POST))
            return false;

        User::setLogTime($_POST['user_id'], $_POST['date']);

        return true;
    }

    public function actionNotifications()
    {
        $userName = Profile::checkLogged();

        $notifications = Profile::getNotifications($_SESSION['userId']);
        $countNotif = User::countNotifications($_SESSION['userId']);

        require_once(ROOT . '/views/profile/notifications.php');
        return true;
    }

    public static function actionDeleteNotification()
    {
        if (empty($_POST))
            return false;

        Profile::deletetNotification($_POST['id']);

        return true;
    }

    public function actionAction($action)
    {
        switch ($action) {
            case 'like':
                if (User::likedBlockedFaked('matches', $_SESSION['userId'], $_POST['whom_id']) == false)
                    User::likeBlockFake('matches', $_SESSION['userId'], $_POST['whom_id'], $_POST['username']);
                User::connect($_SESSION['userId'], $_POST['whom_id'], $_POST['username']);
                break;
            case 'unlike':
                if (User::likedBlockedFaked('matches', $_SESSION['userId'], $_POST['whom_id']) == true)
                    User::unLikeBlockFake('matches', $_SESSION['userId'], $_POST['whom_id'], $_POST['username']);
                User::unconnect($_SESSION['userId'], $_POST['whom_id'], $_POST['username']);
                break;
            case 'block':
                if (User::likedBlockedFaked('block', $_SESSION['userId'], $_POST['whom_id']) == false)
                    User::likeBlockFake('block', $_SESSION['userId'], $_POST['whom_id'], $_POST['username']);
                break;
            case 'unblock':
                if (User::likedBlockedFaked('block', $_SESSION['userId'], $_POST['whom_id']) == true)
                    User::unLikeBlockFake('block', $_SESSION['userId'], $_POST['whom_id'], $_POST['username']);
                break;
            case 'fake':
                if (User::likedBlockedFaked('fake', $_SESSION['userId'], $_POST['whom_id']) == false)
                    User::likeBlockFake('fake', $_SESSION['userId'], $_POST['whom_id'], $_POST['username']);
                break;
            case 'unfake':
                if (User::likedBlockedFaked('fake', $_SESSION['userId'], $_POST['whom_id']) == true)
                    User::unLikeBlockFake('fake', $_SESSION['userId'], $_POST['whom_id'], $_POST['username']);
                break;
            default:
                break;

        }
        return true;
    }

    public function actionAdd_marker()
    {
        User::addLocation($_POST['user_id'], $_POST['username'], $_POST['lat'], $_POST['lng']);
        return true;
    }

    public function actionDelete_photo()
    {
        Profile::delPhoto($_POST['photoName']);
        echo $_POST['photoName'];
        return true;
    }

    public function actionUpload_image()
    {
        echo Profile::uploadNewImage();
        return true;
    }

    public function actionUpload_profile_image()
    {
        echo Profile::uploadProfileImage();
        return true;
    }

    public function actionUser()
    {
        $userName = Profile::checkLogged();
        $uri = explode('/', Router::getURI());
        $username = $uri[1];

        $userData = Profile::getData($username);
        $marker = User::getLocation($username);
        $userInterestList = explode(',', $userData['interest_list']);
        $countNotif = User::countNotifications($_SESSION['userId']);

        if ($userData != false) {
            $userPhotos = Profile::getUserPhotos($userData['id']);
            $loginUserPhotos = Profile::getUserPhotos($_SESSION['userId']);
            $logTime = User::getLogTime($userData['id']);
            $extendedProfile = User::extendedProfile($_SESSION['userId']);
            if ($userData['id'] != $_SESSION['userId']) {
                $liked = User::likedBlockedFaked('matches', $_SESSION['userId'], $userData['id']);
                $blocked = User::likedBlockedFaked('block', $_SESSION['userId'], $userData['id']);
                $blockedYou = User::likedBlockedFaked('block', $userData['id'], $_SESSION['userId']);
                $faked = User::likedBlockedFaked('fake', $_SESSION['userId'], $userData['id']);
                $likedYou = User::likedBlockedFaked('matches', $userData['id'], $_SESSION['userId']);
                $connected = User::connected($userData['id'], $_SESSION['userId']);
                $profileChecked = User::profileChecked($_SESSION['userName'], $userData['id']);
            }

            require_once(ROOT . '/views/profile/index.php');
        } else
            $this->actionWrong_page();
        return true;
    }

    public function actionSave()
    {

        $errors = false;

        if (!User::checkEmail($_POST['email']))
            $errors[] = "Email is invalid";
        if (!User::checkIfExistEmailUsername($_POST['email'], $_POST['username']))
            $errors[] = "User with such email or username already exist";
        if (!User::checkUserName($_POST['username']))
            $errors[] = "Username can be between 3 and 8 symbols";
        if (!ctype_alpha($_POST['first_name']) || !ctype_alpha($_POST['last_name']))
            $errors[] = "First name and last name must contain only letters";
        if (!User::checkAge($_POST['age']))
            $errors[] = "Invalid age. Age can be from 18 to 150";
        foreach ($_POST as $data) {
            if ($data == "") {
                $errors[] = "Please, fill in all the fields";
                break;
            }
        }

        if ($errors == false) {
            User::saveProfileChanges($_POST);
            echo "Changes saved";
        } else {
            foreach ($errors as $error)
                echo $error . "<br>";
        }
        return true;
    }

    public function actionWrong_page()
    {
        if (!empty($_SESSION))
            header('location: /profile/' . $_SESSION['userName'] . '/');
        else
            header('location: /authorization/login/');
    }
}