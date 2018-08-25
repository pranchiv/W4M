<?php require_once('../includes/common.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <?php include($top.'includes/head.php'); ?>
</head>
<body>

<div id="register_page" data-role="page">
 
    <div data-role="header" data-position="fixed">
        <?php include($top.'includes/header.php'); ?>
    </div>

    <div role="main" class="ui-content">
        <?php include($top."includes/banner.php"); ?><!-- Brian's test comment -->

        <form id="register_form">
            <div class="ui-grid-a responsive-grid padded">
                <div class="ui-block-a">
                    <div class="ui-field-contain">
                        <label for="RegistrationType">What role?</label>

                        <select id="register_RegistrationType" name="RegistrationType" data-native-menu="false" data-inline="false">
                            <option value="" data-placeholder="true"></option>
                            <option value="Beneficiary">Beneficiary</option>
                            <option value="Donor">Donor</option>
                            <option value="Driver">Driver</option>
                        </select>
                    </div>
                </div>

                <div class="ui-block-b">
                    <div class="ui-field-contain">
                        <label for="Zip">Zip Code:</label>
                        <input type="text" id="register_Zip" name="Zip" placeholder="Zip Code">
                    </div>
                </div>
            </div>

            <button id="register_registerButton" class="ui-btn ui-btn-b ui-corner-all ui-btn-inline">Start Registration</button>

            <div id="register_registerError" class="error" style="margin: 6px 4px;"></div>
        </form>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php include($top."includes/footer.php"); ?>
    </div>

    <!-- <script src="<?= $top ?>scripts/register.js"></script> -->
</div><!-- /page -->


</body>
</html>