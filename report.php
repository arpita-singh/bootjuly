<?php
    require_once __DIR__.'/src/bootstrap.php';

    use LearnositySdk\Request\Init;

    $currentUser = explode(',', $_COOKIE['lrnuser']);

    $service = 'items';
    $security = [
           'consumer_key' => 'yis0TYCu7U9V4o7M',
           'domain' => $_SERVER['SERVER_NAME'],
           'user_id' => 'open_web_demo',
       ];
    $secret = '74c5fd430cf1242a527f6223aebd42d30464be22';
    $request = [
        "reports"=> [
            [
                'id' => 'sessions-list',
                'type' => 'sessions-list',
                'limit' => 10,
                'display_user' => true,
                'activities' => [
                    ['id' => '0d2e0df2-285d-4507-86e5-3ca8de0f3fc2', 'name' => 'Movie Trivia'],
                ],
                "render" => false
            ],
            [
                'id' => 'progress-by-tag',
                'type' => 'progress-by-tag',
                'user_id' => $currentUser[0],
                'ui' => 'table',
                'hierarchy_reference' => 'Movie Genre',
            ],
            [
                'id' => 'progress-by-tag-by-user',
                'type' => 'progress-by-tag-by-user',
                'users' => [
                    ['id' => $currentUser[0], 'name' => $currentUser[0]],
                ],
                'hierarchy_reference' => 'Movie Genre',


            ],
        ],
    ];

    // Instantiate the SDK Init class with your security and request data=>
    $Init = new Init($service, $security, $secret, $request);

    // Call the generate() method to retrieve a JavaScript object
    $initOptions = $Init->generate();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Movie Trivia Reporting</title>

        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/LeaderBoard.js"></script>
        <script src="//reports.learnosity.com"></script>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">

    </head>
    <body>

    <div class="container">


        <div class="row">
            <div class="col-md-12 text-center">
                <h3>
                    MOVIE TRIVIA - Leader Board
                </h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-left">
                                Rank
                            </th>
                            <th class="text-left">
                                User
                            </th>
                            <th class="text-center">
                                Rating
                            </th>
                            <th class="text-center">
                                Score
                            </th>
                        </tr>
                    </thead>
                    <tbody id="leaderBoardBody"></tbody>
                </table>
            </div>
        </div>





    <div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
    <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Messages</a></li>
    <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">...</div>
    <div role="tabpanel" class="tab-pane" id="profile">...</div>
    <div role="tabpanel" class="tab-pane" id="messages">...</div>
    <div role="tabpanel" class="tab-pane" id="settings">...</div>
  </div>

</div>


    <span class="learnosity-report" id="sessions-list"></span>
    <span class="learnosity-report" id="progress-by-tag"></span>
    <span class="learnosity-report" id="progress-by-tag-by-user"></span>


<div style="text-align:left"><a href="index.html"><button id="backLogin">Back to login page</button></a></div>

</div>
    <script>
        var leaderBoard = new LeaderBoard();

        var initOptions = <?php echo($initOptions) ?>;
        var eventOptions = {
            dataListener: function(data) {
                    leaderBoard.build(data[0].data);
                    leaderBoard.attach('leaderBoardBody');
                },
        };

        var reportsApp = LearnosityReports.init(initOptions, eventOptions);
        </script>
  </body>
</html>
