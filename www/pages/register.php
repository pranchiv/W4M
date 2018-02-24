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
        <?php include("../includes/footer.php"); ?>
    </div>

    <script src="../scripts/register.js"></script>
</div><!-- /page -->


</body>
</html>