<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>CreateSurvey</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">

    <script src="assets/js/createQuestion.js" type="text/javascript"></script>
</head>

<body onload="updateQuestionsIDs()"><!-- Navigation -->
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

    <form id="questionsForm" action="Scripts/CreateSurvey.php" method="POST">
        <div class="container">
            <section class="jumbotron my-4">
                <h1 class="display-3">New Survey</h1>

                <div class="card shadow border-left-warning py-2">

                    <div class="card-body" style="text-align: left;">
                      <?php
                        //when edit an existing survey
                        if(isset($_GET["edit"])){
                          $surveyName = $_GET["surveyName"];
                          $surveyDescription = $_GET["surveyDescription"];

                          echo "<label>Survey Name</label>";
                          echo "<input name='surveyName' class='form-control' type='text' readonly value='$surveyName'>";

                          echo "<label>Survey Description</label>";
                          echo "<input name='description' class='form-control' type='text' readonly value='$surveyDescription'>";
                        
                        }
                        //when create new survey
                        else{
                            echo "<label>Survey Name</label>";
                            echo "<input name='surveyName' class='form-control' type='text' required=''>";

                            echo "<label>Survey Description</label>";
                            echo "<input name='description' class='form-control' type='text' required=''>";
                        }
                      ?>
                    </div>

                    <?php
                      if(isset($_GET["error"])){
                          $error = $_GET["error"];
                          if($error ==  "UsedSurveyName"){
                            echo "<p style='color: red;'>Used Survey Name use another one</p>";
                          }
                          elseif($error == "NoQuestions"){
                            echo "<p style='color: red;'>No Question Created</p>";
                          }
                      }
                    ?>

                    <?php
                      //create the button to edit survey
                      if(isset($_GET["edit"])){
                        echo "<button name='submitEdit' class='btn btn-success' type='Submit'>Submit</button>";

                      }
                      //create the button to create new survey
                      else{
                        echo "<button name='submitNew' class='btn btn-success' type='Submit'>Submit</button>";
                      }

                    ?>

                </div>

            </section>
        </div>
        <input type="hidden" name="MCQNumber" value="0">
        <input type="hidden" name="WrittenNumber" value="0">

        <?php
          //recreate all the questions Written and MCQ with all 
          //the data and information
          if(isset($_GET["edit"])){

            $count = 0;
            $MCQNumber = 0;
            $WrittenNumber = 0;

            require_once("Scripts/databaseConnection.php");
            
            $surveyName = $_GET["surveyName"];

            define("SelectQuestions", "SELECT `Question`, `Answer1`, `Answer2`, `Answer3`, `Answer4`, `Answer5`, `Answer6` FROM `$surveyName`");

            if($questions = mysqli_query($connection, SelectQuestions)){
              if(mysqli_num_rows($questions) > 0){
                while($question = mysqli_fetch_array($questions)){
                  //Note if the first Answer in the survey table is null 
                  //then the question is written question
                  //else the question is MCQ

                  $questionType = $question[1];

                  //$count is used to set the id of the questions containers
                  //the question is Written
                  if($questionType == null){ 
                    $writtenID = "Writtenid$count";
                    echo "<div class='container' type='Written' id='$count'>
                            <div class='card shadow border-left-warning py-2'>
                              <div class='card-body'>
                                <label>Question</label>
                                <input class='form-control' type='text' name='$writtenID' value='$question[0]'>
                                <div style='font-size: 50px; text-align: right;'>
                                  <i class='icon ion-trash-b' style='color:rgb(115,62,147)' onclick='deleteQuestion($count)'></i>
                                </div>
                              </div>
                            </div>
                          </div>";
                    $WrittenNumber++;
                  }

                  //the question is MCQ
                  else{
                    echo "<div class='container' type='MCQ' id='$count'>
                            <div class='card shadow border-left-warning py-2'>
                              <div class='card-body'>
                                <label>Question</label>
                                <input class='form-control' type='text' name='MCQ0id$count' value='$question[0]'>
                                <label>Answer1</label>
                                <input class='form-control' type='text' name='MCQ1id$count' value='$question[1]'>
                                <label>Answer2</label>
                                <input class='form-control' type='text' name='MCQ2id$count' value='$question[2]'>
                                <label>Answer3</label>
                                <input class='form-control' type='text' name='MCQ3id$count' value='$question[3]'>
                                <label>Answer4</label>
                                <input class='form-control' type='text' name='MCQ4id$count' value='$question[4]'>
                                <label>Answer5</label>
                                <input class='form-control' type='text' name='MCQ5id$count' value='$question[5]'>
                                <label>Answer6</label>
                                <input class='form-control' type='text' name='MCQ6id$count' value='$question[6]'>
                                <div style='font-size: 50px; text-align: right;'>
                                  <i class='icon ion-trash-b' style='color:rgb(115,62,147)' onclick='deleteQuestion($count)'></i>
                                </div>
                              </div>
                            </div>
                          </div>";
                    $MCQNumber++;
                  }
                  $count++;
                }
              }
            }
          }
        ?>
    </form> 

    <div class="container addQuestionContainer">

      <i id="addQuestion" class="icon ion-plus-circled addIcon"></i>

      <select id="questionType">
        <option name="none" selected disabled hidden>Select Question Type</option>
        <option>Multiple Choice Question</option>
        <option>Written Question</option>
      </select>

      <label id="select_error" style="color: red; font-size: 15px;"></label>

    </div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/createQuestion.js"></script>
</body>

</html>