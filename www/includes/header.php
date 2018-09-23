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
