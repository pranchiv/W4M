<style>
    .debugtable {
        border: 1px solid;
        border-collapse: collapse;
    }

    .debugtable th, td {
        border: 1px solid;
        padding: 2px 6px;
    }
</style>
<table class="debugtable">
    <tr><td>Member ID</td><td><?= $_SESSION['MemberID'] ?></td></tr>
    <tr><td>Name</td><td><?= $_SESSION['MemberName'] ?></td></tr>
    <tr><td>Member Type ID</td><td><?= $_SESSION['MemberType'] ?></td></tr>
    <tr><td>Member Status ID</td><td><?= $_SESSION['MemberStatus'] ?></td></tr>
    <tr><td>Company ID</td><td><?= $_SESSION['CompanyID'] ?></td></tr>
    <tr><td>Company</td><td><?= $_SESSION['Company'] ?></td></tr>
</table>
