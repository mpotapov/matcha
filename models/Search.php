<?php

class Search
{
//    public static function getData($id)
//    {
//        $db = Db::getConnection();
//
//        $sql = 'SELECT * FROM users WHERE id = :username';
//
//        $result = $db->prepare($sql);
//        $result->bindParam(':username', $id, PDO::PARAM_STR);
//        $result->execute();
//
//        $result->setFetchMode(PDO::FETCH_ASSOC);
//
//
//        if (!empty($result))
//            return $result->fetch();
//        return false;
//    }
//
//    public static function getMarkers()
//    {
//        $db = Db::getConnection();
//
//        $sql = 'SELECT * FROM markers';
//
//        $result = $db->prepare($sql);
//        $result->execute();
//
//        $result->setFetchMode(PDO::FETCH_ASSOC);
//
//        if (!empty($result))
//            return $result;
//        return false;
//    }

//SELECT * FROM users
//WHERE users.username IN
//(SELECT u.username FROM users AS u,markers AS m
//WHERE (SELECT DISTANCE_BETWEEN(50.468433, 30.451862, m.lat, m.lng) < 1000)
//AND u.id = m.user_id)
//AND users.id != 1
//ORDER BY users.fame_rating DESC
//LIMIT 0,10;

    public static function pageCount($count)
    {
        $pageCount = $count / 10;
        if (($pageCount - floor($pageCount)) != 0)
            $pageCount = (int)$pageCount;

        return $pageCount;
    }

    public static function makeFilter($filterData)
    {
        $filter = array('age1' => '0', 'age2' => '150', 'fame_rating1' => '0', 'fame_rating2' => '1000', 'localization1' => '0', 'localization2' => '1000');
        if (!empty($filterData)) {
            foreach ($filterData as $key => $data)
                if (!empty($filterData[$key]))
                    $filter[$key] = $data;
        }
        unset($filter['search']);
        return $filter;
    }

    public static function getAllSuitableUsers($case, $limit, $location, $order_by, $filter, $search_type, $tags)
    {
        $db = Db::getConnection();

        $hashtag = '';
        if (!empty($tags)) {
            foreach ($tags as $tag)
                $hashtag = $hashtag . " AND LOCATE ('" . $tag . "', users.interest_list)";
        }

        if ($order_by == 'nosort' || ($order_by != 'age' && $order_by != 'distance' && $order_by != 'fame_rating' && $order_by != 'interest_list'))
            $order_by = 'CHAR_LENGTH(users.interest_list) DESC, users.fame_rating';


        if ($search_type == 'browsing')
            $dist_sql = 'users.username IN (SELECT u.username FROM users AS u,markers AS m
                  WHERE (SELECT DISTANCE_BETWEEN(:lat, :lng, m.lat, m.lng) < 1000)
                        AND u.id = m.user_id) AND';
        else
            $dist_sql = '';

