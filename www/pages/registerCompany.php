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

        <div style="max-width: 300px; margin: 0 auto 16px;">
            <div style="float: left;">
                Type: <b><?php echo $_SESSION['RegistrationType'] ?></b>
            </div>

            <div style="float: right;">
                ZIP:  <b><?php echo $_SESSION['Zip'] ?></b>
            </div>
        </div>
        <div style="clear: both;"></div>

        <form id="registerCompany_form">
            <div class="ui-field-contain">
                <label for="CompanyName">Name of Organization:</label>
                <input type="text" id="registerCompany_Name" name="CompanyName">
            </div>

            <div class="ui-grid-a responsive-grid padded">
                <div class="ui-block-a">
                    <div class="ui-field-contain">
                        <label for="Address1">Street Address:</label>
                        <input type="text" id="registerCompany_Address1" name="Address1">
                    </div>
                </div>

                <div class="ui-block-b">
                    <div class="ui-field-contain">
                        <label for="Address2">Address Line&nbsp;2:</label>
                        <input type="text" id="registerCompany_Address2" name="Address2">
                    </div>
                </div>
            </div>

            <div class="ui-grid-a responsive-grid padded">
                <div class="ui-block-a">
                    <div class="ui-field-contain reasonable-width-1">
                        <label for="City">City:</label>
                        <input type="text" id="registerCompany_City" name="City">
                    </div>
                </div>

                <div class="ui-block-b">
                    <div class="ui-field-contain">
                        <label for="State">State:</label>
                        <label style="font-weight: bold;">Pennsylvania</label>
                        <input type="hidden" id="registerCompany_State" name="State" value="PA">
                    </div>
                </div>
            </div>

            <input type="hidden" id="registerCompany_Zip" name="Zip" value="<?php echo $_SESSION['Zip'] ?>">

            <div class="ui-grid-a responsive-grid padded">
                <div class="ui-block-a">
                    <div class="ui-field-contain reasonable-width-2">
                        <label for="Phone">Phone:</label>
                        <input type="text" id="registerCompany_Phone" name="Phone">
                    </div>
                </div>

                <div class="ui-block-b">
                </div>
            </div>

            <button id="registerCompany_registerButton" class="ui-btn ui-btn-b ui-corner-all ui-btn-inline">Register Company</button>

            <div id="registerCompany_registerError" class="error" style="margin: 6px 4px;"></div>
        </form>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php include("../includes/footer.php"); ?>
    </div>

    <script src="../scripts/register.js"></script>
</div><!-- /page -->

</body>
</html>