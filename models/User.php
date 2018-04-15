<?php

class User
{

    public static function extendedProfile($user_id)
    {
        $db = Db::getConnection();

        $sql = "SELECT extended_profile FROM `users` WHERE id = :userId";

        $result = $db->prepare($sql);
        $result->bindParam(':userId', $user_id, PDO::PARAM_INT);
        $result->execute();

        $extended = $result->fetchColumn();
        return $extended;
    }

    public static function getLogTime($user_id)
    {
        $db = Db::getConnection();

        $sql = "SELECT last_activity FROM `users` WHERE NOW() > (last_activity + INTERVAL 5 MINUTE) AND id = :userId";

        $result = $db->prepare($sql);
        $result->bindParam(':userId', $user_id, PDO::PARAM_INT);
        $result->execute();

        $date = $result->fetchColumn();
        if (!empty($date))
            return substr($date, 0, 16);
        else
            return 'Online';
    }

    public static function setLogTime($user_id, $time)
    {
        $db = Db::getConnection();

        $sql = "UPDATE users SET last_activity = :time WHERE id = :userId";

        $result = $db->prepare($sql);
        $result->bindParam(':userId', $user_id, PDO::PARAM_INT);
        $result->bindParam(':time', $time, PDO::PARAM_STR);
        $result->execute();
    }

    public static function countNotifications($user_id)
    {
        $db = Db::getConnection();

        $sql = "SELECT COUNT(*) FROM notifications WHERE user_id = :userId AND status = '0'";

        $result = $db->prepare($sql);
        $result->bindParam(':userId', $user_id, PDO::PARAM_INT);
        $result->execute();

        return $result->fetchColumn();
    }

    public static function profileChecked($who_username, $user_id)
    {
        $db = Db::getConnection();

        $sql = "SELECT * FROM notifications WHERE user_id = :user_id AND from_username = :from_username AND notification = 'checked your profile !'";

        $result = $db->prepare($sql);
        $result->bindParam(':from_username', $who_username, PDO::PARAM_STR);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $result->execute();

        if (!empty($result->fetchColumn()))
            return false;

        $time = date("Y-m-d H:i:s");

        $sql1 = "INSERT INTO notifications (user_id, from_username, notification, time) VALUES (:whom_id, :from_username, 'checked your profile !', :time)";

        $result1 = $db->prepare($sql1);
        $result1->bindParam(':from_username', $who_username, PDO::PARAM_STR);
        $result1->bindParam(':time', $time, PDO::PARAM_STR);
        $result1->bindParam(':whom_id', $user_id, PDO::PARAM_INT);
        $result1->execute();
        return true;
    }

    public static function getId($userName)
    {
        $db = Db::getConnection();

        $sql = 'SELECT users.id FROM users WHERE users.username = :userName';

        $result = $db->prepare($sql);
        $result->bindParam(':userName', $userName, PDO::PARAM_STR);
        $result->execute();

        $id = $result->fetchColumn();
        if (!empty($id))
            return $id;
        return false;
    }

    public static function getPhoto($userName)
    {
        $db = Db::getConnection();

        $sql = 'SELECT photos.profile_photo FROM users,photos
          WHERE users.username = :userName AND users.id = photos.id';

        $result = $db->prepare($sql);
        $result->bindParam(':userName', $userName, PDO::PARAM_STR);
        $result->execute();

        $photo = $result->fetchColumn();
        if (!empty($photo))
            return $photo;
        return false;
    }

    public static function getConnectedToUser($userId)
    {
        $db = Db::getConnection();

        $sql = 'SELECT users.id,users.username,photos.profile_photo FROM users,connected,photos
          WHERE users.id != :userId AND (connected.who_id1 = users.id OR connected.who_id2 = users.id)
          AND (connected.who_id1 = :userId OR connected.who_id2 = :userId) AND photos.id = users.id';

        $result = $db->prepare($sql);
        $result->bindParam(':userId', $userId, PDO::PARAM_STR);
        $result->execute();

        $result->setFetchMode(PDO::FETCH_ASSOC);

        $connected = $result->fetchAll();
        if (!empty($connected))
            return $connected;
        return false;
    }

