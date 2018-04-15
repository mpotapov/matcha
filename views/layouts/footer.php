<footer class="footer-basic-centered">

    <p class="footer-company-motto">UNIT Factory</p>

    <p class="footer-company-name">mpotapov &copy; 2018</p>

</footer>
<script async src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script async src="<?php ROOT ?>/template/moment.min.js"></script>

<script>

    $(document).ready(function () {

        <?php if(User::isAuthorized()): ?>
        var socket = new WebSocket("ws://localhost:8080");

        socket.onopen = function () {
            let date = moment().format("YYYY-MM-DD HH:mm:ss");
            var params = {
                user_id: <?php echo $_SESSION['userId'] ?>,
                date: date
            };
            $.post('/set/log_time/', params);
        };

        socket.onclose = function (event) {};

        socket.onmessage = function (event) {
            let message = JSON.parse(event.data);
                if (message.whom_id == <?php echo $_SESSION['userId']; ?> || message.to_user == <?php echo $_SESSION['userId']; ?>) {
                    let n = parseInt($('#notifications').text().substr(14), 10);
                    if (isNaN(n))
                        n = '1';
                    else
                        n++;
                    n = '(' + n + ')';
                    $('#notifications').text('Notifications' + n);
                }
        };

        <?php endif; ?>

        var showHeaderAt = 150;

        var win = $(window),
            body = $('body');

        if (win.width() > 600) {

            win.on('scroll', function (e) {

                if (win.scrollTop() > showHeaderAt) {
                    body.addClass('fixed');
                }
                else {
                    body.removeClass('fixed');
                }
            });

        }

    });

</script>
</body>

</html>