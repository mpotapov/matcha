<?php include ROOT . '/views/layouts/header.php' ?>

<link href='https://fonts.googleapis.com/css?family=Leckerli+One|Metrophobic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php ROOT ?>/template/contacts.css">

<div class="container">
    <div class="main">
        <div class="wrapper">
            <div class="wrapper_inner">
                <!-- Gallery -->
                <section class="gallery">
                    <!-- Gallery  item -->

                    <?php
                    if (!empty($connectedUsers)) {
                        foreach ($connectedUsers as $uData) {
                            echo '<div class="gallery_item">
                        <span class="gallery_item_preview">
                            <a href="/chat/' . $uData['username'] . '" data-js="1">
                            <img src="../../upload/images/' . $uData['profile_photo'] . '"
                                alt=""/>
                                </a>
                                <a href="/profile/' . $uData['username'] . '">
                            <span>
                                <h3>' . $uData['username'] . '</h3>
                            </span>
                            </a>
                        </span>
                        </div>';
                        }
                    }
                    ?>
                </section>
            </div>
        </div>

    </div>
</div>

<?php include ROOT . '/views/layouts/footer.php' ?>
