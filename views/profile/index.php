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
                                    <strong>Matcha user</strong>
                                </div>
                            </header>
                        </div>
                        <div class="panel-body">
                            <div class="text-center" id="author">
                                <img id="profilePhoto"
                                     src="../../upload/images/<?php if (isset($userPhotos['profile_photo'])) echo $userPhotos['profile_photo']; else echo "no-image.png"; ?>">
                                <?php if ($userData['id'] == $_SESSION['userId']): ?>
                                    <form id="uploadPhoto" style="display: none">
                                        <p id="uploadProfilePhotoError" style="color: red"></p>
                                        <input name="file" type="file" multiple="multiple" accept="image/*">
                                        <input type="submit" value="Upload profile photo">
                                    </form>
                                <?php endif; ?>
                                <h3><?php echo $userData['first_name'] . " " . $userData['last_name']; ?></h3>
                                <p>Fame rating:
                                    <small class="label label-warning"><?php echo $userData['fame_rating'] ?></small>
                                </p>
                                <?php if ($userData['id'] != $_SESSION['userId']) {
                                    if ($likedYou == true)
                                        echo "<p>Like you</p>";
                                    if ($connected == true)
                                        echo "<p id='connected'>Connected</p>";
                                    else
                                        echo "<p id='connected'></p>";
                                    if ($loginUserPhotos['profile_photo'] && $blockedYou == false && $extendedProfile == 1) {
                                        if ($liked == false) {
                                            echo '<button id="like" type="button" class="btn btn-success" data-original-title="" title="">Like</button>';
                                        } else {
                                            echo '<button id="unlike" type="button" class="btn btn-danger" data-original-title="" title="">Unlike</button>';
                                        }
                                    }
                                } ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <?php if ($userData['id'] == $_SESSION['userId']): ?>
                                <ul id="myTab" class="nav nav-pills">
                                    <li id="viewProfile"><a href="#detail" data-toggle="tab">View profile</a></li>
                                    <li class="" id="editProfile"><a href="#contact" data-toggle="tab">Edit profile</a>
                                    </li>
                                </ul>
                            <?php endif; ?>
                            <div id="myTabContent" class="tab-content">
                                <hr>
                                <div class="tab-pane fade active in" id="detail">
                                    <h4>Profile information</h4>
                                    <table class="table">
                                        <tr>
                                            <th>Username</th>
                                            <td><?php echo $userData['username'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Last Activity:</th>
                                            <td><?php echo $logTime ?></td>
                                        </tr>
                                        <tr>
                                            <th>City:</th>
                                            <td><?php echo $userData['city'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Gender:</th>
                                            <td><?php echo $userData['gender'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Age:</th>
                                            <td><?php echo $userData['age'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Sexual preferences:</th>
                                            <td><?php echo $userData['sexual_prefer'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Biography:</th>
                                            <td><?php echo $userData['biography'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>A list of interests:</th>
                                            <td><?php foreach ($userInterestList as $hashtag) echo "#" . $hashtag . " " ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <?php if ($userData['id'] == $_SESSION['userId']): ?>
                                <div class="tab-pane fade" id="contact" style="display: none">
                                    <br>
                                    <p></p>
                                    <form id="editUserInformationForm">
                                        <h4>Edit profile information</h4>
                                        <h5 id="successSave" style="color: green"></h5>
                                        <h5 id="errorSave" style="color: red"></h5>
                                        <table class="table">
                                            <tr>
                                                <td>First name</td>
                                                <td><textarea name="" id="first_name" cols="30" name="first_name"
                                                              rows="2"><?php echo $userData['first_name'] ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Last name</td>
                                                <td><textarea name="" id="last_name" cols="30" name="last_name"
                                                              rows="2"><?php echo $userData['last_name'] ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                                <td><textarea name="" id="email" cols="30" name="email"
                                                              rows="2"><?php echo $userData['email'] ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Username</td>
                                                <td><textarea name="" id="username" cols="30" name="username"
                                                              rows="2"><?php echo $userData['username'] ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Country:</td>
                                                <td><textarea name="" id="country" cols="30" name="country"
                                                              rows="2"><?php echo $userData['country'] ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>City:</td>
                                                <td><textarea name="" id="city" cols="30" name="city"
                                                              rows="2"><?php echo $userData['city'] ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Gender:</td>
                                                <?php $gender = array('none' => 'None', 'male' => 'Male', 'female' => 'Female', 'hermaphrodite' => 'Hermaphrodite', 'agender' => 'Agender', 'travesti' => 'Travesti', 'third_gender' => 'Third gender',) ?>
                                                <td><select name="gender" id="gender">
                                                        <?php foreach ($gender as $g => $k) {
                                                            if ($g == $userData['gender'])
                                                                echo "<option selected value=" . $g . ">" . $k . "</option>";
                                                            else
                                                                echo "<option value=" . $g . ">" . $k . "</option>";
                                                        } ?>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td>Age:</td>
                                                <td><textarea id="age" cols="30" name="age"
                                                              rows="2"><?php echo $userData['age'] ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Sexual preferences:</td>
                                                <?php $preferences = array('none' => 'None', 'asexual' => 'Asexual', 'bisexual' => 'Bisexual', 'heterosexual' => 'Heterosexual', 'homosexual' => 'Homosexual') ?>
                                                <td><select name="sexual_preferences" id="sexual_preferences">
                                                        <?php foreach ($preferences as $prefer => $key) {
                                                            if ($prefer == $userData['sexual_prefer'])
                                                                echo "<option selected value=" . $prefer . ">" . $key . "</option>";
                                                            else
                                                                echo "<option value=" . $prefer . ">" . $key . "</option>";
                                                        } ?>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td>Biography:</td>
                                                <td><textarea name="" id="biography" cols="30" name="biography"
                                                              rows="2"><?php echo $userData['biography'] ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>A list of interests:</td>
                                                <div>
                                                    <td>
                                                        <?php $interest_list = array('Geek', 'IT', 'VapeNation', 'Sport', 'Traveller', 'Bummer', 'Anime', 'Games', 'Social', 'Serials', 'Hokage', 'Music', 'Family', 'Alcohol');
                                                        foreach ($interest_list as $hashtag) {
                                                            if (in_array($hashtag, $userInterestList))
                                                                echo '<label><input value="' . $hashtag . '" type="checkbox" checked>' . $hashtag . '</label><br>';
                                                            else
                                                                echo '<label><input value="' . $hashtag . '" type="checkbox">' . $hashtag . '</label><br>';
                                                        }
                                                        ?>
                                                    </td>
                                                </div>
                                            </tr>
                                            <tr>
                                                <td>Location</td>
                                                <td><?php if ($userData['location'] == 1)
                                                        echo '<input id="location" value="On" type="checkbox" checked>On<br>';
                                                    else
                                                        echo '<input id="location" value="On" type="checkbox" >On<br>'; ?>
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="form-group">
                                            <button id="save_changes" type="button" class="btn btn-success"
                                                    data-original-title=""
                                                    title="">Save changes
                                            </button>
                                        </div>
                                    </form>
                                    <h4>Add new photo</h4>
                                    <form id="uploadNewPhoto">
                                        <p id="uploadPhotoError" style="color: red"></p>
                                        <input name="file" type="file" multiple="multiple" accept="image/*">
                                        <input type="submit" value="Upload New photo">
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-photo">
                <?php if ($userPhotos) {
                    foreach ($userPhotos as $photo => $key) {
                        if (isset($key) && $photo != 'id' && $photo != 'profile_photo') {
                            echo "<img class='user-photo' id='$photo' src='../../upload/images/" . $key . "'>";
                        } else if ($photo != 'id' && $photo != 'profile_photo')
                            echo "<img class='profile-no-image' style='display: none' src='../../upload/images/no-image.png'>";
                    }
                } else {
                    for ($i = 0; $i < 4; $i++) {
                        echo "<img class='profile-no-image' style='display: none' src='../../upload/images/no-image.png'>";
                    }
                }
                ?>
            </div>
            <div id="deletePhotosBox" style="display: none">
                <?php
                echo "<div class='deletePhoto'>";
                if ($userData['id'] == $_SESSION['userId'] && !empty($userPhotos)) {
                    foreach ($userPhotos as $photo => $key) {
                        if (isset($key) && $photo != 'id' && $photo != 'profile_photo') {
                            echo "<a id='deletePhoto$photo' class='photoToDel' href=''>delete</a>";
                        } else if ($photo != 'id' && $photo != 'profile_photo')
                            echo "<a id='deletePhoto$photo' class='photoToDel' href='' style='visibility: hidden'>delete</a>";
                    }
                } else {
                    for ($i = 1; $i < 5; $i++) {
                        echo "<a id='deletePhotophoto$i' class='photoToDel' href='' style='visibility: hidden'>delete</a>";
                    }
                }
                echo "</div>"; ?>
            </div>
            <?php if ($userData['id'] == $_SESSION['userId'] && $userData['location'] == 1)
                echo '<a href="" id="gps" style="display: none">Get current location</a><br><br>' ?>
            <?php if ($userData['location'] == 1)
                echo '<div id="map"></div>';
            else
                echo '<div id="map" style="display: none"></div>';
            if ($userData['id'] != $_SESSION['userId']) {
                if ($faked == false)
                    echo '<br><button id="fakeAccount" type="button" class="btn btn-warning" data-original-title="" title="">Fake account</button>';
                else
                    echo '<br><button id="fakeAccount" type="button" class="btn btn-danger" data-original-title="" title="">Doesn\'t fake</button>';
                if ($blocked == false)
                    echo '<button id="blockUser" type="button" class="btn btn-warning" data-original-title="" title="">Block user</button>';
                else
                    echo '<button id="blockUser" type="button" class="btn btn-danger" data-original-title="" title="">Unlock user</button>';
            }
            ?>
        </div>
    </div>

    </div>
    </div>

    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQuCrCgHtG5STYEIsDGWYEfdNoi8D7YFc&callback=initMap">
    </script>

    <script>

        var map, marker, infoWindow;

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: -34.397, lng: 150.644},
                zoom: 6
            });
            infoWindow = new google.maps.InfoWindow({map: map});

            <?php if ($marker): ?>
            var pos = {
                lat: <?php echo $marker['lat']; ?>,
                lng: <?php echo $marker['lng']; ?>
            };
            placeMarker(pos);
            map.setCenter(pos);
            <?php endif; ?>


            google.maps.event.addListener(map, 'click', function (event) {
                if ($('#contact').length && $('#contact').css('display') != 'none') {
                    if (marker)
                        marker.setMap(null);
                    placeMarker(event.latLng);
                    var params = {
                        user_id: '<?php echo $userData['id']?>',
                        username: '<?php echo $userData['username']?>',
                        lat: event.latLng.lat(),
                        lng: event.latLng.lng()
                    };
                    $.post("/profile/add_new_marker/", params)
                }
            });

            function placeMarker(location) {
                marker = new google.maps.Marker({
                    position: location,
                    map: map
                });
            }
        }

        <?php if ($userData['id'] == $_SESSION['userId']) : ?>

        function gps() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    marker = new google.maps.Marker({
                        position: pos,
                        map: map,
                        animation: google.maps.Animation.DROP,
                        title: '<?php echo $userData['username'] ?>'
                    });
                    map.setCenter(pos);

                    var params = {
                        user_id: '<?php echo $userData['id']?>',
                        username: '<?php echo $userData['username']?>',
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    $.post("/profile/add_new_marker/", params)
                }, function () {
                    handleLocationError(true, infoWindow, map.getCenter());
                });
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }
        }

        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(browserHasGeolocation ?
                'Error: The Geolocation service failed.' :
                'Error: Your browser doesn\'t support geolocation.');
        }

        $('#gps').click(function (e) {
            e.preventDefault();
            if (marker)
                marker.setMap(null);
            gps();
        });

        $(document).ready(function () {

            <?php if (!$marker) echo 'gps()'; ?>

            $("#editProfile").click(function () {
                $("#myTabContent").hide();
                $("#contact").show();
                $("#uploadPhoto").show();
                $(".profile-no-image").show();
                $("#deletePhotosBox").show();
                $("#gps").show();
                if ($('img.user-photo').length === 4) {
                    $('#uploadNewPhoto').hide();
                }
            });

            $("#viewProfile").click(function () {
                $("#myTabContent").show();
                $("#contact").hide();
                $("#uploadPhoto").hide();
                $(".profile-no-image").hide();
                $("#deletePhotosBox").hide();
                $("#gps").hide();
            });

            $("#save_changes").click(function () {

                var hashtags = '';
                $('#editUserInformationForm input:checkbox:checked').each(function () {
                    hashtags += ',' + $(this).val();
                });
                hashtags = hashtags.slice(1);
                var params = {
                    first_name: $("#first_name").val().replace(/</g, "&lt;").replace(/>/g, "&gt;"),
                    last_name: $("#last_name").val().replace(/</g, "&lt;").replace(/>/g, "&gt;"),
                    email: $("#email").val().replace(/</g, "&lt;").replace(/>/g, "&gt;"),
                    username: $("#username").val().replace(/</g, "&lt;").replace(/>/g, "&gt;"),
                    country: $("#country").val().replace(/</g, "&lt;").replace(/>/g, "&gt;"),
                    city: $("#city").val().replace(/</g, "&lt;").replace(/>/g, "&gt;"),
                    gender: $("#gender").val().replace(/</g, "&lt;").replace(/>/g, "&gt;"),
                    age: $("#age").val().replace(/</g, "&lt;").replace(/>/g, "&gt;"),
                    sexual_preferences: $("#sexual_preferences").val().replace(/</g, "&lt;").replace(/>/g, "&gt;"),
                    biography: $("#biography").val().replace(/</g, "&lt;").replace(/>/g, "&gt;"),
                    list_of_interests: hashtags.replace(/</g, "&lt;").replace(/>/g, "&gt;")
                };
                $.post("/profile/save_changes/", params, function (data) {
                    $("#successSave").html("");
                    $("#errorSave").html("");
                    if (data.toString() == "Changes saved")
                        $("#successSave").html(data);
                    else {
                        $("#errorSave").html(data);
                    }
                })
            });

            $("#uploadNewPhoto").on('submit', (function (e) {
                e.preventDefault();
                $("#uploadPhotoError").html("");

                $.ajax({
                    url: "/profile/upload_image/",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {

                        split = data.split(' ');
                        if (split[0] === "Success") {

                            for (i = 1; i < 5; i++) {
                                var photoId = '#photo' + i;
                                if (!$('.user-photo').is(photoId)) {
                                    $("img.profile-no-image").eq(0).replaceWith("<img class='user-photo' id='photo" + i + "' src='../../upload/images/" + data.replace("Success ", "") + "'>");
                                    var deleteId = '#deletePhotophoto' + i.toString();
                                    $(deleteId).css('visibility', 'visible');
                                    break;
                                }
                            }
                            if ($('img.user-photo').length === 4) {
                                $('#uploadNewPhoto').hide();
                            }
                        }
                        else
                            $("#uploadPhotoError").html(data);
                    }
                });
            }));

            $("#uploadPhoto").on('submit', (function (e) {
                e.preventDefault();
                $("#uploadProfilePhotoError").html("");

                $.ajax({
                    url: "/profile/upload_profile_image/",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {

                        split = data.split(' ');
                        if (split[0] === "Success") {
                            $("#profilePhoto").attr("src", "../../upload/images/" + data.replace("Success ", ""));
                        }
                        else
                            $("#uploadProfilePhotoError").html(data);
                    }
                });
            }));

            $(".photoToDel").click(function (e) {
                e.preventDefault();

                $(this).css('visibility', 'hidden');
                var photoId = $(this).attr("id").substr(11);
                var params = {photoName: photoId};
                $.post("/profile/delete_photo/", params, function (data) {
                    var photoId = '#' + data;
                    $(photoId).replaceWith("<img class='profile-no-image' src='../../upload/images/no-image.png'>");
                    if ($('img.user-photo').length < 4) {
                        $('#uploadNewPhoto').show();
                    }
                })
            });

            $("#location").click(function () {
                if ($("#map").css('display') == 'none') {
                    $("#map").show();
                    $("#gps").show();
                }
                else {
                    $("#map").hide();
                    $("#gps").hide();
                }
            });

        });
        <?php else : ?>

        $(document).ready(function () {

            var socket = new WebSocket("ws://localhost:8080");

            <?php if($profileChecked === true):  ?>
            socket.onopen = function () {
                var params = {
                    whom_id: <?php echo $userData['id'] ?>,
                    username: '<?php echo $_SESSION['userName'] ?>',
                    action: 'check'
                };
                socket.send(JSON.stringify(params));
            };
            <?php endif; ?>

            $("#author").on('click', '#like', function () {
                var params = {
                    whom_id: <?php echo $userData['id'] ?>,
                    username: '<?php echo $_SESSION['userName'] ?>',
                    action: 'like'
                };
                socket.send(JSON.stringify(params));
                $.post("/profile/action/like/", params, function (data) {
                    if (data == 'connected')
                        $('#connected').html('Connected');
                    $("#like").replaceWith('<button id="unlike" type="button" class="btn btn-danger" data-original-title="" title="">Unlike</button>');
                });
            });

            $("#author").on('click', '#unlike', function () {
                var params = {
                    whom_id: <?php echo $userData['id'] ?>,
                    username: '<?php echo $_SESSION['userName'] ?>',
                    action: 'unlike'
                };
                socket.send(JSON.stringify(params));
                $.post("/profile/action/unlike/", params, function (data) {
                    if (data == 'unconnected')
                        $('#connected').html('');
                    $("#unlike").replaceWith('<button id="like" type="button" class="btn btn-success" data-original-title="" title="">Like</button>');
                });
            });

            $("#main").on('click', '#blockUser', function () {
                var params = {
                    whom_id: <?php echo $userData['id'] ?>
                };

                if ($(this).hasClass('btn btn-warning')) {
                    $(this).removeClass('btn-warning');
                    $(this).addClass('btn-danger');
                    $(this).html('Unblock user');
                    $.post("/profile/action/block/", params);
                } else {
                    $(this).attr('class', 'btn btn-warning');
                    $(this).html('Block user');
                    $.post("/profile/action/unblock/", params);
                }
            });

            $("#main").on('click', '#fakeAccount', function () {
                var params = {
                    whom_id: <?php echo $userData['id'] ?>
                };

                if ($(this).hasClass('btn btn-warning')) {
                    $(this).removeClass('btn-warning');
                    $(this).addClass('btn-danger');
                    $(this).html('Doesn\'t fake');
                    $.post("/profile/action/fake/", params);
                } else {
                    $(this).attr('class', 'btn btn-warning');
                    $(this).html('Fake account');
                    $.post("/profile/action/unfake/", params);
                }
            })
        });

        <?php endif; ?>
    </script>

<?php include ROOT . '/views/layouts/footer.php' ?>