<!DOCTYPE html>
<html>
<head>
    <title>WheelsForMeals</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script src="scripts/sample.js"></script>
    <link href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" rel="stylesheet">
    <link href="styles/main.css?1" rel="stylesheet" type="text/css" media="all">
</head>
<body>

<?php require_once('connection.php'); ?>

<div data-role="page">
 
    <div data-role="header" data-position="fixed">
        include("includes/header.php");
    </div>

    <div role="main" class="ui-content">
        <form id="register_form">
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

            <button id="register_registerButton" class="ui-btn ui-btn-b ui-corner-all">Register Company</button>

            <div id="register_registerError" class="error" style="margin: 6px 4px;"></div>

            <?php
            DB::closeConnection();
            ?>
        </form>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        include("includes/footer.php");
    </div>

</div><!-- /page -->


</body>
</html>