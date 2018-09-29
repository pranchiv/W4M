<?php require_once('../includes/common.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <?php include($top."includes/head.php"); ?>
</head>
<body>

<?php require_once($top."connection.php"); ?>

<div id="accountSettings_page" data-role="page">
 
    <div data-role="header" data-position="fixed">
        <?php require_once($top."includes/header.php"); ?>
    </div>

    <div role="main" class="ui-content">
        <div class="form" style="max-width: 1080px; margin: 0 auto;">
            <div class="form_headers">
                <h3>ACCOUNT SETTINGS</h3>

                <a href="<?= $top ?>pages/<?= strtolower($_SESSION['MemberType']) ?>.php">Back to Main Page</a>

                <div style="display: flex; justify-content: flex-end; font-weight: bold;">
                    <?= $_SESSION['MemberName'] ?>
                </div>
            </div>

            <form id="accountSettings_form">
                <h4>Login Info</h4>

                <div class="ui-grid-a responsive-grid padded">
                    <div class="ui-block-a">
                        <div class="ui-field-contain">
                            <label for="Username">Username:</label>
                            <input type="text" id="accountSettings_Username" name="Username">
                        </div>
                    </div>
                    <div class="ui-block-b">
                        <div class="ui-field-contain">
                            <a href="<?= $top ?>pages/password.php" style="text-align: center;" data-transition="flip">Change password</a>
                        </div>
                    </div>
                </div>

                <h4>Member Info</h4>

                <div class="ui-grid-b responsive-grid padded">
                    <div class="ui-block-a">
                        <div class="ui-field-contain">
                            <label for="FirstName">First Name:</label>
                            <input type="text" id="accountSettings_FirstName" name="FirstName">
                        </div>
                    </div>
                    <div class="ui-block-b">
                        <div class="ui-field-contain">
                            <label for="LastName">Last Name:</label>
                            <input type="text" id="accountSettings_LastName" name="LastName">
                        </div>
                    </div>
                    <div class="ui-block-c">
                        <div class="ui-field-contain">
                            <label for="Email">Email:</label>
                            <input type="text" id="accountSettings_Email" name="Email">
                        </div>
                    </div>
                </div>

                <div class="ui-grid-a responsive-grid padded">
                    <div class="ui-block-a">
                        <div class="ui-field-contain">
                            <label for="CellNumber">Cell Number:</label>
                            <input type="text" id="accountSettings_CellNumber" name="CellNumber">
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

                                    <select id="accountSettings_CellCarrier" name="CellCarrier" data-native-menu="false" data-inline="false">
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

                <button type="submit" id="accountSettings_updateButton" class="ui-btn ui-btn-b ui-corner-all ui-btn-inline">Update Account Settings</button>
                <span id="accountSettings_updateError" class="errormessage" style="margin: 6px 4px;"></span>
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