    public static function connect($who1, $who2, $who_username)
    {
        if (self::likedBlockedFaked('matches', $who2, $who1) == false)
            return false;
        $db = Db::getConnection();

        $sql = 'INSERT INTO connected (who_id1, who_id2) VALUES (:who1, :who2)';

        $result = $db->prepare($sql);
        $result->bindParam(':who1', $who1, PDO::PARAM_STR);
        $result->bindParam(':who2', $who2, PDO::PARAM_STR);
        $result->execute();

        $time = date("Y-m-d H:i:s");

        $sql1 = "INSERT INTO notifications (user_id, from_username, notification, time) VALUES (:whom_id, :from_username, 'connected with you !', :time)";

        $result1 = $db->prepare($sql1);
        $result1->bindParam(':from_username', $who_username, PDO::PARAM_STR);
        $result1->bindParam(':time', $time, PDO::PARAM_STR);
        $result1->bindParam(':whom_id', $who2, PDO::PARAM_INT);
        $result1->execute();

        echo 'connected';
        return true;
    }

    public static function unconnect($who1, $who2, $who_username)
    {
        if (self::likedBlockedFaked('matches', $who2, $who1) == false)
            return false;
        $db = Db::getConnection();

        $sql = 'DELETE FROM connected WHERE (who_id1 = :who1 AND who_id2 = :who2) OR (who_id1 = :who2 AND who_id2 = :who1)';

        $result = $db->prepare($sql);
        $result->bindParam(':who1', $who1, PDO::PARAM_STR);
        $result->bindParam(':who2', $who2, PDO::PARAM_STR);
        $result->execute();

        $time = date("Y-m-d H:i:s");

        $sql1 = "INSERT INTO notifications (user_id, from_username, notification, time) VALUES (:whom_id, :from_username, 'unconnected from you !', :time)";

        $result1 = $db->prepare($sql1);
        $result1->bindParam(':from_username', $who_username, PDO::PARAM_STR);
        $result1->bindParam(':time', $time, PDO::PARAM_STR);
        $result1->bindParam(':whom_id', $who2, PDO::PARAM_INT);
        $result1->execute();

        echo 'unconnected';
        return true;
    }

    public static function unLikeBlockFake($action, $who_id, $whom_id, $who_username)
    {
        $db = Db::getConnection();

        $sql = 'DELETE FROM ' . $action . ' WHERE who_id = :who_id AND whom_id = :whom_id';

        $result = $db->prepare($sql);
        $result->bindParam(':who_id', $who_id, PDO::PARAM_STR);
        $result->bindParam(':whom_id', $whom_id, PDO::PARAM_STR);
        $result->execute();

        if ($action == 'block') {

            $sql1 = "UPDATE users SET fame_rating = fame_rating + 20 WHERE users.id = :whom_id";

            $result1 = $db->prepare($sql1);
            $result1->bindParam(':whom_id', $whom_id, PDO::PARAM_INT);
            $result1->execute();
        }

        if ($action == 'matches') {

            $sql1 = "UPDATE users SET fame_rating = fame_rating - 5 WHERE users.id = :whom_id";

            $result1 = $db->prepare($sql1);
            $result1->bindParam(':whom_id', $whom_id, PDO::PARAM_INT);
            $result1->execute();

            if (self::likedBlockedFaked('matches', $whom_id, $who_id) == true)
                return;

            $time = date("Y-m-d H:i:s");

            $sql1 = "INSERT INTO notifications (user_id, from_username, notification, time) VALUES (:whom_id, :from_username, 'unliked you !', :time)";

            $result1 = $db->prepare($sql1);
            $result1->bindParam(':from_username', $who_username, PDO::PARAM_STR);
            $result1->bindParam(':time', $time, PDO::PARAM_STR);
            $result1->bindParam(':whom_id', $whom_id, PDO::PARAM_INT);
            $result1->execute();
        }
    }

    public static function likeBlockFake($action, $who_id, $whom_id, $who_username)
    {
        $db = Db::getConnection();

        $sql = 'INSERT INTO ' . $action . ' (who_id, whom_id) VALUES (:who_id, :whom_id)';

        $result = $db->prepare($sql);
        $result->bindParam(':who_id', $who_id, PDO::PARAM_STR);
        $result->bindParam(':whom_id', $whom_id, PDO::PARAM_STR);
        $result->execute();

        if ($action == 'block') {

            $sql1 = "UPDATE users SET fame_rating = fame_rating - 20 WHERE users.id = :whom_id";

            $result1 = $db->prepare($sql1);
            $result1->bindParam(':whom_id', $whom_id, PDO::PARAM_INT);
            $result1->execute();

            if (self::likedBlockedFaked('matches', $who_id, $whom_id) == true)
                self::unLikeBlockFake('matches', $who_id, $whom_id, $who_username);
            self::unconnect($who_id, $whom_id, $who_username);
        }

        if ($action == 'matches') {

            $sql1 = "UPDATE users SET fame_rating = fame_rating + 5 WHERE users.id = :whom_id";

            $result1 = $db->prepare($sql1);
            $result1->bindParam(':whom_id', $whom_id, PDO::PARAM_INT);
            $result1->execute();

            if (self::likedBlockedFaked('matches', $whom_id, $who_id) == true)
                return;

            $time = date("Y-m-d H:i:s");

            $sql1 = "INSERT INTO notifications (user_id, from_username, notification, time) VALUES (:whom_id, :from_username, 'liked you !', :time)";

            $result1 = $db->prepare($sql1);
            $result1->bindParam(':from_username', $who_username, PDO::PARAM_STR);
            $result1->bindParam(':time', $time, PDO::PARAM_STR);
            $result1->bindParam(':whom_id', $whom_id, PDO::PARAM_INT);
            $result1->execute();
        }
    }

