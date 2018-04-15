<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="/template/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>

<header class="header-fixed">

    <div class="header-limiter">

        <h1><a href="#">UNIT<span>matcha</span></a></h1>

        <?php $uri = Router::getURI();?>

        <nav>
            <?php if (User::isAuthorized()): ?>
                <a href="/profile/<?php echo $_SESSION['userName'] ?>" <?php if ($uri == 'profile/' . $_SESSION['userName']) echo 'class="selected"' ?>>My
                    profile</a>
                <a href="/chat/contacts/" <?php if (substr($uri, 0, 4) == "chat") echo 'class="selected"' ?>>Chat</a>
                <a href="/search/browsing/0/nosort" <?php if (substr($uri, 0, 6) == "search") echo 'class="selected"' ?>>Search</a>
                <a href="/profile/notifications/" <?php if ($uri == "profile/notifications") echo 'class="selected"' ?> id="notifications">Notifications<?php echo '(' . $countNotif . ')'?></a>
                <a href="/authorization/logout/">Logout</a>
            <?php else: ?>
                <a href="/authorization/login/" <?php if ($uri == "authorization/login") echo 'class="selected"' ?>>Login</a>
                <a href="/authorization/registration/" <?php if ($uri == "authorization/registration") echo 'class="selected"' ?>>Register</a>
            <?php endif; ?>
        </nav>

    </div>

</header>

<div class="header-fixed-placeholder"></div>



