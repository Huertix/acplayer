<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <link href="http://vjs.zencdn.net/5.11/video-js.min.css" rel="stylesheet">
    <link  href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" rel="stylesheet" />
    <link  href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" rel="stylesheet" />
    <link  href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />

    <link  href="css/app.css" rel="stylesheet" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
    <script src="http://vjs.zencdn.net/5.11/video.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script   src="http://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
              integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
              crossorigin="anonymous">

    </script>


    <title>AC-Player</title>
    <meta name="description" content="AC-player">
    <meta name="author" content="huertix">

</head>

<body>
<div class="row">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="title_box"><h3><?= $data['file_title'] ?></h3></div>
            </div>
        </div>
        <div class="row player_panel">
            <div class="col-md-2 left_panel_btns">
                <button type="button" id="full_screen_btn" class="btn btn-info" onClick="fullScreen(this)">
                    <span class="glyphicon glyphicon-fullscreen"></span>
                </button>
                <button type="button" id="suffle_btn" class="btn btn-info" onClick="playSuffle(this)">
                    <span class="glyphicon glyphicon-random"></span>
                </button>
                <button type="button" id="previous_btn" class="btn btn-info" onClick="previousSong(this)">
                    <span class="glyphicon glyphicon-fast-backward"></span>
                </button>
                <button type="button" id="play_btn" class="btn btn-danger" onClick="playSong(this)">
                    <span class="glyphicon glyphicon-stop"></span>
                </button>
                <button type="button" id="next_btn" class="btn btn-info" onClick="nextSong(this)">
                    <span class="glyphicon glyphicon-fast-forward"></span>
                </button>
            </div>
            <div class="col-md-8 video-container">
                <video
                  id="my-player"
                  class="video-js"
                  controls
                  preload="auto"
                  data-setup='{ "inactivityTimeout": 0 }'
                  poster="//vjs.zencdn.net/v/oceans.png"
                  data-setup='{}'>
                    <source src="<?= $data['file_path'] ?>" type="video/mp4"></source>
                    <p class="vjs-no-js">
                        To view this video please enable JavaScript, and consider upgrading to a
                        web browser that
                        <a href="http://videojs.com/html5-video-support/" target="_blank">
                            supports HTML5 video
                        </a>
                    </p>
                </video>
            </div>
            <div class="col-md-2 right_panel_btns">
                <button type="button" id="toggle_dark_btn" class="btn btn-info" onClick="darkScreen()">
                    <span class="glyphicon glyphicon-adjust"></span>
                </button>
                <button type="button" id="media_list_btn" class="btn btn-info" onClick="showMediaList(this)">
                    <span class="glyphicon glyphicon-list"></span>
                </button>
                <button type="button" id="volume_up_btn" class="btn btn-info" onClick="volumeUP(this)">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
                <button type="button" id="volume_down_btn" class="btn btn-info" onClick="volumeDOWN(this)">
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
                <button type="button" id="mute_btn" class="btn btn-info" onClick="volumeMuteToggle(this)">
                    <span class="glyphicon glyphicon-volume-off"></span>
                </button>
            </div>
        </div>
        <div class="row timer_panel">
            <div class="col-md-2 s">

            </div>
            <div class="col-md-8">
                <div id="timer"></div>
            </div>
            <div class="col-md-2 ">

            </div>
        </div>
    </div>

