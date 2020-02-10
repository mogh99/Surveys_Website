<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Confirmation</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/registration.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row mh-100vh">
            <div class="col-10 col-sm-8 col-md-6 col-lg-6 offset-1 offset-sm-2 offset-md-3 offset-lg-0 align-self-center d-lg-flex align-items-lg-center align-self-lg-stretch bg-white p-5 rounded rounded-lg-0 my-5 my-lg-0" id="login-block">

                <div class="m-auto w-lg-75 w-xl-50">

                    <h2 class="text-info font-weight-light mb-5">
                        <i class="fa fa-check-square"></i>&nbsp;Confirmation
                    </h2>
                    <?php
                        if(isset($_GET["error"])){
                            if($_GET["error"] == "WrongCode"){
                                echo "<p style='color:red;'>Wrong Confrimation Code</p>";
                            }
                        }
                    ?>
                    <form action="Scripts/ConfirmationAnswerSurvey.php" method="POST">
                        <?php
                            if(isset($_GET["answerd"])){
                                echo "<input type='hidden' name='answerd' value='true'>";
                            }
                        ?>
                        <div class="form-group">
                            <label class="text-secondary">Enter The Confirmation Code:</label>
                            <input name="confirmation" class="form-control" type="text" pattern="\d{6}" required="" max="6">
                        </div>
                        <button name="submit" class="btn btn-info mt-2" type="submit">Next</button>
                    </form>
                </div>

            </div>

            <div class="col-lg-6 d-flex align-items-end" id="bg-block" style="background-image: url(&quot;assets/img/4pic3.png&quot;);background-size: cover;background-position: center center;">
                <p class="ml-auto small text-dark mb-2"><br></p>
            </div>

        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>