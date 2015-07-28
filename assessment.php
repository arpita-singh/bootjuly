<?php
    require_once __DIR__.'/src/bootstrap.php';

    use LearnositySdk\Request\Init;

    $currentUser = explode(',', $_COOKIE['lrnuser']);

    $service = 'items';
    $security = [
           'consumer_key' => 'yis0TYCu7U9V4o7M',
           'domain' => 'localhost',
           'user_id' => 'demo_student',
       ];
    $secret = '74c5fd430cf1242a527f6223aebd42d30464be22';
    $request = [
        'activity_id' => '56d23693-df43-486a-882b-d7ccd010ada2',
        'user_id' => $currentUser[0],
        'session_id' => $currentUser[1],
        'type' => 'submit_practice',
        'name' => '1234',
        'course_id' => '1234',
        'state' => 'resume',
        'rendering_type' => 'assess',
        'items' => [
            'bootjuly_jh_1',
            'bootjuly_as_2',
            'bootjuly_as_3',
            'bootjuly_jh_4a',
            'bootjuly_jh_4b',
            'bootjuly_as_5',
            'bootjuly_bm_6',
            'bootjuly_bm_7',
            'bootjuly_bm_8',
            'bootjuly_as_9',
            'bootjuly_bm_10',
        ],

        'config' => [
            'ui_style' => 'main',
            'time' => [
                'max_time' => 1500,
                'limit_type' => 'soft',
                'show_pause' => true,
                'show_time' => true,
                'countdown' => 10,
            ],
            'navigation' => [
                'auto_save' => true,
                'toc' => [
                    'show_itemcount' => true,
                ],
                'show_save' => true,
                'show_submit' => true,
                'skip_submit_confirmation' => false,
                'show_title' => true,
                'show_progress' => true,
                'show_fullscreencontrol' => true,
                'show_intro' => true,
                'show_outro' => true,
                'show_prev' => true,
                'show_next' => true,
                'show_itemcount' => false,
                'warning_on_change' => false,
                'scrolling_indicator' => false,
                'scroll_to_top' => true,
                'scroll_to_test' => false,
                'transition' => 'fade',
                'transition_speed' => 400,
                'show_calculator' => false,
                'show_accessibility' => [
                    'show_colourscheme' => false,
                    'show_fontsize' => false,
                    'show_zoom' => false,
                ],
                'show_answermasking' => false,
                'exit_securebrowser' => true,
                'show_acknowledgements' => false,
            ],
            'configuration' => [
                'fontsize' => 'normal',
                'dynamic' => false,
                'idle_timeout' => false,
                'events' => false,
                'preload_audio_player' => false,
                'submit_criteria' => false,
                'onsubmit_redirect_url' => 'report.php',
            ],
            'questions_api_init_options' => [
                'captureOnResumeError' => true,
            ],
            'administration' => false,
            'title' => 'Movie Trivia',
        ],
    ];

    // Instantiate the SDK Init class with your security and request data:
    $Init = new Init($service, $security, $secret, $request);

    // Call the generate() method to retrieve a JavaScript object
    $request = $Init->generate();
?>

<html>
    <head>
    <script src="//items.learnosity.com"></script>
    </head>
    <body>

    <script>
    function getUser(name) {
        var users = [];
        if (localStorage.getItem('users')) {

            // Get users from localStorage
            users = JSON.parse(localStorage.getItem('users'));
        }

        for (var i in users) {
            if (users[i].username == name) {
                return users[i];
            }
        }

        return null;
    };
    function saveUser(user) {
        if (localStorage.getItem('users')) {

            // Get users from localStorage
            users = JSON.parse(localStorage.getItem('users'));
            var matched = false;
            for (var i in users) {
                if (users[i].username == user.username) {
                    matched = true;
                    users[i] = user;
                    break;
                }
            }
            if (!matched) {
                users.push(user);
            }
        }
        else {
            users = [user];
        }

        localStorage.setItem('users', JSON.stringify(users));
        return true;
    };


    var itemsApp;
    var username = '<?php echo $currentUser[0] ?>';
    var sessionId = '<?php echo $currentUser[1] ?>';

    var jh4 = {
        controlDiv: null,
        lockedMessage: null,
        lockedDiv: null,
        playCount: getUser(username).playCount,
        maxPlays: 2,

        incrementPlays: function() {
            this.playCount++;
            this.syncPlays();
        },
        decrementPlays: function() {
            this.playCount--;
            this.syncPlays();
        },
        syncPlays: function() {
            var user = getUser(username);
            user.playCount = this.playCount;
            saveUser(user);
        },
    };

    function setupVideoQuestion() {

        // Link the play control to the video widget.
        var items = itemsApp.getItems();
        var videoId = items.bootjuly_jh_4a.feature_ids[0];
        var videoWrapper = itemsApp.feature(videoId);

        var controlId = items.bootjuly_jh_4a.feature_ids[1];
        var control = itemsApp.feature(controlId);

        var playVideo = function() {
            if (jh4.playCount < jh4.maxPlays) {
                videoWrapper.video.play();
            }
        };
        var pauseVideo = function() {
            videoWrapper.video.pause();
        };
        var resumeVideo = function() {
            videoWrapper.video.resume();
        };

        var lockVideo = function() {
            jh4.controlDiv.style.display = 'none';
            control.off('playback:started', playVideo);
            control.off('playback:paused', pauseVideo);
            control.off('playback:resumed', resumeVideo);
        };

        var unlockVideo = function() {
            jh4.controlDiv.style.display = 'block';
            control.on('playback:started', playVideo);
            control.on('playback:paused', pauseVideo);
            control.on('playback:resumed', resumeVideo);
        };

        var lockQ4 = function() {
            jh4.lockedMessage.style.display='block';
            jh4.lockedDiv.style.display='none';
        };

        var unlockQ4 = function() {
            jh4.lockedMessage.style.display='none';
            jh4.lockedDiv.style.display='block';
        };

        // When the video is complete, unlock the div.
        videoWrapper.on('playback:complete', function() {
            control.stop();
            jh4.incrementPlays();
            unlockQ4();

            if (jh4.playCount >= jh4.maxPlays) {
                lockVideo();
            }
        });

        // Once the DOM is rendered, hide the locked part of q4b.
        setTimeout(function() {
            jh4.controlDiv = document.getElementById('bootjuly_jh_4a_controlDiv');
            jh4.lockedMessage = document.getElementById('bootjuly_jh_4b_lockedMessage');
            jh4.lockedDiv = document.getElementById('bootjuly_jh_4b_lockedDiv');

            if (jh4.playCount > 0) {
                unlockQ4();
            }
            else {
                lockQ4();
            }

            if (jh4.playCount < jh4.maxPlays) {
                unlockVideo();
            }
            else {
                lockVideo();
            }

        }, 0);
    }

    // Initialise the items/assess app.
    var initOptions = <?php echo $request ?>;
    var eventOptions = {
        readyListener: function() {
            setupVideoQuestion();
        },
    };
    itemsApp = LearnosityItems.init(initOptions, eventOptions);
    </script>

    <div id="learnosity_assess"></div>
    </body>
</html>
