<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Statistics</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body><!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <?php
        session_start();
        if(isset($_SESSION["name"])){
          $name = $_SESSION["name"];
          echo "<a class='navbar-brand' style='color: white;'>$name</a>";
        }
      ?>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active">
            <form action="Scripts/SignOut.php" method="POST">
            <input style="background-color: #343a40; border: none;" class="nav-link" type="submit" value="Sign Out" name="submit">
            </form>
        </li></ul>
      </div>
    </div>
  </nav>
    <div class="container">
        <section class="jumbotron my-4">
            <h1 class="display-3">Survey Statistics</h1>
            <div class="card shadow border-left-warning py-2">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col mr-2">

                            <div class="text-uppercase text-warning font-weight-bold text-xs mb-1">
                                <span>Total Respondents</span>
                            </div>

                            <div class="text-dark font-weight-bold h5 mb-0">
                                <?php
                                    if(isset($_GET["surveyName"])){
                                        require_once("Scripts/databaseConnection.php");
                                        $surveyName = $_GET["surveyName"];

                                        define("ShowTables", "SHOW TABLES LIKE '%_$surveyName'");

                                        if($respondents = mysqli_query($connection, ShowTables)){
                                            $respondentsNumber = mysqli_num_rows($respondents);

                                            echo "<span>$respondentsNumber</span>";
                                        }
                                    }
                                ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    
        <section class="jumbotron my-4">
            <div class="table-responsive">
                <input type="text" id="tableSearch" class="form-control" placeholder="Search..">
                <table class="table table-striped table-dark table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <?php
                                if(isset($_GET["surveyName"])){
                                    require_once("Scripts/databaseConnection.php");
                                    $surveyName = $_GET["surveyName"];

                                    define("SelectQuestions", "SELECT `Question` FROM `$surveyName`");

                                    if($questions = mysqli_query($connection, SelectQuestions)){
                                        while($question = mysqli_fetch_array($questions)){
                                            $questionText = $question[0];
                                            echo "<th>$questionText</th>";
                                        }
                                    }
                                }
                            ?>
                        </tr>
                    </thead>
                    <tbody id="infoTable">

                        <?php
                            if(isset($_GET["surveyName"])){
                                require_once("Scripts/databaseConnection.php");
                                $surveyName = $_GET["surveyName"];

                                if($respondents = mysqli_query($connection, ShowTables)){
                                    while($respondent = mysqli_fetch_array($respondents)){
                                        $respondentTableName = $respondent[0];

                                        $selectAnswers = "SELECT `Answer` FROM `$respondentTableName`";

                                        if($answers = mysqli_query($connection, $selectAnswers)){

                                            $respondentName = str_replace("_".$surveyName, "", "$respondentTableName");
                                            
                                            $selectRespondentInformation = "SELECT `Email` FROM `respondents` WHERE `Name` = '$respondentName'";
                                            
                                            $respondentEmail = mysqli_fetch_array(mysqli_query($connection, $selectRespondentInformation))[0];

                                            echo "<td>$respondentName</td>";
                                            echo "<td>$respondentEmail</td>";


                                            while($answer = mysqli_fetch_array($answers)){
                                                $answerText = $answer[0];
                                                echo "<td>$answerText</td>";
                                            }
                                            echo "</tr>";
                                        }

                                    }
                                }
                            }
                        ?>
                        
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/chart.min.js"></script>
    <script src="assets/js/bs-charts.js"></script>
    <script src="assets/js/tableSearch.js"></script>
</body>

</html>