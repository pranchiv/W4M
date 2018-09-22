<?php if (DEBUG) { ?>

<style>
    .debugtable {
        border: 1px solid;
        border-collapse: collapse;
        position: fixed; 
        bottom: 10px; 
        right: 4px; 
        font-size: 10px;
        box-shadow: black 6px 6px 10px;
        background: #ecdec8d9;
        z-index: 1500;
    }

    .debugtable th, td {
        border: 1px solid;
        padding: 2px 6px;
    }
</style>
<table class="debugtable">
    <tr><td>Member ID</td><td><?= $_SESSION['MemberID'] ?></td></tr>
    <tr><td>Name</td><td><?= $_SESSION['MemberName'] ?></td></tr>
    <tr><td>Member Type</td><td><?= $_SESSION['MemberType'].' ('.$_SESSION['MemberTypeID'].')' ?></td></tr>
    <tr><td>Member Status</td><td><?= $_SESSION['MemberStatus'].' ('.$_SESSION['MemberStatusID'].')' ?></td></tr>
    <tr><td>Company</td><td><?= $_SESSION['Company'].' ('.$_SESSION['CompanyID'].')' ?></td></tr>
    <tr><td>Forgot?</td><td><?= $_SESSION['ForgotPassword'] ?></td></tr>
</table>

<?php } ?>
