<?php

class Chat
{
    public static function setMessage($fromId, $toId, $text, $date)
    {
        $db = Db::getConnection();

        $sql = 'INSERT INTO messages (from_id, to_id, message, time)
                VALUES (:fromId, :toId, :text, :datetime)';

        $result = $db->prepare($sql);
        $result->bindParam(':fromId', $fromId, PDO::PARAM_INT);
        $result->bindParam(':toId', $toId, PDO::PARAM_INT);
        $result->bindParam(':text', $text, PDO::PARAM_STR);
        $result->bindParam(':datetime', $date, PDO::PARAM_STR);
        $result->execute();
    }

    public static function readMessageNotifications($user_id, $from_username)
    {
        $db = Db::getConnection();

        $sql = "UPDATE notifications SET status = '1' WHERE user_id = :user_id AND notification = 'send message to you !' AND from_username = :from_username";

        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $result->bindParam(':from_username', $from_username, PDO::PARAM_STR);
        $result->execute();
    }

    public static function messageNotification($user_id, $from_username, $date)
    {
        $db = Db::getConnection();

        $sql = "INSERT INTO notifications (user_id, from_username, notification, time)
                VALUES (:user_id, :from_username, 'send message to you !', :datetime)";

        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $result->bindParam(':from_username', $from_username, PDO::PARAM_STR);
        $result->bindParam(':datetime', $date, PDO::PARAM_STR);
        $result->execute();
    }

    public static function getMessages($fromId, $toId)
    {
        $db = Db::getConnection();

        $sql = 'SELECT messages.from_id,messages.message,messages.time FROM messages
            WHERE (from_id = :fromId AND to_id = :toId) OR
            (from_id = :toId AND to_id = :fromId) ORDER BY `time`';

        $result = $db->prepare($sql);
        $result->bindParam(':fromId', $fromId, PDO::PARAM_INT);
        $result->bindParam(':toId', $toId, PDO::PARAM_INT);
        $result->execute();

        $result->setFetchMode(PDO::FETCH_ASSOC);

        return $result->fetchAll();
    }
}