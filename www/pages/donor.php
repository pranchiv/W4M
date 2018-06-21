<?php require_once('../includes/common.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <?php include($top."includes/head.php"); ?>
</head>
<body>

<?php require_once($top."connection.php"); ?>

<div id="donation_page" data-role="page">
    <div data-role="header" data-position="fixed">
        <?php include($top.'includes/header.php'); ?>
    </div>

    <div role="main" class="ui-content">
        <?php include($top."includes/banner.php"); ?>

        <div class="form_headers">
            <h3 style="flex-grow: 4;">Pending Donations</h3>

            <a href="" data-transition="flip" style="flex-grow: 1;">Donation History</a>

            <div style="flex-grow: 1; display: flex; justify-content: flex-end; font-weight: bold;">
                <?= $_SESSION['Company'] ?>
            </div>
        </div>

        <div id="donation_Pending" style="margin-bottom: 32px;"></div>

        <button id="donation_AddModeButton" class="ui-btn ui-btn-b ui-corner-all ui-btn-inline">Submit a Donation</button>

        <form id="donation_form" style="display: none">
            <h5>Submit a Donation:</h5>

            <div class="inline-form" style="border: 1px solid lightgray;">

                <div style="flex-grow: 1;">
                    <label for="donation_pickuptime">Pickup time:&nbsp;&nbsp;<span id="donation_today"></span> (today)</label>

                    <select id="donation_pickuptime" name="PickupTime" style="min-width: 140px;" data-native-menu="true" data-inline="true">
                        <option value="0" data-placeholder="true"></option>

                        <?php
                            $currentDate = date('Y-m-d');
                            $currentHour = date('H');
                            $currentMin = date('i');
                            
                            foreach (['am', 'pm'] as $ampm) {
                                for ($x = 0; $x <= 11; $x++) {
                                    $h = ($x == 0 ? $h = 12 : $h = $x);
                                    $h24 = ($ampm == 'am' ? $x : $x + 12);

                                    if ($currentHour < $h24) {
                                        $do00 = true;
                                        $do30 = true;
                                    } else if ($currentHour == $h24) {
                                        $do00 = false;
                                        $do30 = ($currentMin < 30);
                                    } else {
                                        $do00 = false;
                                        $do30 = false;
                                    }
                                    
                                    if ($do00) {
                                        echo '<option value="'.$currentDate.' '.$h24.':00">'.$h.':00 '.$ampm.'</option>\r\n';
                                    }
                                    if ($do30) {
                                        echo '<option value="'.$currentDate.' '.$h24.':30">'.$h.':30 '.$ampm.'</option>\r\n';
                                    }
                                }
                            }
                        ?>
                    </select>
                </div>

                <div>
                    <label>What types of food?</label>
                    
                    <fieldset data-role="controlgroup" data-type="horizontal">
                        <?php $donationTypes = DB::callProcWithRecordset('CALL GetDonationTypes(null)'); ?>
                        
                        <?php foreach ($donationTypes as $row) { ?>
                            <?php $id = $row["DonationTypeID"]; ?>
                            <input type="checkbox" id="donation_type<?= $id; ?>" value="<?= $id; ?>" name="DonationTypes[]">
                            <label for="donation_type<?= $id; ?>"><?= $row["Name"]; ?></label>
                        <?php } ?>
                    <fieldset>
                </div>                

                <div>
                    <label for="donation_boxes">How many boxes?</label>
                    <input type="number" id="donation_boxes" name="NumBoxes" min="1" max="100" />
                </div>                

                <div>
                    <label for="donation_weight">Approximate weight (in pounds):</label>
                    <input type="number" id="donation_weight" name="Weight" min="0" max="200" />
                </div>                
            </div>

            <button id="donation_AddButton" class="ui-btn ui-btn-b ui-corner-all ui-btn-inline">Donate</button>

            <div id="donation_AddError" class="error" style="margin: 6px 4px;"></div>
        </form>

        <?php include($top."includes/debug.php"); ?>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php include($top."includes/footer.php"); ?>
    </div>
</div><!-- /page -->

</body>
</html>