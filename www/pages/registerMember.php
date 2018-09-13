<?php require_once('../includes/common.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <?php include($top."includes/head.php"); ?>
    <!-- <script src="<?= $top ?>scripts/register.js"></script> -->
</head>
<body>

<?php require_once($top.'connection.php'); ?>

<div id="registerMember_page" data-role="page">
 
    <div data-role="header" data-position="fixed">
        <?php require_once($top."includes/header.php"); ?>
    </div>

    <div role="main" class="ui-content">
        <?php require_once($top."includes/banner.php"); ?>

        <div class="form" style="max-width: 1080px; margin: 12px auto;">
            <div class="form_headers">
                <h3>MEMBER REGISTRATION</h3>

                <div style="display: flex; justify-content: flex-end;">
                    <?php if ($_SESSION['RegistrationType'] != 'Driver') { ?>
                        <div style="margin-right: 16px;">
                            Company: <b><?= $_SESSION['Company'] ?></b>
                        </div>
                    <?php } ?>
                    <div style="margin-right: 16px;">
                        Type: <b><?= $_SESSION['RegistrationType'] ?></b>
                    </div>
                    <div style="">
                        ZIP: <b><?= $_SESSION['Zip'] ?></b>
                    </div>
                </div>
            </div>

            <form id="registerMember_form">
                <div class="ui-grid-b responsive-grid padded">
                    <div class="ui-block-a">
                        <div class="ui-field-contain">
                            <label for="FirstName">First Name:</label>
                            <input type="text" id="registerMember_FirstName" name="FirstName">
                        </div>
                    </div>
                    <div class="ui-block-b">
                        <div class="ui-field-contain">
                            <label for="LastName">Last Name:</label>
                            <input type="text" id="registerMember_LastName" name="LastName">
                        </div>
                    </div>
                    <div class="ui-block-c">
                        <div class="ui-field-contain">
                            <label for="Email">Email:</label>
                            <input type="text" id="registerMember_Email" name="Email">
                        </div>
                    </div>
                </div>

                <div class="ui-grid-a responsive-grid padded">
                    <div class="ui-block-a">
                        <div class="ui-field-contain">
                            <label for="CellNumber">Cell Number:</label>
                            <input type="text" id="registerMember_CellNumber" name="CellNumber">
                        </div>
                    </div>
                    <div class="ui-block-b">
                        <div class="ui-field-contain">
                            <?php
                            $cellCarriers = DB::callProcWithRecordset('CALL GetCellCarriers()');

                            if (is_null($cellCarriers)) {
                                echo '<p style="color: red; font-style: italic;">couldn\'t get cell carriers</p>' . "\n";
                            } else {
                                ?>
                                <div class="ui-field-contain">
                                    <label for="CellCarrier">Cell Carrier:</label>

                                    <select id="registerMember_CellCarrier" name="CellCarrier" data-native-menu="false" data-inline="false">
                                        <option value="" data-placeholder="true"></option>

                                        <?php foreach ($cellCarriers as $row) { ?>
                                            <option value="<?= $row["CellCarrierID"]; ?>"><?= $row["Name"]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="ui-grid-a responsive-grid padded">
                    <div class="ui-block-a">
                        <div class="ui-field-contain">
                            <label for="Username">Username:</label>
                            <input type="text" id="registerMember_Username" name="Username">
                        </div>
                    </div>
                    <div class="ui-block-b">
                        <div class="ui-field-contain">
                            <label for="Password">Password:</label>
                            <input type="password" id="registerMember_Password" name="Password">
                        </div>
                    </div>
                    <div class="ui-block-c">
                        <div class="ui-field-contain">
                            <label for="ConfirmPassword">Confirm Password:</label>
                            <input type="password" id="registerMember_ConfirmPassword" name="ConfirmPassword">
                        </div>
                    </div>
                </div>

                <button type="submit" id="registerMember_registerButton" class="ui-btn ui-btn-b ui-corner-all ui-btn-inline">Register Member</button>
                <div id="registerMember_registerError" class="errormessage" style="margin: 6px 4px;"></div>
            </form>
        </div>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php require_once($top."includes/footer.php"); ?>
    </div>
</div><!-- /page -->

</body>
</html>