<?php
    require_once __DIR__ .'/src/bootstrap.php';

    use LearnositySdk\Request\Init;

    $currentUser = explode(",", $_COOKIE['lrnuser']);

    $service = 'items';
    $security = [
           'consumer_key' => 'yis0TYCu7U9V4o7M',
           'domain'       => 'localhost'
       ];
    $secret = '74c5fd430cf1242a527f6223aebd42d30464be22';
    $request = [
        "reports"=> [
            [
                "id" => "report-1",
                "type"=> "sessions-list",
                "limit"=> 10,
                "display_user" => true,
                "activities"=> [
                    ["id"=> "56d23693-df43-486a-882b-d7ccd010ada2", "name"=> "Movie Trivia"],
                ]
                // "users"=> [
                //     [
                //         "id"=> $currentUser[0], "name"=> $currentUser[0]
                //     ]
                // ]
            ],
            [
                "id" =>    "report-2",
                "type" =>  "progress-by-tag-by-user",
                "users" => [
                     "id" => $currentUser[0], "name"=> $currentUser[0]
                ],
                "hierarchy_reference"=> "Movie Genre",
                // "tag_hierarchy_path"=>  [
                //     [
                //         "type"=> "questiontype",
                //         "name"=> "clozeassociation"
                //     ]
                // ]
            ]
        ]
    ];

    // Instantiate the SDK Init class with your security and request data:
    $Init = new Init($service, $security, $secret, $request);

    // Call the generate() method to retrieve a JavaScript object
    $Request = $Init->generate();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Movie Trivia Reporting</title>
    <script src="//reports.learnosity.com"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

  </head>
  <body>

    <div class="container">
	<div class="row text-center">
		<div class="col-md-12 text-center">
			<h3>
				MOVIE TRIVIA - Leader Board
			</h3>
			<table class="table">
				<thead>
					<tr>
						<th class="text-center">
							Rank
						</th>
						<th class="text-center">
							UserName
						</th>
						<th class="text-center">
							Score
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							1
						</td>
						<td>
							Jordan
						</td>

						<td>
							15/15
						</td>
					</tr>
					<tr class="active">
						<td>
							2
						</td>
						<td>
						Brody
						</td>

						<td>
							12/15
						</td>
					</tr>
					<tr class="success">
						<td>
							3
						</td>
						<td>
							Dennis
						</td>

						<td>
							5/15
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div style="padding-bottom:30px;" class="container text-center"><a href="/bootjuly"><button id="backLogin">Back to login page</button></a></div>

<span class="learnosity-report" id="report-1"></span>

<span class="learnosity-report" id="report-2"></span>



    <script>
      var reportsApp = LearnosityReports.init(<?php echo($Request) ?>);
    </script>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>