<?php require_once('../includes/common.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Wheels4Meals</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <link href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" rel="stylesheet">
    <link href="../styles/main.css?1" rel="stylesheet" type="text/css" media="all">
</head>
<body>

<div data-role="page">
 
    <div data-role="header" data-position="fixed">
        <?php include("../includes/header.php"); ?>
    </div>

    <div role="main" class="ui-content">
        <?php include("../includes/banner.php"); ?>

        <h3>START COMPANY REGISTRATION</h3>

        <p>Type: <b><?php echo $_SESSION['RegistrationType'] ?></b></p>
        <p>ZIP:  <b><?php echo $_SESSION['Zip'] ?></b></p>

        <div>
            Get company name, address, schedule, etc.
        </div>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php include("../includes/footer.php"); ?>
    </div>

    <script src="../scripts/register.js"></script>
</div><!-- /page -->

</body>
</html>