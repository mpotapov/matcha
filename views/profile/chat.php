<?php //include ROOT . '/views/layouts/header.php' ?>

    <link rel="stylesheet" href="<?php ROOT ?>/template/profile.css">
    <link rel="stylesheet" href="<?php ROOT ?>/template/chat.css">
    <script src="http://bootstraptema.ru/plugins/jquery/jquery-1.11.3.min.js"></script>

    <div class="container" style="height: 100%">
        <div id="main" style="height: 100%">

            <div class="menu">
                <a href="/chat/contacts/">
                    <div id="backButton" class="back"><i style="margin-top: 11%" class="fa fa-chevron-left"></i> <img
                                src="../../upload/images/<?php echo $chatUserPhoto ?>"
                                draggable="false"/>
                    </div>
                </a>
                <div class="name"><?php echo $chatUserName; ?></div>
            </div>
            <div id="status"></div>
            <ol class="chat">
                <?php foreach ($messages as $message): ?>
                    <?php if ($message['from_id'] == $chatUserId) {
                        echo '<li class="other">
                              <a href="/profile/' . $chatUserName . '/"><div class="avatar"><img src="../../upload/images/' . $chatUserPhoto . '" draggable="false"/></div></a>';
                    } else {
                        echo '<li class="self">
                            <div class="avatar"><img src="../../upload/images/' . $userPhoto . '" draggable="false"/></div>';
                    }
                    ?>
                    <div class="msg">
                        <p><?php echo $message['message']; ?></p>
                        <time><?php echo substr($message['time'], 0, 16); ?></time>
                    </div>
                    </li>

                <?php endforeach; ?>
            </ol>
            <input id="inputMassage" autofocus class="textarea" type="text" placeholder="Type message.."/>
            <button id="sendMassage" class="send"></button>

        </div>
    </div>

    <script>
        $(document).ready(function () {

            var socket = new WebSocket("ws://localhost:8080");
            var status = document.querySelector('#status');

            socket.onopen = function () {
                let date = moment().format("YYYY-MM-DD HH:mm:ss");
                var params = {
                    user_id: <?php echo $_SESSION['userId'] ?>,
                    date: date
                };
                $.post('/set/log_time/', params);
            };

            socket.onclose = function (event) {
                if (event.wasClean) {
                    status.innerHTML = 'Connection lost';
                } else {
                    status.innerHTML = 'Connection lost';
                }
            };

            socket.onmessage = function (event) {
                let message = JSON.parse(event.data);
                if (message.to_user == <?php echo $_SESSION['userId']; ?> && message.from_user == <?php echo $chatUserId;?>)
                {
                    $('.chat').append('<li class="other">' +
                        '<div class="avatar"><img src="../../upload/images/<?php echo $chatUserPhoto?>" draggable="false"/></div>' +
                        '<div class="msg">' +
                        '<p>' + message.text + '</p>' +
                        '<time>' + message.date.substr(0, 16) + '</time> </div> </li>');
                    $(".chat").scrollTop($('.chat')[0].scrollHeight);
                }
            };

            socket.onerror = function (event) {
                status.innerHTML = "Error " + event.message;
            };


            $(".chat").scrollTop($('.chat')[0].scrollHeight);

            $("#inputMassage").keyup(function (event) {
                if (event.keyCode == 13) {
                    $("#sendMassage").click();
                    $(this).val('');
                }
            });

            $("#sendMassage").click(function () {
                let text = $("#inputMassage").val();
                text = text.replace(/</g, "&lt;").replace(/>/g, "&gt;");
                if (text.trim() == '')
                    return;
                let date = moment().format("YYYY-MM-DD HH:mm:ss");
                var message = {
                    from_user: <?php echo $_SESSION['userId'] ?>,
                    to_user: <?php echo $chatUserId; ?>,
                    text: text,
                    date: date
                };
                socket.send(JSON.stringify(message));
                $.post("/chat/send_message/", message, function () {
                    $('.chat').append('<li class="self">' +
                        '<div class="avatar"><img src="../../upload/images/<?php echo $userPhoto?>" draggable="false"/></div>' +
                        '<div class="msg">' +
                        '<p>' + text + '</p>' +
                        '<time>' + message.date.substr(0, 16)  + '</time> </div> </li>');
                    $(".chat").scrollTop($('.chat')[0].scrollHeight);
                })
            })
        })
    </script>

    <script async src="<?php ROOT ?>/template/moment.min.js"></script>

<?php //include ROOT . '/views/layouts/footer.php' ?>