<div class="header">
    <a href="<?= $root ?>" data-ajax="false"><img src="<?= $root ?>/images/logo.svg" style="height:40px;" /></a>

    <?php if ($_SESSION['MemberTypeID'] == 1) { ?>
        <div class="menu" style="flex-grow: 1;">
            <a href="<?= $top ?>pages/admin.php" data-transition="flip">Accounts</a>
            <a href="<?= $top ?>pages/adminDonations.php" data-transition="flip">Donations</a>
            <a href="<?= $top ?>pages/donationHistory.php" data-transition="flip">Donation History</a>
        </div>
    <?php } ?>

    <?php if ($_SESSION['MemberID']) { ?>
        <div class="logout">
            Log Out
        </div>
    <?php } ?>
</div>