        switch ($case) {
            case 0:
                $sql = "SELECT *,(SELECT DISTANCE_BETWEEN(:lat, :lng, markers.lat, markers.lng)) distance FROM users,markers
                  WHERE " . $dist_sql . "
                        users.id != :user_id
                        AND users.id = markers.user_id
                        AND NOT EXISTS (SELECT id FROM block WHERE block.who_id = :user_id AND block.whom_id = users.id)
                        AND users.gender != 'male'
                        AND users.age BETWEEN :age1 AND :age2
                        AND (SELECT DISTANCE_BETWEEN(:lat, :lng, markers.lat, markers.lng)) BETWEEN :localization1 AND :localization2
                        AND users.fame_rating BETWEEN :fame_rating1 AND :fame_rating2
                        " . $hashtag . "
                        ORDER BY " . $order_by . " DESC
                        LIMIT :f,10;";
                $sql1 = "SELECT COUNT(*) FROM users,markers
                  WHERE " . $dist_sql . "
                        users.id = markers.user_id
                        AND users.age BETWEEN :age1 AND :age2
                        AND (SELECT DISTANCE_BETWEEN(:lat, :lng, markers.lat, markers.lng)) BETWEEN :localization1 AND :localization2
                        AND users.fame_rating BETWEEN :fame_rating1 AND :fame_rating2
                        AND users.gender != 'male'
                        " . $hashtag . "
                        AND users.id != :user_id";

                break;
            case 1:
                $sql = "SELECT *,(SELECT DISTANCE_BETWEEN(:lat, :lng, markers.lat, markers.lng)) distance FROM users,markers
                  WHERE " . $dist_sql . "
                        users.id != :user_id
                        AND users.id = markers.user_id
                        AND NOT EXISTS (SELECT id FROM block WHERE block.who_id = :user_id AND block.whom_id = users.id)
                        AND users.gender != 'female'
                        AND users.age BETWEEN :age1 AND :age2
                        AND (SELECT DISTANCE_BETWEEN(:lat, :lng, markers.lat, markers.lng)) BETWEEN :localization1 AND :localization2
                        AND users.fame_rating BETWEEN :fame_rating1 AND :fame_rating2
                        " . $hashtag . "
                        ORDER BY " . $order_by . " DESC
                        LIMIT :f,10;";
                $sql1 = "SELECT COUNT(*) FROM users,markers
                  WHERE " . $dist_sql . "
                        users.id = markers.user_id
                        AND users.age BETWEEN :age1 AND :age2
                        AND (SELECT DISTANCE_BETWEEN(:lat, :lng, markers.lat, markers.lng)) BETWEEN :localization1 AND :localization2
                        AND users.fame_rating BETWEEN :fame_rating1 AND :fame_rating2
                        AND users.gender != 'female'
                        " . $hashtag . "
                        AND users.id != :user_id";
                break;
            default:
                $sql = "SELECT *,(SELECT DISTANCE_BETWEEN(:lat, :lng, markers.lat, markers.lng)) distance FROM users,markers
                  WHERE " . $dist_sql . "
                        users.id != :user_id
                        AND users.id = markers.user_id
                        AND NOT EXISTS (SELECT id FROM block WHERE block.who_id = :user_id AND block.whom_id = users.id)
                        AND users.age BETWEEN :age1 AND :age2
                        AND (SELECT DISTANCE_BETWEEN(:lat, :lng, markers.lat, markers.lng)) BETWEEN :localization1 AND :localization2
                        AND users.fame_rating BETWEEN :fame_rating1 AND :fame_rating2
                        " . $hashtag . "
                        ORDER BY " . $order_by . " DESC
                        LIMIT :f,10;";
                $sql1 = "SELECT COUNT(*) FROM users,markers
                  WHERE " . $dist_sql . "
                        users.id = markers.user_id
                        AND users.age BETWEEN :age1 AND :age2
                        AND (SELECT DISTANCE_BETWEEN(:lat, :lng, markers.lat, markers.lng)) BETWEEN :localization1 AND :localization2
                        AND users.fame_rating BETWEEN :fame_rating1 AND :fame_rating2
                        " . $hashtag . "
                        AND users.id != :user_id";
        }

        $result = $db->prepare($sql);
        $result->bindParam(':f', $limit, PDO::PARAM_INT);
        $result->bindParam(':lat', $location['lat'], PDO::PARAM_INT);
        $result->bindParam(':lng', $location['lng'], PDO::PARAM_INT);
        $result->bindParam(':user_id', $location['user_id'], PDO::PARAM_INT);
        $result->bindParam(':age1', $filter['age1'], PDO::PARAM_INT);
        $result->bindParam(':age2', $filter['age2'], PDO::PARAM_INT);
        $result->bindParam(':fame_rating1', $filter['fame_rating1'], PDO::PARAM_INT);
        $result->bindParam(':fame_rating2', $filter['fame_rating2'], PDO::PARAM_INT);
        $result->bindParam(':localization1', $filter['localization1'], PDO::PARAM_INT);
        $result->bindParam(':localization2', $filter['localization2'], PDO::PARAM_INT);
        $result->execute();

        $count = $db->prepare($sql1);
        $count->bindParam(':lat', $location['lat'], PDO::PARAM_INT);
        $count->bindParam(':lng', $location['lng'], PDO::PARAM_INT);
        $count->bindParam(':user_id', $location['user_id'], PDO::PARAM_INT);
        $count->bindParam(':age1', $filter['age1'], PDO::PARAM_INT);
        $count->bindParam(':age2', $filter['age2'], PDO::PARAM_INT);
        $count->bindParam(':fame_rating1', $filter['fame_rating1'], PDO::PARAM_INT);
        $count->bindParam(':fame_rating2', $filter['fame_rating2'], PDO::PARAM_INT);
        $count->bindParam(':localization1', $filter['localization1'], PDO::PARAM_INT);
        $count->bindParam(':localization2', $filter['localization2'], PDO::PARAM_INT);
        $count->execute();

        $result->setFetchMode(PDO::FETCH_ASSOC);
        $count->setFetchMode(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            $result = $result->fetchAll();
            $result[] = $count->fetchColumn();
            return $result;
        }
        return false;
    }

}