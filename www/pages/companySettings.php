<?php require_once('../includes/common.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <?php include($top."includes/head.php"); ?>
</head>
<body>

<?php require_once($top."connection.php"); ?>

<div id="companySettings_page" data-role="page">
 
    <div data-role="header" data-position="fixed">
        <?php require_once($top."includes/header.php"); ?>
    </div>

    <div role="main" class="ui-content">
        <?php include($top."includes/banner.php"); ?>

        <div class="form" style="max-width: 1080px; margin: 0 auto;">
            <div class="form_headers">
                <h3>COMPANY SETTINGS</h3>

                <a href="<?= $top ?>pages/beneficiary.php" data-rel="back">Back to Main Page</a>

                <div style="display: flex; justify-content: flex-end; font-weight: bold;">
                    <?= $_SESSION['Company'] ?>
                </div>
            </div>

            <h4>When is <?= $_SESSION['Company'] ?> typically available to receive donations?</h4>

            <h5 style="margin-bottom: 4px;">Current Schedule</h5>
            <table id="companySettingsSchedule_schedule" class="adminGrid ui-responsive" data-role="table" style="margin-bottom: 16px;">
                <thead>
                    <tr>
                        <th>Mondays</th>
                        <th>Tuesdays</th>
                        <th>Wednesdays</th>
                        <th>Thursdays</th>
                        <th>Fridays</th>
                        <th>Saturdays</th>
                        <th>Sundays</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><div id="companySettingsSchedule_Day2"></div></td>
                        <td><div id="companySettingsSchedule_Day3"></div></td>
                        <td><div id="companySettingsSchedule_Day4"></div></td>
                        <td><div id="companySettingsSchedule_Day5"></div></td>
                        <td><div id="companySettingsSchedule_Day6"></div></td>
                        <td><div id="companySettingsSchedule_Day7"></div></td>
                        <td><div id="companySettingsSchedule_Day1"></div></td>
                    </tr>
                </tbody>
            </table>
            <div id="companySettingsSchedule_Error" class="error" style="margin: 6px 4px;"></div>

            <button id="companySettingsSchedule_AddModeButton" class="ui-btn ui-btn-b ui-corner-all ui-btn-inline">Add a Time Period</button>

            <form id="companySettingsSchedule_form" style="display: none">
                <h5>Add a time period:</h5>

                <div class="inline-form" style="border: 1px solid lightgray;">
                    <div style="flex-grow: 2;">
                        <label for="Day" class="select">Day of the week:</label>

                        <select id="companySettings_Day" name="Day" style="min-width: 200px;" data-native-menu="false" data-inline="false">
                            <option value="0" data-placeholder="true"></option>
                            <option value="2">Mondays</option>
                            <option value="3">Tuesdays</option>
                            <option value="4">Wednesdays</option>
                            <option value="5">Thursdays</option>
                            <option value="6">Fridays</option>
                            <option value="7">Saturdays</option>
                            <option value="1">Sundays</option>
                        </select>
                    </div>

                    <div style="flex-grow: 1;">
                        <label for="Start" class="select">Start time:</label>

                        <select id="companySettings_Start" name="Start" style="min-width: 140px;" data-native-menu="true" data-inline="false">
                            <option value="0" data-placeholder="true"></option>

                            <?php foreach (['am', 'pm'] as $ampm) { ?>
                                <?php for ($x = 0; $x <= 11; $x++) { ?>
                                    <?php
                                        $h24 = ($ampm == 'am' ? $x : $x + 12);
                                        $h = ($x == 0 ? $h = 12 : $h = $x);
                                    ?>
                                    <option value="<?= $h24.":00" ?>"><?= $h.":00 ".$ampm; ?></option>
                                    <option value="<?= $h24.":30" ?>"><?= $h.":30 ".$ampm; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>

                    <div style="flex-grow: 1;">
                        <label for="End" class="select">End time:</label>

                        <select id="companySettings_End" name="End" style="min-width: 140px;" data-native-menu="true" data-inline="false">
                            <option value="0" data-placeholder="true"></option>

                            <?php foreach (['am', 'pm'] as $ampm) { ?>
                                <?php for ($x = 0; $x <= 11; $x++) { ?>
                                    <?php
                                        $h24 = ($ampm == 'am' ? $x : $x + 12);
                                        $h = ($x == 0 ? $h = 12 : $h = $x);
                                    ?>
                                    <option value="<?= $h24.":00" ?>"><?= $h.":00 ".$ampm; ?></option>
                                    <option value="<?= $h24.":30" ?>"><?= $h.":30 ".$ampm; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <button id="companySettingsSchedule_AddButton" class="ui-btn ui-btn-b ui-corner-all ui-btn-inline">Add</button>

                <div id="companySettings_Error" class="error" style="margin: 6px 4px;"></div>
            </form>

            <h4>What types of donations would <?= $_SESSION['Company'] ?> like to receive?</h4>

            <form id="companySettingsDonationTypes_form">
                <div class="ui-field-contain">
                    <fieldset data-role="controlgroup" data-type="horizontal">
                        <?php $donationTypes = DB::callProcWithRecordset('CALL GetDonationTypes('.$_SESSION['CompanyID'].')'); ?>
                        
                        <?php foreach ($donationTypes as $row) { ?>
                            <?php $id = $row["DonationTypeID"]; ?>
                            <input type="checkbox" id="donationType_<?= $id; ?>" value="<?= $id; ?>" name="donationTypes[]" <?php if ($row["CompanyDonationType"]) { echo 'checked="checked"'; } ?>>
                            <label for="donationType_<?= $id; ?>"><?= $row["Name"]; ?></label>
                        <?php } ?>
                    <fieldset>
                </div>
            </form>

            <button id="companySettingsDonationTypes_button" class="ui-btn ui-btn-b ui-corner-all ui-btn-inline">Save Donation Types</button>
            <div id="companySettingsDonationTypes_Error" class="error" style="margin: 6px 4px;"></div>
        </div>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php require_once($top."includes/footer.php"); ?>
    </div>
</div><!-- /page -->

<!-- <script src="<?= $top ?>scripts/company.js"></script> -->

</body>
</html>