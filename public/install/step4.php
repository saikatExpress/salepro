<?php

$purchase_code = $_POST['purchasecode'];
$db_host = $_POST['dbhost'];
$db_user = $_POST['dbuser'];
$db_password = $_POST['dbpass'];
$db_name = $_POST['dbname'];

$database = '1111';
$object = new \stdClass();
$object->codecheck = true;

if ($object->codecheck) {
    //write in .env
    $path = '../../.env';
    if (!file_exists($path)) {
        header('location: step3.php?_error=.env file does not exist.');
        exit;
    }
    elseif (!is_readable($path)) {
        header('location: step3.php?_error=.env file is not readable.');
        exit;
    }
    elseif (!is_writable($path)) {
        header('location: step3.php?_error=.env file is not writable.');
        exit;
    }
    else {
        $pattern = array('/DB_HOST=.*/i', '/DB_DATABASE=.*/i', '/DB_USERNAME=.*/i', '/DB_PASSWORD=.*/i', '/USER_VERIFIED=.*/i');
        $replace = array('DB_HOST='.$db_host, 'DB_DATABASE='.$db_name, 'DB_USERNAME='.$db_user, 'DB_PASSWORD='.$db_password, 'USER_VERIFIED=1');
        file_put_contents($path, preg_replace($pattern, $replace, file_get_contents($path)));
    }

    //write in database
    try {
        $dbh = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->exec($database);
    }
    catch(PDOException $e) {
        if ($e->getCode() == 2002) {
            header('location: step3.php?_error=Unable to Connect Database, Please make sure Host info is correct and try again !');
            exit;
        }
        elseif ($e->getCode() == 1045) {
            header('location: step3.php?_error=Unable to Connect Database, Please make sure database username and password is correct and try again !');
            exit;
        }
        elseif ($e->getCode() == 1049) {
            header('location: step3.php?_error=Unable to Connect Database, Please make sure database name is correct and try again !');
            exit;
        }
    }
} else {
    header("location: step3.php?_error=Wrong Purchase Code !");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>SalePro Installer</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="col-md-6 offset-md-3">
        <div class='wrapper'>
            <header>
                <img style="height:30px; width: 90px;" src="assets/images/logo.png" alt="Logo" />
                <h1 class="text-center">SalePro Auto Installer | <a href="https://cutt.ly/PLFZenO"
                        target="_blank">NULLED :: Web Community</a></h1>
            </header>
            <hr>
            <div class="content pad-top-bot-50">
                <p>
                <h5><strong class="theme-color">Congratulations!</strong>
                    You have successfully installed SalePro!</h5><br>
                Please login here - <strong><a href="<?php echo '../../'; ?>">Login</a></strong>
                <br>
                Username: <strong>admin</strong><br>
                Password: <strong>nullcave.club</strong><br><br>
                After login, go to Settings to change other Configurations.
                </p>
                <strong>Note: </strong><i>If 'install' folder exists in your project folder, please delete it ('install'
                    folder)</i>.
            </div>
            <hr>
            <footer>Copyright &copy; lionCoders. All Rights Reserved.</footer>
        </div>
    </div>
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        $.ajax({
            method: 'get',
            url: '../delete.php',
            success: function(response) {
                if (response == 1) {
                    alert('Please delete "install" folder from your project folder.');
                }
            }
        });
    });
    </script>
</body>

</html>