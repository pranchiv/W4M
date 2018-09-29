<?php require_once('../includes/common.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <?php include($top."includes/head.php"); ?>
    <!-- <script src="<?= $top ?>scripts/register.js"></script> -->
</head>
<body>

<div id="registerCompany_page" data-role="page">
 
    <div data-role="header" data-position="fixed">
        <?php require_once($top."includes/header.php"); ?>
    </div>

    <div role="main" class="ui-content">
        <?php require_once($top."includes/banner.php"); ?>

        <div class="form" style="max-width: 920px; margin: 0 auto;">

            <div class="form_headers">
                <h3>COMPANY REGISTRATION</h3>

                <div style="display: flex; justify-content: flex-end;">
                    <div style="margin-right: 16px;">
                        Type: <b><?= $_SESSION['RegistrationType'] ?></b>
                    </div>

                    <div style="">
                        ZIP: <b><?= $_SESSION['Zip'] ?></b>
                    </div>
                </div>
            </div>

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
                        <div class="ui-field-contain">
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

                <input type="hidden" id="registerCompany_Zip" name="Zip" value="<?= $_SESSION['Zip'] ?>">

                <div class="ui-grid-a responsive-grid padded">
                    <div class="ui-block-a">
                        <div class="ui-field-contain">
                            <label for="Phone">Company Phone:</label>
                            <input type="text" id="registerCompany_Phone" name="Phone">
                        </div>
                    </div>

                    <div class="ui-block-b">
                    </div>
                </div>

                <button type="submit" id="registerCompany_registerButton" class="ui-btn ui-btn-b ui-corner-all ui-btn-inline">Register Company</button>
                <div id="registerCompany_registerError" class="errormessage" style="margin: 6px 4px;"></div>
            </form>

        </div>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php require_once($top."includes/footer.php"); ?>
    </div>
</div><!-- /page -->

</body>
</html>