    public static function connected($who1, $who2)
    {
        $db = Db::getConnection();

        $sql = 'SELECT * FROM connected WHERE (who_id1 = :who1 AND who_id2 = :who2) OR (who_id1 = :who2 AND who_id2 = :who1)';

        $result = $db->prepare($sql);
        $result->bindParam(':who1', $who1, PDO::PARAM_STR);
        $result->bindParam(':who2', $who2, PDO::PARAM_STR);
        $result->execute();

        $connected = $result->fetch();
        if (!empty($connected))
            return true;
        return false;
    }

    public static function likedBlockedFaked($action, $who_id, $whom_id)
    {
        $db = Db::getConnection();

        $sql = 'SELECT * FROM ' . $action . ' WHERE who_id = :who_id AND whom_id = :whom_id';

        $result = $db->prepare($sql);
        $result->bindParam(':who_id', $who_id, PDO::PARAM_STR);
        $result->bindParam(':whom_id', $whom_id, PDO::PARAM_STR);
        $result->execute();

        $liked = $result->fetch();
        if (!empty($liked))
            return true;
        return false;
    }

    public static function getLocation($username)
    {
        $db = Db::getConnection();

        $sql = 'SELECT * FROM markers WHERE username = :username';

        $result = $db->prepare($sql);
        $result->bindParam(':username', $username, PDO::PARAM_STR);
        $result->execute();

        $result->setFetchMode(PDO::FETCH_ASSOC);

        if (!empty($result))
            return $result->fetch();
        return false;
    }

