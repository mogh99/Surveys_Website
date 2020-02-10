<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>DashBoard</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
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

  <!-- Page Content -->
  <div class="container">

    <!-- Jumbotron Header -->
    <header class="jumbotron my-4">
      <h1 class="display-3">Welcome Again</h1>
      <a href="CreateSurvey.php" class="btn btn-primary btn-lg">Create New Survey</a>
    </header>

    <!-- Page Features -->
    <div id="surveysContainer" class="row text-center">
      <?php
        /*take the survey name and the survey description from the survyor table
        and update the page. set the survey container name to equal the survey name*/
        require_once("Scripts/databaseConnection.php");


        define("SelectSurveys", "SELECT * FROM `$name`");

        if($surveys = mysqli_query($connection, SelectSurveys)){
          if(mysqli_num_rows($surveys) > 0){
            while($survey = mysqli_fetch_array($surveys)){
              echo "<div class='col-lg-3 col-md-6 mb-4'>
                      <div class='card h-100'>
                        <div class='card-body'>
                          <h4 class='card-title'>$survey[0]</h4>
                          <p class='card-text'>$survey[1]</p>
                        </div>
                        <div class='card-footer'>
                          <form action='Scripts/SurveyOptions.php' method='POST'>
                            <button class='btn btn-primary' type='submit' name='statistics'><i class='btn btn-primary fa fa-list-alt'></i></button>
                            <button class='btn btn-primary' type='submit' name='edit'><i class='btn btn-primary fa fa-edit'></i></button>
                            <button class='btn btn-primary' type='submit' name='delete'><i class='btn btn-primary fa fa-trash'></i></button>
                            <input type='hidden' name='surveyName' value='$survey[0]'></input>
                            <input type='hidden' name='surveyDescription' value='$survey[1]'></input>
                          </form>
                        </div>
                      </div>
                    </div> ";
            }
          }
        }
      ?>
      
    </div>
  </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>