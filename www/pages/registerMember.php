<?php require_once('../includes/common.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <?php include($top."includes/head.php"); ?>
</head>
<body>

<div data-role="page">
 
    <div data-role="header" data-position="fixed">
        <?php include($top."includes/header.php"); ?>
    </div>

    <div role="main" class="ui-content">
        <?php include($top."includes/banner.php"); ?>

        <h3>START MEMBER REGISTRATION</h3>

        <p>Type: <b><?php echo $_SESSION['RegistrationType'] ?></b></p>

        <div>
            Get Name, Cell, etc.
        </div>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php include($top."includes/footer.php"); ?>
    </div>

    <script src="<?= $top ?>scripts/register.js"></script>
</div><!-- /page -->

</body>
</html>