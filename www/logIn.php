<?php require_once('includes/common.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <title>WheelsForMeals</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script src="scripts/jquery.toast.min.js"></script>
    <script src="scripts/sample.js"></script>
    <link href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" rel="stylesheet">
    <link href="styles/jquery.toast.min.css" rel="stylesheet" type="text/css">
    <link href="styles/main.css?1" rel="stylesheet" type="text/css" media="all">
</head>
<body>

<?php require_once('connection.php'); ?>

<div data-role="page">
 
    <div data-role="header" data-position="fixed">
        <?php include("includes/header.php"); ?>
    </div>

    <div role="main" class="ui-content">
        <form id="sample_formLogin">
            <input type="text" id="sample_username" name="Username" placeholder="username">
            <input type="password" id="sample_password" name="Password" placeholder="password">

            <button id="loginButton" class="ui-btn ui-btn-b ui-corner-all">Log In</button>

            <div id="loginError" class="error" style="margin: 6px 4px;"></div>
        </form>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php include("includes/footer.php"); ?>
    </div>

</div><!-- /page -->


</body>
</html>