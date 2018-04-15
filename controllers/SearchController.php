<?php

include_once ROOT . '/models/Profile.php';
include_once ROOT . '/models/User.php';
include_once ROOT . '/models/Search.php';

class SearchController
{

//    public function actionAddLocation()
//    {
//        $db = Db::getConnection();
//
//        $sql = 'SELECT * FROM users WHERE id = :username';
//
//        $result = $db->prepare($sql);
//        $result->bindParam(':username', $id, PDO::PARAM_STR);
//        $result->execute();
//
//
//        for ($i = 0; $i < 799; $i++) {
//            $data = Search::getData($i);
//            if ($data == false)
//                continue;
//
//            $sql = 'INSERT INTO markers (username, lat, lng, user_id) VALUES (:username, :lat, :lng, :user_id)';
//
//            $lat = rand(15, 77);
//            $lng = rand(9, 169);
//
//            $result = $db->prepare($sql);
//            $result->bindParam(':username', $data['username'], PDO::PARAM_STR);
//            $result->bindParam(':lat', $lat, PDO::PARAM_INT);
//            $result->bindParam(':lng', $lng, PDO::PARAM_INT);
//            $result->bindParam(':user_id', $data['id'], PDO::PARAM_INT);
//            $result->execute();
//        }
//    }

    public function actionParseMarkers()
    {
        Profile::checkLogged();
        include(ROOT . '/models/markers.php');
    }

    public function actionResearch($page, $order_by)
    {
        Profile::checkLogged();
        $search_type = 'research';
        $filter = Search::makeFilter($_POST);
        $tags = array_splice($filter, 6);

        $previousPage = $page - 1;
        $nextPage = $page + 1;
        $userData = Profile::getData($_SESSION['userName']);
        $userLocation = User::getLocation($_SESSION['userName']);

        $uInfo = Search::getAllSuitableUsers(2, $page * 10, $userLocation, $order_by, $filter, $search_type, $tags);

        $pageCount = Search::pageCount(array_pop($uInfo));
        $countNotif = User::countNotifications($_SESSION['userId']);

        require_once(ROOT . '/views/search/search.php');

        return true;
    }


    public function actionBrowsing($page, $order_by)
    {
        Profile::checkLogged();
        $search_type = 'browsing';
        $filter = Search::makeFilter($_POST);
        $tags = array_splice($filter, 6);

        $previousPage = $page - 1;
        $nextPage = $page + 1;
        $userData = Profile::getData($_SESSION['userName']);
        $userLocation = User::getLocation($_SESSION['userName']);

        if (($userData['gender'] == 'male' && $userData['sexual_prefer'] == 'heterosexual') || ($userData['gender'] == 'female' && $userData['sexual_prefer'] == 'homosexual'))
            $uInfo = Search::getAllSuitableUsers(0, $page * 10, $userLocation, $order_by, $filter, $search_type, $tags);
        else if (($userData['gender'] == 'female' && $userData['sexual_prefer'] == 'heterosexual') || ($userData['gender'] == 'male' && $userData['sexual_prefer'] == 'homosexual'))
            $uInfo = Search::getAllSuitableUsers(1, $page * 10, $userLocation, $order_by, $filter, $search_type, $tags);
        else
            $uInfo = Search::getAllSuitableUsers(2, $page * 10, $userLocation, $order_by, $filter, $search_type, $tags);

        $pageCount = Search::pageCount(array_pop($uInfo));
        $countNotif = User::countNotifications($_SESSION['userId']);

        require_once(ROOT . '/views/search/search.php');

        return true;
    }

}