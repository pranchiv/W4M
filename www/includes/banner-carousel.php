<div class="xxxbanner">
    <?php
        // choose random pic/video
        $img = '../images/backgrounds/bread.jpg';
    ?>

    <!-- <div style="position: absolute; font-size: 24px; transform: rotate(-30deg); top: 50px;">-- BANNER --</div>
    <img src="<?php echo $img; ?>" /> -->
</div>

<!-- requires <link href="<?php echo $root ?>/styles/bootstrap.min.css" rel="stylesheet" type="text/css" media="all"> -->

  	<div id="pageintro" class="clear" style=""> <!-- DEBUG ONLY: style background: #B98251; height: 50px;-->
		<div id="myCarousel" style="" class="carousel slide" data-ride="carousel"> <!-- DEBUG ONLY: style display: none;-->
			<!-- Indicators -->
			<ol class="carousel-indicators">
				<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
				<li data-target="#myCarousel" data-slide-to="1"></li>
				<li data-target="#myCarousel" data-slide-to="2"></li>
			</ol>

			<!-- Wrapper for slides -->
			<div class="carousel-inner">
				<div class="item active">
					<img src="<?php echo $root ?>/images/slider/slider1.jpg" alt="">
					<div class="carousel-caption">
						<h3>$13.4 Billion</h3>
						<h4>Food Wasted in US alone</h4>
						<h4>Every year!</h4>
						<p>By Restaurants, Grocery Stores, Fast Food Outlets, Corporations</p>
					</div>
				</div>

				<div class="item">
					<img src="<?php echo $root ?>/images/slider/slider2.jpg" alt="">
					<div class="carousel-caption">
						<h3>12,306,250</h3>
						<h4>Number of People that Go Hungry!!</h4>
						<h4>Every 3 Day in US.</h4>
						<p>Emergency Shelters, Food Banks, Charities try hard to serve Meals!</p>
					</div>
				</div>

				<div class="item">
					<img src="<?php echo $root ?>/images/slider/slider3.jpg" alt="">
					<div class="carousel-caption">
						<h3>Bring Your ‘Wheels-4-Meals’!!</h3>
						<h4>Pick Up Donated Food.</h4>
						<h4>Deliver to Food Pantries in your Area!</h4>
						<p>Register. Get notified of Food Donations &amp; where to deliver.</p>
					</div>
				</div>
			</div>

			<!-- Left and right controls -->
			<a class="left carousel-control" href="#myCarousel" data-slide="prev">
				<span class="fa fa-chevron-left"></span>
			</a>
			<a class="right carousel-control" href="#myCarousel" data-slide="next">
				<span class="fa fa-chevron-right"></span>
			</a>
		</div>    
	</div>

<script>
$(document).ready(function () {
    //rotation speed and timer
    var speed = 3000;
 
    var run = setInterval(rotate, speed);
    var slides = $('.slide');
    var container = $('#slides ul');
    var elm = container.find(':first-child').prop("tagName");
    var item_width = container.width();
    var previous = 'prev'; //id of previous button
    var next = 'next'; //id of next button
    slides.width(item_width); //set the slides to the correct pixel width
    container.parent().width(item_width);
    container.width(slides.length * item_width); //set the slides container to the correct total width
    container.find(elm + ':first').before(container.find(elm + ':last'));
    resetSlides();
 
 
    //if user clicked on prev button
 
    $('#buttons a').click(function (e) {
        //slide the item
 
        if (container.is(':animated')) {
            return false;
        }
        if (e.target.id == previous) {
            container.stop().animate({
                'left': 0
            }, 1500, function () {
                container.find(elm + ':first').before(container.find(elm + ':last'));
                resetSlides();
            });
        }
 
        if (e.target.id == next) {
            container.stop().animate({
                'left': item_width * -2
            }, 1500, function () {
                container.find(elm + ':last').after(container.find(elm + ':first'));
                resetSlides();
            });
        }
 
        //cancel the link behavior            
        return false;
 
    });
 
    //if mouse hover, pause the auto rotation, otherwise rotate it    
    container.parent().mouseenter(function () {
        clearInterval(run);
    }).mouseleave(function () {
        run = setInterval(rotate, speed);
    });
 
 
    function resetSlides() {
        //and adjust the container so current is in the frame
        container.css({
            'left': -1 * item_width
        });
    }
 
});
//a simple function to click next link
//a timer will call this function, and the rotation will begin
 
function rotate() {
    $('#next').click();
}
</script>