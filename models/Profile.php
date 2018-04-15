<?php

class Profile
{

    public static function deletetNotification($id)
    {
        $db = Db::getConnection();

        $sql = "DELETE FROM notifications WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();
    }

    public static function getNotifications($user_id)
    {
        $db = Db::getConnection();

        $sql = "SELECT * FROM notifications WHERE user_id = :userId ORDER BY time DESC";
        $sql1 = "UPDATE notifications SET status = '1' WHERE user_id = :userId";

        $result = $db->prepare($sql);
        $result1 = $db->prepare($sql1);
        $result->bindParam(':userId', $user_id, PDO::PARAM_INT);
        $result1->bindParam(':userId', $user_id, PDO::PARAM_INT);
        $result->execute();
        $result1->execute();

        $result->setFetchMode(PDO::FETCH_ASSOC);

        return $result->fetchAll();
    }

    public static function delPhoto($photoName)
    {
        $db = Db::getConnection();


        $sql = 'SELECT * FROM photos WHERE id = :userId';

        $result = $db->prepare($sql);
        $result->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_INT);
        $result->execute();

        $src = $result->fetch();

        $sql = 'UPDATE photos SET ' . $photoName . ' = NULL';

        $result = $db->prepare($sql);
        $result->execute();

        $sql = 'SELECT * FROM photos WHERE profile_photo = :photoName OR photo1 = :photoName OR photo2 = :photoName OR photo3 = :photoName OR photo4 = :photoName';

        $result = $db->prepare($sql);
        $result->bindParam(':photoName', $photoName, PDO::PARAM_INT);
        $result->execute();

//        $photoExists = $result->fetchColumn();

        if (empty($result) && isset($src[$photoName]) && file_exists(ROOT . "/upload/images/" . $src[$photoName]))
            unlink(ROOT . "/upload/images/" . $src[$photoName]);

        return true;
    }

    public static function uploadNewImage ()
    {
        if (isset($_FILES['file']['type']) && (($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg"))) {

            if ($_FILES['file']['error'] != 0)
                return "Ups, we have an error";
            $file_name = explode('.', $_FILES['file']['name']);
            $extensions = array("jpeg", "jpg", "png");
            $file_extension = end($file_name);
            if (!in_array($file_extension, $extensions))
                return "Invalid type of file";
            $sourcePath = $_FILES['file']['tmp_name'];
            $new_file_name = implode($file_name) . substr(md5(time()), 0, 5) . "." . $file_extension;
            $targetPath = ROOT . "/upload/images/" . $new_file_name;
            move_uploaded_file($sourcePath, $targetPath);

            $photos = self::getUserPhotos($_SESSION['userId']);
            if ($photos != false) {
                foreach ($photos as $key => $photo) {
                    if (!isset($photo) && $key != 'id' && $key != 'profile_photo') {
                        self::photoToDb($new_file_name, $key);
                        break;
                    }
                }
            }
            else {
                self::photoToDb($new_file_name, 'photo1');
            }
            return "Success " . $new_file_name;
        }
        return "Incorrect file";
    }

    public static function uploadProfileImage()
    {
        if (isset($_FILES['file']['type']) && (($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg"))) {

            if ($_FILES['file']['error'] != 0)
                return "Ups, we have an error";
            $file_name = explode('.', $_FILES['file']['name']);
            $extensions = array("jpeg", "jpg", "png");
            $file_extension = end($file_name);
            if (!in_array($file_extension, $extensions))
                return "Invalid type of file";
            $sourcePath = $_FILES['file']['tmp_name'];
            $new_file_name = implode($file_name) . substr(md5(time()), 0, 5) . "." . $file_extension;
            $targetPath = ROOT . "/upload/images/" . $new_file_name;
            move_uploaded_file($sourcePath, $targetPath);
            self::photoToDb($new_file_name, "profile_photo");
            return "Success " . $new_file_name;
        }
        return "Incorrect file";
    }

    public static function getUserPhotos($id)
    {
        $db = Db::getConnection();

        $sql = 'SELECT * FROM photos WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        $result->setFetchMode(PDO::FETCH_ASSOC);

        if (!empty($result))
            return $result->fetch();
        return false;
    }

    public static function photoToDb($filename, $columnname)
    {
        $db = Db::getConnection();

        $sql = 'SELECT * FROM photos WHERE id = :userId';

        $result = $db->prepare($sql);
        $result->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_INT);
        $result->execute();

        $arr = $result->fetch();

        if ($arr) {
            if (isset($arr[$columnname]) && file_exists(ROOT . "/upload/images/" . $arr[$columnname]))
                unlink(ROOT . "/upload/images/" . $arr[$columnname]);

            $sql = 'UPDATE photos SET ' . $columnname . ' = :filename WHERE id = :userId';

            $result = $db->prepare($sql);
            $result->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_INT);
            $result->bindParam(':filename', $filename, PDO::PARAM_STR);
            $result->execute();
        }
        else {
            $sql = 'INSERT INTO photos (id, ' . $columnname . ') VALUES (:userId, :filename)';

            $result = $db->prepare($sql);
            $result->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_INT);
            $result->bindParam(':filename', $filename, PDO::PARAM_STR);
            $result->execute();
        }
    }

    public static function getData($username)
    {
        $db = Db::getConnection();

        $sql = 'SELECT * FROM users WHERE username = :username';

        $result = $db->prepare($sql);
        $result->bindParam(':username', $username, PDO::PARAM_STR);
        $result->execute();

        $result->setFetchMode(PDO::FETCH_ASSOC);

        if (!empty($result))
            return $result->fetch();
        return false;
    }

    public static function checkLogged()
    {

        if (isset($_SESSION['userName'])) {
            return $_SESSION['userName'];
        }
        header('location: /authorization/login/');
    }

    public static function activateAccount($code)
    {
        $db = Db::getConnection();

        $sql = 'SELECT * FROM users WHERE activation_key = :code';

        $result = $db->prepare($sql);
        $result->bindParam(':code', $code, PDO::PARAM_INT);
        $result->execute();

        if (!empty($result)) {
            $sql = 'UPDATE users SET status = 1 WHERE activation_key = :code';

            $result = $db->prepare($sql);
            $result->bindParam(':code', $code, PDO::PARAM_INT);
            $result->execute();
            header('location: /authorization/login/');
        }
        else {
            header("HTTP/1.0 404 Not Found");
            die();
        }
    }
}