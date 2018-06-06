<?php require_once('includes/common.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <?php include("includes/head.php"); ?>
    <script src="scripts/sample.js"></script>
</head>
<body>

<?php require_once('connection.php'); ?>

<div data-role="page">
 
    <div data-role="header" data-position="fixed">
        <?php include("includes/header.php"); ?>
    </div>

    <div role="main" class="ui-content">
        <form id="sample_form">
            <?php
            $cellCarriers = DB::callProcWithRecordset('CALL GetCellCarriers()');

            if (is_null($cellCarriers)) {
                echo '<p style="color: red; font-style: italic;">GetCellCarriers() query failed</p>' . "\n";
            } else {
                ?>

                <div class="ui-field-contain">
                    <label for="CellCarrier" style="white-space: nowrap;">Mobile Cell Carrier:</label>

                    <select id="CellCarrier" name="CellCarrier" data-native-menu="false" data-inline="false">
                        <option value="0" data-placeholder="true"></option>

                        <?php foreach ($cellCarriers as $row) { ?>
                            <option value="<?php echo $row["CellCarrierID"]; ?>"><?php echo $row["Name"]; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <?php
            }

            $donationTypes = DB::callProcWithRecordset('CALL GetDonationTypes()');

            if (is_null($donationTypes)) {
                echo '<p style="color: red; font-style: italic;">GetDonationTypes() query failed</p>' . "\n";
            } else {
                ?>

                <div class="ui-field-contain">
                    <label for="DonationType" style="white-space: nowrap;">Donation Type:</label>

                    <select id="DonationType" name="DonationType" data-native-menu="false" data-inline="false">
                        <option value="0" data-placeholder="true"></option>

                        <?php foreach ($donationTypes as $row) { ?>
                            <option value="<?php echo $row["DonationTypeID"]; ?>"><?php echo $row["Name"]; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <?php
            }
            ?>

            <div class="ui-field-contain">
                <label for="CompanyName">Company Name:</label>
                <input type="text" id="register_companyName" name="CompanyName" placeholder="Company Name">
            </div>

            <button id="sample_notificationButton" class="ui-btn ui-btn-b ui-corner-all" data-env="<?php echo ENV; ?>" disabled="disabled">Generate Notification(s)</button>

            <div id="register_registerError" class="error" style="margin: 6px 4px;"></div>

            <?php
            DB::closeConnection();
            ?>
        </form>

        <form id="sample_formPassword" style="margin-top: 40px">
            <input type="password" id="sample_password" name="Password" placeholder="password">

            <button id="store_passwordButton" class="ui-btn ui-btn-b ui-corner-all">Store Password</button>
        </form>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php include("includes/footer.php"); ?>
    </div>

</div><!-- /page -->


</body>
</html>