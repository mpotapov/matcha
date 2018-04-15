<?php include ROOT . '/views/layouts/header.php' ?>

    <link rel="stylesheet" href="http://bootstraptema.ru/plugins/2015/bootstrap3/bootstrap.min.css"/>
    <link rel="stylesheet" href="<?php ROOT ?>/template/profile.css">
    <link rel="stylesheet" href="<?php ROOT ?>/template/notifications.css">
    <!--    <link rel="stylesheet" href="http://bootstraptema.ru/plugins/font-awesome/4-4-0/font-awesome.min.css"/>-->
    <script src="http://bootstraptema.ru/plugins/jquery/jquery-1.11.3.min.js"></script>
    <script src="http://bootstraptema.ru/plugins/2015/b-v3-3-6/bootstrap.min.js"></script>
    <link rel="stylesheet" href="<?php ROOT ?>/template/notifications.css">

    <div class="container">
        <div id="main">

            <?php if (!empty($notifications)) :
            foreach ($notifications

            as $notification): ?>
            <?php
            if ($notification['notification'] == 'unliked you !')
                echo '<div class="notification warning">';
            else if ($notification['notification'] == 'checked your profile !')
                echo '<div class="notification danger">';
            else if ($notification['notification'] == 'liked you !')
                echo '<div class="notification success">';
            else if ($notification['notification'] == 'send message to you !')
                echo '<div class="notification info">';
            else if ($notification['notification'] == 'unconnected from you !')
                echo '<div class="notification warning">';
            else if ($notification['notification'] == 'connected with you !')
                echo '<div class="notification success">';
            ?>
            <span class="notification-close" data-id="<?php echo $notification['id'] ?>"}>&times;</span>
            <a href="<?php echo '/profile/' . $notification['from_username'] ?>"><h3 class="notification-title"><?php echo $notification['from_username'] ?></h3></a>
            <p class="notification-message"><?php echo $notification['notification'] ?></p>
        </div>
        <?php endforeach;
        endif; ?>

    </div>
    </div>

    <script>
        $('.notification-close').on('click', notificationDel);

        function notificationDel() {
            let params = {id: $(this).attr('data-id')};
            $.post("/delete/notification/", params);
            $(this)
                .parent('.notification')
                .hide();
        }
    </script>

<?php include ROOT . '/views/layouts/footer.php' ?>