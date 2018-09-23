<?php require_once('../includes/common.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <?php include($top."includes/head.php"); ?>
</head>
<body>

<?php require_once($top."connection.php"); ?>

<div id="password_page" data-role="page">
 
    <div data-role="header" data-position="fixed">
        <?php require_once($top."includes/header.php"); ?>
    </div>

    <div role="main" class="ui-content">
        <div class="form" style="max-width: 1080px; margin: 0 auto;">
            <div class="form_headers">
                <h3>ACCOUNT SETTINGS</h3>

                <?php if (! isset($_SESSION['ForgotPassword'])) { ?>
                    <a href="<?= $top ?>pages/<?= strtolower($_SESSION['MemberType']) ?>.php">Back to Main Page</a>
                <?php } ?>

                <div style="display: flex; justify-content: flex-end; font-weight: bold;">
                    <?= $_SESSION['MemberName'] ?>
                </div>
            </div>

            <form id="password_form" style="max-width: 400px; margin: 30px auto 0;">
                <?php if (! isset($_SESSION['ForgotPassword'])) { ?>
                    <div class="ui-field-contain">
                        <label for="OldPassword">Old Password:</label>
                        <input type="password" id="password_OldPassword" name="OldPassword">
                    </div>
                <?php } ?>

                <div class="ui-field-contain">
                    <label for="Password">New Password:</label>
                    <input type="password" id="password_Password" name="Password">
                </div>

                <div class="ui-field-contain">
                    <label for="ConfirmPassword">Confirm Password:</label>
                    <input type="password" id="password_ConfirmPassword" name="ConfirmPassword">
                </div>

                <button type="submit" id="password_updateButton" class="ui-btn ui-btn-b ui-corner-all ui-btn-inline">Update Password</button>
                <?php if (! isset($_SESSION['ForgotPassword'])) { ?>
                    <a href="<?= $top ?>pages/accountSettings.php" style="text-align: center;" data-rel="back">Other account settings</a>
                <?php } ?>

                <div id="password_updateError" class="errormessage" style="margin: 6px 4px;"></div>
            </form>
        </div>

        <?php include($top."includes/debug.php"); ?>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php require_once($top."includes/footer.php"); ?>
    </div>
</div><!-- /page -->

</body>
</html>