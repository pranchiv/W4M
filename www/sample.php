<!DOCTYPE html>
<html>
<head>
    <title>Wheels4Meals</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <link href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" rel="stylesheet">
    <link href="styles/main.css?1" rel="stylesheet" type="text/css" media="all">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.3/js/swiper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.3/css/swiper.min.css">

    <style>
        .swiper-container {
            width: 100%;
            height: 300px;
        }

        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: #fff;
            /* Center slide text vertically */
            display: -webkit-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            -webkit-justify-content: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            -webkit-align-items: center;
            align-items: center;
        }

        .swiper-slide-caption {
            position: absolute;
            z-index: 10;
            color: white;
            background: rgba(0,0,0,0.4);
            padding: 0 12px;
        }
    </style>
</head>
<body>

<div data-role="page">
 
    <div data-role="header" data-position="fixed">
        <?php include("includes/header.php"); ?>
    </div>

    <div role="main" class="ui-content">
        <!-- Slider main container -->
        <div class="swiper-container">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
                <!-- Slides -->
                <div class="swiper-slide">
                    <img src="<?php echo $root ?>/images/slider/slider1.jpg" alt="">
					<div class="swiper-slide-caption">
						<h3>$13.4 Billion</h3>
						<h4>Food Wasted in US alone</h4>
						<h4>Every year!</h4>
						<p>By Restaurants, Grocery Stores, Fast Food Outlets, Corporations</p>
					</div>
                </div>
                <div class="swiper-slide">
                    <img src="<?php echo $root ?>/images/slider/slider2.jpg" alt="">
					<div class="swiper-slide-caption">
						<h3>12,306,250</h3>
						<h4>Number of People that Go Hungry!!</h4>
						<h4>Every 3 Day in US.</h4>
						<p>Emergency Shelters, Food Banks, Charities try hard to serve Meals!</p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <img src="<?php echo $root ?>/images/slider/slider3.jpg" alt="">
					<div class="swiper-slide-caption">
						<h3>Bring Your ‘Wheels-4-Meals’!!</h3>
						<h4>Pick Up Donated Food.</h4>
						<h4>Deliver to Food Pantries in your Area!</h4>
						<p>Register. Get notified of Food Donations &amp; where to deliver.</p>
					</div>
                </div>
            </div>
            <!-- If we need pagination -->
            <div class="swiper-pagination"></div>

            <!-- If we need navigation buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>

        <h1>Login/Register</h1>
        <h2>Start the registration process below!</h2>
		
		
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
            Enter what your role will be and your cell carrier below:
        </p>

        

        <?php if ($dataset->num_rows > 0) { ?>
            <div class="ui-field-contain">
                <label for="memberType" style="white-space: nowrap;">Member Role:</label>

                <select id="memberType" name="memberType" data-native-menu="false" data-inline="true">
                    <option value="1" data-placeholder="true"></option>

                    <?php while ($row = $dataset->fetch_assoc()): ?>
                        <option value="<?php echo $row["MemberTypeID"]; ?>"><?php echo $row["Name"]; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        <?php
        }

        $sql = "SELECT CellCarrierID, Name FROM CellCarrier";
        $dataset = $db->query($sql);

        if (!$dataset) {
            echo '<p style="color: red; font-style: italic;">Query failed: ' . $db->error . "</p>\n";
        }
        ?>

        <?php if ($dataset->num_rows > 0) { ?>
            <div class="ui-field-contain">
                <label for="CellCarrier" style="white-space: nowrap;">Mobile Cell Carrier:</label>

                <select id="CellCarrier" name="CellCarrier" data-native-menu="false" data-inline="true">
                    <option value="0" data-placeholder="true"></option>

                    <?php while ($row = $dataset->fetch_assoc()): ?>
                        <option value="<?php echo $row["CellCarrierID"]; ?>"><?php echo $row["Name"]; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        <?php
        }

        $dataset = null;
        $db->close();
        ?>

    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php include("includes/footer.php"); ?>
    </div>

</div><!-- /page -->

    <script>
    var mySwiper = new Swiper ('.swiper-container', {
        loop: true,
        spaceBetween: 0,
        speed: 1000,
        centeredSlides: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: true,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    })
    </script>

</body>
</html>