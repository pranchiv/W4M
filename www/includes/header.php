<div class="header">
    <a href="<?= $root ?>" data-ajax="false"><img src="<?= $root ?>/images/logo.svg" style="height:40px;" /></a>

    <?php if (! isset($_SESSION['ForgotPassword'])) { ?>
        <?php if (isset($_SESSION['MemberTypeID']) && $_SESSION['MemberTypeID'] == 1) { ?>
            <div class="menu" style="flex-grow: 1;">
                <a href="<?= $top ?>pages/admin.php" data-transition="flip">Accounts</a>
                <a href="<?= $top ?>pages/adminDonations.php" data-transition="flip">Donations</a>
                <a href="<?= $top ?>pages/donationHistory.php" data-transition="flip">Donation History</a>
            </div>
        <?php } ?>

        <?php if (isset($_SESSION['MemberID'])) { ?>
            <div class="account_stuff">
                <a href="<?= $top ?>pages/accountSettings.php" data-transition="flip" class="ui-shadow ui-corner-all ui-icon-gear ui-btn-icon-notext ui-btn-icon-right account_settings_icon"></a>
                <span class="logout">Log Out</span>
            </div>
        <?php } ?>
    <?php } ?>
</div>

<div data-role="popup" class="popup_refreshNeeded" data-overlay-theme="b" data-theme="b" data-dismissible="false" style="max-width:400px;">
    <div data-role="header" data-theme="a">
        <h1>Could Not Update</h1>
    </div>
    <div role="main" class="ui-content">
        <p class="popup_refreshNeeded_message" class="ui-title"></p>
        <button class="popup_refreshNeeded_button" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b" data-rel="back">Refresh</button>
    </div>
</div>
