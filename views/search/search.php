<?php include ROOT . '/views/layouts/header.php' ?>

    <link rel="stylesheet" href="http://bootstraptema.ru/plugins/2015/bootstrap3/bootstrap.min.css"/>
    <link rel="stylesheet" href="<?php ROOT ?>/template/profile.css">
    <!--    <link rel="stylesheet" href="http://bootstraptema.ru/plugins/font-awesome/4-4-0/font-awesome.min.css"/>-->
    <script src="http://bootstraptema.ru/plugins/jquery/jquery-1.11.3.min.js"></script>
    <script src="http://bootstraptema.ru/plugins/2015/b-v3-3-6/bootstrap.min.js"></script>

    <div class="container">
        <div id="main">


            <div class="row" id="real-estates-detail">
                <div class="col-lg-4 col-md-4 col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <header class="panel-title">
                                <div class="text-center">
                                    <strong>Search</strong>
                                </div>
                            </header>
                        </div>
                        <div class="panel-body">
                            <div class="text-center" id="author">
                                <form id="filter"
                                      method="post">
                                    <table class="table">
                                        <tr>
                                            <td>Age</td>
                                            <td><input class="filterInput" type="number" name="age1" value="<?php echo $filter['age1'] ?>">
                                                - <input class="filterInput" name="age2" type="number" value="<?php echo $filter['age2'] ?>"
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Localization</td>
                                            <td><input  class="filterInput" name="localization1" type="number"
                                                       value="<?php echo $filter['localization1'] ?>"> - <input
                                                        class="filterInput" name="localization2" type="number"
                                                        value="<?php echo $filter['localization2'] ?>"></td>
                                        </tr>
                                        <tr>
                                            <td>Fame rating</td>
                                            <td><input class="filterInput" name="fame_rating1" type="number"
                                                       value="<?php echo $filter['fame_rating1'] ?>"> - <input class="filterInput"
                                                        name="fame_rating2" type="number"
                                                        value="<?php echo $filter['fame_rating2'] ?>"></td>
                                        </tr>
                                        <tr>
                                            <td>A list of interests:</td>
                                            <div>
                                                <td>
                                                    <?php $interest_list = array('Geek', 'IT', 'VapeNation', 'Sport', 'Traveller', 'Bummer', 'Anime', 'Games', 'Social', 'Serials', 'Hokage', 'Music', 'Family', 'Alcohol');
                                                    foreach ($interest_list as $hashtag) {
                                                        if (isset($tags[$hashtag]))
                                                            echo '<label><input name="' . $hashtag . '" value="' . $hashtag . '" type="checkbox" checked>' . $hashtag . '</label><br>';
                                                        else
                                                            echo '<label><input name="' . $hashtag . '" value="' . $hashtag . '" type="checkbox">' . $hashtag . '</label><br>';
                                                    }
                                                    ?>
                                                </td>
                                            </div>
                                        </tr>
                                    </table>
                                    <div class="form-group">
                                        <input type="submit" value="Browsing" formaction="/search/browsing/0/nosort">
                                        <input type="submit" value="Research" formaction="/search/research/0/nosort">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 col-xs-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Username</th>
                                <th>City</th>
                                <th><a href=""><button  type="submit" form="filter" formaction="/search/<?php echo $search_type . '/' . $page ?>/age/" formmethod="post" >Age</button></a></th>
                                <th><a href=""><button  type="submit" form="filter" formaction="/search/<?php echo $search_type . '/' . $page ?>/distance/" formmethod="post" >Localization</button></a></th>
                                <th><a href=""><button  type="submit" form="filter" formaction="/search/<?php echo $search_type . '/' . $page ?>/fame_rating/" formmethod="post" >Fame rating</button></a></th>
                                <th><a href=""><button  type="submit" form="filter" formaction="/search/<?php echo $search_type . '/' . $page ?>/interest_list/" formmethod="post" >Hashtags</button></a></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($uInfo as $data) {
                                echo '<tr>
                                <td><a href="/profile/' . $data['username'] . '">' . $data['username'] . '</a></td>
                                <td>' . $data['city'] . '</td>
                                <td>' . $data['age'] . '</td>
                                <td>' . intval($data['distance']) . ' km</td>
                                <td>' . $data['fame_rating'] . '</td>
                                <td>' . $data['interest_list'] . '</td>
                            </tr>';
                            } ?>
                            </tbody>
                        </table>
                        <ul class="pager">
                            <?php if ($page != 0)
                                echo '<li style="visibility: hidden" class="previous"><a><button type="submit" form="filter" formaction="/search/' . $search_type . '/' . $previousPage . '/' . $order_by . '" formmethod="post">Previous</button></a></li>';
                            if ($page != $pageCount)
                                echo '<li style="visibility: hidden" class="next"><a><button type="submit" form="filter" formaction="/search/' . $search_type . '/' . $nextPage . '/' . $order_by . '" formmethod="post">Next</button></a></li>'; ?>
                        </ul>
                    </div>
                </div>

            </div>
            <div id="map"></div>
        </div>

    </div>
    </div>

    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQuCrCgHtG5STYEIsDGWYEfdNoi8D7YFc&callback=initMap">
    </script>
    <script>

        $(document).ready(function () {
            $('.next').css('visibility', 'visible');
            $('.previous').css('visibility', 'visible');
        });

        function initMap() {

            var map = new google.maps.Map(document.getElementById('map'), {
                center: new google.maps.LatLng(-33.863276, 151.207977),
                zoom: 12
            });
            var infoWindow = new google.maps.InfoWindow;

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    map.setCenter(pos);
                })
            }

            // Change this depending on the name of your PHP or XML file
            downloadUrl('/search/parse_markers/', function (data) {
                var xml = data.responseXML;
                var markers = xml.documentElement.getElementsByTagName('marker');
                Array.prototype.forEach.call(markers, function (markerElem) {
                    var name = markerElem.getAttribute('name');
                    var point = new google.maps.LatLng(
                        parseFloat(markerElem.getAttribute('lat')),
                        parseFloat(markerElem.getAttribute('lng')));

                    var infowincontent = document.createElement('div');
                    var strong = document.createElement('strong');
                    strong.textContent = name;
                    infowincontent.appendChild(strong);
                    infowincontent.appendChild(document.createElement('br'));

                    var text = document.createElement('a');
                    text.textContent = 'profile';
                    text.href = '/profile/' + name + '/';
                    infowincontent.appendChild(text);
                    var marker = new google.maps.Marker({
                        map: map,
                        position: point,
                    });
                    marker.addListener('click', function () {
                        infoWindow.setContent(infowincontent);
                        infoWindow.open(map, marker);
                    });
                });
            });
        }

        function downloadUrl(url, callback) {
            var request = window.ActiveXObject ?
                new ActiveXObject('Microsoft.XMLHTTP') :
                new XMLHttpRequest;

            request.onreadystatechange = function () {
                if (request.readyState == 4) {
                    request.onreadystatechange = doNothing;
                    callback(request, request.status);
                }
            };

            request.open('GET', url, true);
            request.send(null);
        }

        function doNothing() {
        }
    </script>

<?php include ROOT . '/views/layouts/footer.php' ?>