    public static function addLocation($id, $username, $lat, $lng)
    {
        $db = Db::getConnection();

        if (self::getLocation($username))
            $sql = 'UPDATE markers SET lat = :lat, lng = :lng WHERE username = :username AND user_id = :user_id';
        else
            $sql = 'INSERT INTO markers (user_id, username, lat, lng) VALUES (:user_id, :username, :lat, :lng)';

        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $id, PDO::PARAM_INT);
        $result->bindParam(':username', $username, PDO::PARAM_STR);
        $result->bindParam(':lat', $lat, PDO::PARAM_INT);
        $result->bindParam(':lng', $lng, PDO::PARAM_INT);
        $result->execute();

    }

    public static function checkStatus($username)
    {
        $db = Db::getConnection();

        $sql = 'SELECT status FROM users WHERE username = :username';

        $result = $db->prepare($sql);
        $result->bindParam(':username', $username, PDO::PARAM_STR);
        $result->execute();

        $status = $result->fetchColumn();
//        echo $status;
        if ($status == 0) {
            return false;
        }
        return true;
    }

    public static function sendRestoreEmail($email)
    {

        $db = Db::getConnection();

        $sql = 'SELECT * FROM users WHERE email = :email';

        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();

        if ($result->fetchColumn()) {
            $new_password = substr(md5(time()), 3, 7);
            $mail_subject = "Restore password";
            $mes = "Hi, here is your new password " . $new_password . " for " . $_SERVER['SERVER_NAME'];
            mail($email, $mail_subject, $mes, HEADER);
            $hash_np = md5($new_password);
            $sql = 'UPDATE users SET password = :password WHERE email = :email';

            $result = $db->prepare($sql);
            $result->bindParam(':email', $email, PDO::PARAM_STR);
            $result->bindParam(':password', $hash_np, PDO::PARAM_STR);
            $result->execute();
        }
    }

    public static function saveProfileChanges($data)
    {
        $db = Db::getConnection();

        $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, username = :username,
                email = :email, country = :country, city = :city, age = :age, 
                gender = :gender, sexual_prefer = :sexual_prefer, biography = :biography, interest_list = :interest_list, location = :location, extended_profile = '1'
                WHERE id = :id";

        $location = array('1', '0');
        $result = $db->prepare($sql);
        $result->bindParam(":first_name", $data['first_name'], PDO::PARAM_STR);
        $result->bindParam(":last_name", $data['last_name'], PDO::PARAM_STR);
        $result->bindParam(":username", $data['username'], PDO::PARAM_STR);
        $result->bindParam(":email", $data['email'], PDO::PARAM_STR);
        $result->bindParam(":country", $data['country'], PDO::PARAM_STR);
        $result->bindParam(":city", $data['city'], PDO::PARAM_STR);
        $result->bindParam(":sexual_prefer", $data['sexual_preferences'], PDO::PARAM_STR);
        $result->bindParam(":biography", $data['biography'], PDO::PARAM_STR);
        $result->bindParam(":age", $data['age'], PDO::PARAM_INT);
        $result->bindParam(":id", $_SESSION['userId'], PDO::PARAM_INT);
        $result->bindParam(":gender", $data['gender'], PDO::PARAM_STR);
        if (!stripos($data['list_of_interests'], 'On')) {
            $result->bindParam(":location", $location[1], PDO::PARAM_STR);
        } else {
            $data['list_of_interests'] = substr($data['list_of_interests'], 0, -3);
            $result->bindParam(":location", $location[0], PDO::PARAM_STR);
        }
        $result->bindParam(":interest_list", $data['list_of_interests'], PDO::PARAM_STR);

        $result->execute();

        return true;
    }

    public static function isAuthorized()
    {

        if (isset($_SESSION['userId']))
            return true;
        return false;
    }

    public static function checkLogged()
    {

        if (isset($_SESSION['user']))
            return $_SESSION['user'];
        header('location: /authorization/login');
    }

    public static function checkUserData($username, $password)
    {

        $db = Db::getConnection();

        $sql = 'SELECT * FROM users WHERE username = :username AND password = :password';

        $result = $db->prepare($sql);
        $result->bindParam(':username', $username, PDO::PARAM_INT);
        $password = md5($password);
        $result->bindParam(':password', $password, PDO::PARAM_INT);
        $result->execute();

        $user = $result->fetch();
        if ($user) {
            $userData['id'] = $user['id'];
            $userData['username'] = $user['username'];
            return $userData;
        }
        return false;
    }

    public static function checkName($name)
    {
        if (strlen($name) <= 30 && strlen($name) > 1)
            return true;
        return false;
    }

    public static function checkUserName($username)
    {
        if (strlen($username) <= 8 && strlen($username) > 2)
            return true;
        return false;
    }

    public static function checkPassword($password)
    {
        if (strlen($password) > 4)
            return true;
        return false;
    }

    public static function checkEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL))
            return true;
        return false;
    }

    public static function checkAge($age)
    {

        if ((!ctype_digit($age) || intval($age) < 18 || intval($age) > 150) && $age != "")
            return false;
        return true;
    }

    public static function checkIfExistEmailUsername($email, $username)
    {
        $db = Db::getConnection();

        $sql = 'SELECT COUNT(*) FROM users WHERE email = :email OR username = :username';

        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':username', $username, PDO::PARAM_STR);
        $result->execute();

        if ($result->fetchColumn())
            return true;
        return false;
    }

    public static function register($username, $first, $last, $email, $pass)
    {

        $db = Db::getConnection();

        $sql = 'INSERT INTO users (username, first_name, last_name, email, password, fame_rating, activation_key)
                VALUES (:username, :first_name, :last_name, :email, :password, 100, :activation_key)';

        $result = $db->prepare($sql);
        $result->bindParam(':username', $username, PDO::PARAM_STR);
        $result->bindParam(':first_name', $first, PDO::PARAM_STR);
        $result->bindParam(':last_name', $last, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $pass = md5($pass);
        $result->bindParam(':password', $pass, PDO::PARAM_STR);
        $activation_key = md5(time() . $pass);
        if (strlen($activation_key) > 30)
            $activation_key = substr($activation_key, 0, 30);
        $result->bindParam(':activation_key', $activation_key, PDO::PARAM_STR);

        $mail_subject = "Activate your account";
        $mes = "Please, activate your account clicking on the link http://" . $_SERVER['HTTP_HOST'] . "/authorization/activation/" . $activation_key;
        mail($email, $mail_subject, $mes, HEADER);

        return $result->execute();
    }

}