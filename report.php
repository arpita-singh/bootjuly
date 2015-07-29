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
                'render' => false,
            ],
            [
                "id"=>          "lastscore-bar-1",
                "type"=>         "lastscore-single",
                "ui"=>           "pie",
                "user_id"=>      $currentUser[0],
                "activity_id"=>  "56d23693-df43-486a-882b-d7ccd010ada2"
            ],
            [
                'id' => 'progress-by-tag',
                'type' => 'progress-by-tag',
                'user_id' => $currentUser[0],
                'ui' => 'table',
                'hierarchy_reference' => 'Movie Genre',
            ],
            [
                "id"=>          "report-demo",
                "type"=>         "sessions-summary",
                "user_id"=>     $currentUser[0],
                "session_ids"=>  [
                    $currentUser[1]
                ]
            ]
        ]
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

        <div style="text-align:left"><a href="index.html"><button id="backLogin">Back to login</button></a></div>

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
        </div> <!-- end planning -->

        <div>

          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#totalScore" aria-controls="totalScore" role="tab" data-toggle="tab">Your total  Score </a></li>
            <li role="presentation"><a href="#triviaSummary" aria-controls="triviaSummary" role="tab" data-toggle="tab">Your trivia summary</a></li>
            <li role="presentation"><a href="#genreScore" aria-controls="genreScore" role="tab" data-toggle="tab">Your score by Genre</a></li>

          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="totalScore"><span class="learnosity-report" id="lastscore-bar-1"></span></div>
            <div role="tabpanel" class="tab-pane" id="triviaSummary"><span class="learnosity-report" id="report-demo"></span></div>
            <div role="tabpanel" class="tab-pane" id="genreScore"><span class="learnosity-report" id="progress-by-tag"></span></div>
          </div>

      </div> <!-- end tabs div-->



    </div> <!--end container-->


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
