<?php if (ENV == 'local') { ?>

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
        z-index: 2000;
    }

    .debugtable th, td {
        border: 1px solid;
        padding: 2px 6px;
    }
</style>
<table class="debugtable">
    <tr><td>Member ID</td><td><?= $_SESSION['MemberID'] ?></td></tr>
    <tr><td>Name</td><td><?= $_SESSION['MemberName'] ?></td></tr>
    <tr><td>Member Type</td><td><?php echo $_SESSION['MemberType'].' ('.$_SESSION['MemberTypeID'].')' ?></td></tr>
    <tr><td>Member Status</td><td><?php echo $_SESSION['MemberStatus'].' ('.$_SESSION['MemberStatusID'].')' ?></td></tr>
    <tr><td>Company</td><td><?php echo $_SESSION['Company'].' ('.$_SESSION['CompanyID'].')' ?></td></tr>
</table>

<?php } ?>
