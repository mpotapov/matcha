<?php

include_once ROOT . '/models/User.php';
include_once ROOT . '/models/Chat.php';
include_once ROOT . '/models/Profile.php';

class ChatController
{
    public function actionSend()
    {
        Chat::setMessage($_POST['from_user'], $_POST['to_user'], $_POST['text'], $_POST['date']);

        Chat::messageNotification($_POST['to_user'], $_SESSION['userName'], $_POST['date']);

        return true;
    }

    public function actionChat($chatUserName)
    {
        Profile::checkLogged();

        $userPhoto = User::getPhoto($_SESSION['userName']);
        $chatUserPhoto = User::getPhoto($chatUserName);
        $chatUserId = User::getId($chatUserName);

        $messages = Chat::getMessages($chatUserId, $_SESSION['userId']);
        Chat::readMessageNotifications($_SESSION['userId'], $chatUserName);

        require_once(ROOT . '/views/profile/chat.php');
        return true;
    }

    public function actionContacts()
    {
        Profile::checkLogged();

        $connectedUsers = User::getConnectedToUser($_SESSION['userId']);
        $countNotif = User::countNotifications($_SESSION['userId']);

        require_once(ROOT . '/views/profile/contacts.php');
        return true;
    }
}