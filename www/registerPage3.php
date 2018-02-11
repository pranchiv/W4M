<!DOCTYPE html>
<html>
<head>
    <title>Wheels4Meals</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <link href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" rel="stylesheet">
    <link href="styles/main.css?1" rel="stylesheet" type="text/css" media="all">
</head>
<body>

<div data-role="page">
 
    <div data-role="header" data-position="fixed">
        <?php include("includes/header.php"); ?>
    </div>

    <div role="main" class="ui-content">

        <h1>Login/Register</h1>
        <h2>Continue the process below:</h2>
		
		
        <?php require_once('connection.php'); ?>

        <?php
        $db = DB::getInstance();

        echo '<div class="location_id' . (isset($db) ? '' : ' bad') . '">DB Connection: ' . (isset($db) ? 'GOOD!' : 'bad') . '</div><br/>' . "\n\n";

        //$proc = 'GetMemberTypes';
        $sql = "SELECT MemberTypeID, Name FROM MemberType";
        $dataset = $db->query($sql);

        if (!$dataset) {
            echo '<p style="color: red; font-style: italic;">Query failed: ' . $db->error . "</p>\n";
        }
        //$dataset->setFetchMode(PDO::FETCH_ASSOC);
        // $stmt->execute();
        // "CALL GetCustomers()"
        //$stmt = $dbh->prepare ("INSERT INTO user (firstname, surname) VALUES (:fname, :sname)");
        //$stmt -> bindParam(':fname', 'John');
        //$stmt -> bindParam(':sname', 'Smith');
        //$stmt -> execute();
        ?>

        <p style="font-size: 20px; border: 2px solid #BB7722; border-radius: 8px; padding: 8px; margin-top: 32px; width: 80%;">
            Please specify what days and times your organization can accept or donate food:
        </p>

        

        <?php if ($dataset->num_rows > 0) { ?>
            <div class="ui-field-contain">
                <label for="memberType" style="white-space: nowrap;">ZIP code:</label>

                <form>
                     
                     <input type="number" data-clear-btn="false" name="number-1" id="number-1" value="">
                     
                </form>
            </div>
        <?php
        }

        $sql = "SELECT CellCarrierID, Name FROM CellCarrier";
        $dataset = $db->query($sql);

      if (!$dataset) {
            echo '<p style="color: red; font-style: italic;">Query failed: ' . $db->error . "</p>\n";
        }
        ?>
        <a href="#" class="ui-btn ui-btn-inline">Previous Page</a>
        <button class="ui-btn ui-btn-inline">Continue</button>

        
        }

        $dataset = null;
        $db->close();
        ?>

    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php include("includes/footer.php"); ?>
    </div>

</div><!-- /page -->


</body>
</html>