<script>

    var current_song = 0;
    var suffle = false;
    var isDarkScreen = false;

    document.cancelFullScreen = document.webkitCancelFullScreen ||
        document.mozCancelFullScreen || document.cancelFullScreen;

    document.body.requestFullScreen = document.body.webkitRequestFullScreen ||
        document.body.mozRequestFullScreen || document.body.requestFullScreen;

    function isFullScreen() {
        return !!(document.webkitIsFullScreen || document.mozFullScreen ||
        document.isFullScreen); // if any defined and true
    }

    function fullScreenElement() {
        return document.webkitFullScreenElement ||
            document.webkitCurrentFullScreenElement ||
            document.mozFullScreenElement || document.fullScreenElement;
    }


    function toggle_btn_on_touch(btn) {
        btn = $(btn);
        btn.switchClass('btn-info', 'btn-danger', 100);

        setTimeout( function() {
            btn.switchClass('btn-danger', 'btn-info', 100);
        },150);
    }

    function fullScreen(btn) {
        btn = $(btn);
        if(isFullScreen()) {
            btn.switchClass('btn-danger', 'btn-info', 100);
            document.cancelFullScreen();
        }
        else {
            btn.switchClass('btn-info', 'btn-danger', 100);
            document.body.requestFullScreen();
        }
    }

    function darkScreen() {

        if (isDarkScreen) {
            isDarkScreen = false;
            $('.timer_panel').hide();
            $('.player_panel').show();
        }
        else {
            isDarkScreen = true;
            $('.player_panel').hide();
            $('.timer_panel').show();
        }

    }

    function playSong(btn) {
        btn = $(btn);
        console.log(myPlayer);
        console.log(myPlayer.remainingTime());
        console.log(myPlayer.paused());
        console.log(myPlayer.hasStarted());

        if (myPlayer.paused()) {
            myPlayer.play();
            btn.switchClass('btn-danger', 'btn-info', 100);
            btn.children().switchClass('glyphicon-stop', 'glyphicon-play', 100);
        } else {
            myPlayer.pause();
            btn.switchClass('btn-info', 'btn-danger', 100);
            btn.children().switchClass('glyphicon-play', 'glyphicon-stop', 100);
        }
    }

    function playSuffle(btn) {
        suffle = !suffle;

        btn = $(btn);
        if (suffle)
            btn.switchClass('btn-info', 'btn-danger', 100);
        else
            btn.switchClass('btn-danger', 'btn-info', 100);

    }

    function nextSong(btn) {
        toggle_btn_on_touch(btn);

        if (current_song == play_list.length - 1)
            current_song = -1;

        else if (suffle){
            current_song = Math.floor(Math.random() * play_list.length - 1) + 1
        }

        var node = play_list[current_song + 1];

        myPlayer.src({
            "type":"video/mp4",
            "src": node.li_attr.relative_path
        });
        playSong($('#play_btn'));
        current_song = node.list_number;

        $(".title_box").html("<h3>" + node.text + "</h3>");
    }

    function previousSong(btn) {
        toggle_btn_on_touch(btn);


        if  (current_song == 0 )
            current_song = play_list.length;

        else if (suffle){
            current_song = Math.floor(Math.random() * play_list.length - 1) + 1
        }

        var node = play_list[current_song - 1];

        myPlayer.src({
            "type":"video/mp4",
            "src": node.li_attr.relative_path
        });
        playSong($('#play_btn'));
        current_song = node.list_number;

        $(".title_box").html("<h3>" + node.text + "</h3>");
    }

    function showMediaList(btn) {
        toggle_btn_on_touch(btn);
        $('#mediaListModal').modal();
    }

    function volumeUP(btn) {
        toggle_btn_on_touch(btn);
        console.log(myPlayer.volume());
        myPlayer.volume(myPlayer.volume() + 0.05);
    }

    function volumeDOWN(btn) {
        toggle_btn_on_touch(btn);
        console.log(myPlayer.volume());
        myPlayer.volume(myPlayer.volume() - 0.05);
    }

    function volumeMuteToggle(btn) {
        if (myPlayer.volume() <= 0) {
            myPlayer.volume(volume_before_mute);
            var obj = $('#mute_btn');
            console.log(obj);
            obj.switchClass('btn-danger', 'btn-info', 400)
        } else {
            volume_before_mute = myPlayer.volume();
            myPlayer.volume(0);
            $('#mute_btn').switchClass('btn-info', 'btn-danger', 400)
        }
    }

    function startTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('timer').innerHTML =
            h + " : " + m + " : " + s;
        var t = setTimeout(startTime, 500);
    }
    function checkTime(i) {
        if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
        return i;
    }




    $( document ).ready(function() {

	$('#full_screen_btn').click();

        startTime();

        $('.timer_panel').hide();

        $('#timer').on('click', function(e) {
            console.log('ksll');
            if (isDarkScreen) {
                darkScreen();
            }
        });

        videojs("my-player", {
            controlBar: {
                muteToggle: false
            },
            "width": 720,
            "height": 420

        }).ready(function(){
            myPlayer = this;
            myPlayer.on("ended", function(){
                nextSong($('#next_btn'));
            });

        });

        $.ajax({
            url: '/medialist/get_play_list',
        }).done(function(data) {
            play_list = data;
        });

        $('#jstree').jstree({
            'core' : {
                'data' : {
                    'url' : function (node) {
                        console.log(node.id);
                        return node.id === '#' ?
                            '/medialist' :
                            '/medialist/' + node.id;
                    },
                    'data' : function (node) {
                        return {
                            'id' : node.id,
                            'text' : node.text};
                    }
                }
            }
        });


        $('#jstree').on('loaded.jstree', function(e, data) {
            console.log('Tree Loaded');
            // invoked after jstree has loaded
            //$(this).jstree("open_node", $('#/media/Music/a/HappyTogether-MileyCyrus.mp4_anchor');
        });

        $('#jstree').on("changed.jstree", function (e, data) {
            if (data.node.li_attr.isLeaf) {
                myPlayer.src({
                    "type":"video/mp4",
                    "src": data.node.li_attr.relative_path
                });
                myPlayer.play();
                current_song = data.node.li_attr.list_number;
                console.log(current_song);

                $(".title_box").html("<h3>" + data.node.text + "</h3>");
            }

        });

        $('#jstree').on('select_node.jstree', function (e, data) {
            data.instance.toggle_node(data.node);
        });

    });
</script>
    <div = id="mediaListModal" class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <!-- Modal Content-->
            <div class="modal-content">
                <div class="modal-header" style="align-content: center">
                    <h2 class="modal-title">MEDIA BROWSER</h2>
                </div>
                <div class="modal-body">
                    <div class="tree-container">
                        <div id="jstree"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
