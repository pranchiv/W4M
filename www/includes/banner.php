<?php if ($showBanner) { ?>

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

<script>
    $(document).on('pagecreate', function() {
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
    });
</script>

<?php } ?>