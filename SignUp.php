<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>SignUp</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/registration.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row mh-100vh">
            <div class="col-10 col-sm-8 col-md-6 col-lg-6 offset-1 offset-sm-2 offset-md-3 offset-lg-0 align-self-center d-lg-flex align-items-lg-center align-self-lg-stretch bg-white p-5 rounded rounded-lg-0 my-5 my-lg-0" id="login-block">
                <div class="m-auto w-lg-75 w-xl-50">
                    <h2 class="text-info font-weight-light mb-5"><i class="fa fa-chevron-up"></i>&nbsp;Sign Up</h2>
                    <form action="Scripts/SignUp.php" method="POST">

                        <?php
                            if(isset($_GET["error"])){
                                $error = $_GET["error"];
                                if($error == "UsedEmail"){
                                    echo "<p style='color: red;'>The Email is Used</p>";
                                }
                                else if($error == "UsedName"){
                                    echo "<p style='color: red';>The Name is Used</p>";
                                }
                                else if($error == "UsedBoth"){
                                    echo "<p style='color: red;'>The name and Email are Used</p>";
                                }
                            }
                        ?>

                        <div class="form-group">
                            <label class="text-secondary">Name</label>
                            <input name="username" class="form-control" type="name" required="">
                        </div>

                        <div class="form-group">
                            <label class="text-secondary">Email</label>
                            <input name="email" class="form-control" type="text" required="" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,15}$" inputmode="email">
                        </div>

                        <div class="form-group">
                            <label class="text-secondary">Password</label>
                            <input name="password" class="form-control" type="password" required="">
                        </div>

                        <button name="submit" class="btn btn-info mt-2" type="submit">Next</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-6 d-flex align-items-end" id="bg-block" style="background-image: url(&quot;assets/img/IMG_8212.png&quot;);background-size: cover;background-position: center;">
                <p class="ml-auto small text-dark mb-2"><em>&nbsp;</em><br></p>
            </div>

        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>