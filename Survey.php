<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Survey</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body><!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <?php
        session_start();
        if(isset($_SESSION["respondentName"])){
          $name = $_SESSION["respondentName"];
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

    <form action="Scripts/Survey.php" method="POST">
        <div class="container">
            <section class="jumbotron my-4">
                <h1 class="display-3">Answer Survey</h1>
                <div class="card shadow border-left-warning py-2">
                    <div class="card-body">
                        <div class="form-row align-items-center no-gutters">
                            <div class="col mr-2">
                                <div class="text-uppercase text-warning font-weight-bold text-xs mb-1"><span>Survey Name</span></div>
                                <div class='text-dark font-weight-bold h5 mb-0'>
                                <?php
                                    $surveyName = $_SESSION['surveyName'];
                                    if(isset($_SESSION["surveyName"])){
                                        require_once("Scripts/databaseConnection.php");
                                        $surveyName = $_SESSION["surveyName"];

                                        define("NumberOfQuestions", "SELECT * FROM `$surveyName`");
                                        if($rows = mysqli_query($connection, NumberOfQuestions)){
                                            $numberOfRows = mysqli_num_rows($rows);
                                            echo "<span>Number of Questions = $numberOfRows</span>";
                                        }
                                    }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <?php
                if(isset($_SESSION["surveyName"])){
                    require_once("Scripts/databaseConnection.php");
                    //count is used to give the question their IDs
                    $count = 0;
                    //questionNumber is used to give the question thier numbers
                    $questionNumber = 1;

                    //MCQNumber and WrittenNumber is to know how many question for each type
                    $MCQNumber = 0;
                    $WrittenNumber = 0;

                    $surveyName = $_SESSION["surveyName"];
                    define("SelectQuestions", "SELECT `Question`, `Answer1`, `Answer2`, `Answer3`, `Answer4`, `Answer5`, `Answer6` FROM `$surveyName`");

                    if(isset($_GET["answerd"])){
                        if($_GET["answerd"] == "true"){
                            //upload all the answers for the respondent 
                            //that he answerd before

                            $surveyTableName = $_SESSION["surveyTableName"];
                            define("SelectMCQAnswers", "SELECT `Answer` FROM $surveyTableName WHERE Type = 'MCQ'");
                            define("SelectWrittenAnswers", "SELECT `Answer` FROM $surveyTableName WHERE Type = 'Written'");

                            $WrittenAnswers = mysqli_query($connection, SelectWrittenAnswers);
                            echo "<h1>".mysqli_error($connection)."</h1>";

                            //first check if the question is MCQ or Written
                            if($questions = mysqli_query($connection, SelectQuestions)){
                                if(mysqli_num_rows($questions) > 0){
                                    while($question = mysqli_fetch_array($questions)){
                                        //Note if the first Answer in the survey table is null 
                                        //then the question is written question
                                        //else the question is MCQ
                                        $questionType = $question[1];

                                        //the question is Written
                                        if($questionType == null){
                                            $WrittenID = "WrittenID".$count;

                                            $answer = mysqli_fetch_array($WrittenAnswers)[0];

                                            echo "<section>
                                                    <div class='card shadow border-left-warning py-2'>
                                                        <div class='card-body'>
                                                            <h1>Question$questionNumber</h1>
                                                            <h5>
                                                                $question[0]
                                                            </h5>

                                                            <div class='form-check'>
                                                                <input name='$WrittenID' value='$answer' class='form-control' type='text' required=''>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </section>";;

                                            $questionNumber++;
                                            $count++;
                                            $WrittenNumber++;
                                        }

                                        //the question is MCQ
                                        else{
                                            $MCQID = "MCQID".$count;
                                            echo "<section>
                                                <div class='card shadow border-left-warning py-2'>
                                                    <div class='card-body'>
                                                        <h1>Question$questionNumber</h1>
                                                        <p>
                                                            $question[0]
                                                        </p>";

                                            for($i = 1; $i < 7; $i++){
                                                if($question[$i] != ""){
                                                    //ID is the question number and order in the database
                                                    $MCQID = "MCQID".$count;
                                                    $MCQAnswers = mysqli_query($connection, SelectMCQAnswers);
                                                    $checked = true;
                                                    
                                                    while($answer = mysqli_fetch_array($MCQAnswers)){
                                                        if($question[$i] == $answer[0]){
                                                            echo "<div
                                                                class='form-check'>
                                                                <input value='$question[$i]' name='$MCQID' class='form-check-input' type='radio' required='' checked='checked'>
                                                                <label class='form-check-label'>$question[$i]</label>
                                                            </div>";
                                                            $checked = false;
                                                        }  
                                                    }
                                                    if($checked){
                                                        echo "<div
                                                                class='form-check'>
                                                                <input value='$question[$i]' name='$MCQID' class='form-check-input' type='radio' required=''>
                                                                <label class='form-check-label'>$question[$i]</label>
                                                            </div>";    
                                                    }
                                                }
                                            }

                                            echo "      </div>
                                                    </div>
                                                </section>";
                                            $questionNumber++;
                                            $count++;
                                            $MCQNumber++;
                                        }
                                    }
                                }
                            }
                            echo "<input type='hidden' name='edit' value='true'>";
                        }
                    }  
                    else{
                        //create all the question for the respondent
                        if($questions = mysqli_query($connection, SelectQuestions)){
                            if(mysqli_num_rows($questions) > 0){
                                while($question = mysqli_fetch_array($questions)){
                                    //Note if the first Answer in the survey table is null 
                                    //then the question is written question
                                    //else the question is MCQ

                                    $questionType = $question[1];

                                    //the question is Written
                                    if($questionType == null){
                                        $WrittenID = "WrittenID".$count;
                                        echo "<section>
                                                <div class='card shadow border-left-warning py-2'>
                                                    <div class='card-body'>
                                                        <h1>Question$questionNumber</h1>
                                                        <h5>
                                                            $question[0]
                                                        </h5>

                                                        <div class='form-check'>
                                                            <input name='$WrittenID' class='form-control' type='text' required=''>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>";
                                        $questionNumber++;
                                        $count++;
                                        $WrittenNumber++;
                                    }

                                    //the question is MCQ
                                    else{
                                        //There will be some questions with different number
                                        //of answers from 1 to 6 to check for that 
                                        //we check if the answer is empty or not if it is empty
                                        //we break the for loop and stop
                                        //if it is not empty we create the radio button
                                        echo "<section>
                                                <div class='card shadow border-left-warning py-2'>
                                                    <div class='card-body'>
                                                        <h1>Question$questionNumber</h1>
                                                        <p>
                                                            $question[0]
                                                        </p>";
                                        for($i = 1; $i < 7; $i++){
                                            if($question[$i] != ""){
                                                //ID is the question number and order in the database
                                                $MCQID = "MCQID".$count;
                                                echo "<div
                                                        class='form-check'>
                                                        <input value='$question[$i]' name='$MCQID' class='form-check-input' type='radio' required=''>
                                                        <label class='form-check-label'>$question[$i]</label>
                                                    </div>";
                                            }
                                        }

                                        echo "      </div>
                                                </div>
                                            </section>";

                                        $questionNumber++;
                                        $count++;
                                        $MCQNumber++;
                                    }
                                }
                            }
                        }   
                    }
                    echo "<input type='hidden' name='MCQNumber' value='$MCQNumber'>";
                    echo "<input type='hidden' name='WrittenNumber' value='$WrittenNumber'>";
                }
            ?>
            <section class="submitSection">
                <button name="submit" class="btn submitSurvey" type="submit" >
                    <i class="fa fa-send bg-dark border rounded-circle border-primary shadow-lg"></i>
                </button>
            </section>

        </div>
    </form>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>