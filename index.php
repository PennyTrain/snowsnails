 <?php // Include the header file
// Include the header file
include "header.php"; ?>
    <!-- CAROUSEL -->
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <picture>
                        <img src="https://res.cloudinary.com/dgz5gpe5z/image/upload/q_auto/f_auto/v1776150413/IMG_2483_tafp34.jpg" class="d-block w-100"
                        alt="Calming and aesthetically pleasing photo of shampoo bottles - calm light">
                </picture>
                <div class="carousel-caption d-none d-md-block">
                    <p class="carousel-title"></p>
                </div>
            </div>
            <div class="carousel-item">
                <picture>
                    <img src="https://res.cloudinary.com/dgz5gpe5z/image/upload/q_auto/f_auto/v1776150413/IMG_2486_ghbqn3.jpg" class="d-block w-100"
                        alt="Calming and aesthetically pleasing photo of shampoo bottles - calm right">
                </picture>
                <div class="carousel-caption d-none d-md-block">
                    <p class="carousel-title"></p>
                </div>
            </div>
            <div class="carousel-item">
                <picture>
                    <img src="https://res.cloudinary.com/dgz5gpe5z/image/upload/v1776150413/IMG_2481_rorfbg.jpg" class="d-block w-100"
                        alt="Default image, Calming and aesthetically pleasing photo of shampoo bottles">
                </picture>
                <div class="carousel-caption d-none d-md-block">
                    <p class="carousel-title"></p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"
                aria-label="slide carousel to the left and reveal previous image"></span>
            <span class="never-display">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"
                aria-label="slide carousel to the right and reveal next image"></span>
            <span class="never-display">Next</span>
        </button>
    </div>
    <!-- CONTENT  -->
    <main class="container">
        <!-- SERVICES -->
        <section>
            <h1 class="home-heading">Our Services</h1>
            <!-- CARDS -->
            <div class="row all-cards">
                                <!-- NAILS -->
                <div class="card col-lg-4 col-md-3 col-sm-12 cards">
                    <img src="./assets/images/lady-nails.jpg" class="card-img-top card-image"
                        alt="A lady doing someones nails">
                    <div class="card-body">
                        <h2 class="card-title">Nails</h2>
                        <p class="card-text">Get the perfect manicure with our wide range of nail services. From classic
                            to
                            trendy designs!</p>
                        <a href="/services.php?category_id=1" class="btn btn btn-secondary">Take a look!</a>
                    </div>
                </div>
                <!-- LASHES -->
                <div class="card col-lg-4 col-md-3 col-sm-12 cards">
                    <img src="./assets/images/lashes.jpg" class="card-img-top card-image"
                        alt="Someone whos eye lashes are perfect">
                    <div class="card-body">
                        <h2 class="card-title">Lashes</h2>
                        <p class="card-text">Enhance your natural beauty with our professional lash extensions. Perfect
                            for
                            any occasion!</p>
                        <a href="/services.php?category_id=2" class="btn btn btn-secondary">Take a look!</a>
                    </div>
                </div>

                <!-- WAXING -->
                <div class="card col-lg-4 col-md-3 col-sm-12 cards">
                    <img src="./assets/images/stone-massage.jpg" class="card-img-top card-image"
                        alt="A lady getting a spa treatment with stones on her back">
                    <div class="card-body">
                        <h2 class="card-title">Treatments</h2>
                        <p class="card-text">Indulge in our luxurious treatments designed to rejuvenate and refresh your
                            skin. Feel pampered!</p>
                        <a href="/services.php?category_id=3" class="btn btn btn-secondary">Take a look!</a>
                    </div>
                </div>
                <!-- TREATMENTS -->
                <div class="card col-lg-4 col-md-3 col-sm-12 cards">
                    <img src="./assets/images/stone-massage.jpg" class="card-img-top card-image"
                        alt="A lady getting a spa treatment with stones on her back">
                    <div class="card-body">
                        <h2 class="card-title">Treatments</h2>
                        <p class="card-text">Indulge in our luxurious treatments designed to rejuvenate and refresh your
                            skin. Feel pampered!</p>
                        <a href="/services.php?category_id=4" class="btn btn btn-secondary">Take a look!</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- OFFERS -->
<section class="offers-container">
    <h1 class="home-heading">
        Special Offers <i class="bi bi-0-square custom-icon"></i>
    </h1>

    <div class="accordion offers-accordion" id="offersAccordion">

        <!-- ITEM 1 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="offerHeadingOne">
                <button class="accordion-button" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#offerCollapseOne"
                        aria-expanded="true">
                    20% Off Facials
                </button>
            </h2>

            <div id="offerCollapseOne" class="accordion-collapse collapse show"
                 data-bs-parent="#offersAccordion">
                <div class="accordion-body">
                    <p>
                        Pamper yourself with our luxurious facials and enjoy 20% off your first booking.
                        Perfect for rejuvenating your skin!
                    </p>
                    <div class="guten">
                        <a href="./bookings/booking.php" class="btn offer-btn btn-secondary">Book Now</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ITEM 2 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="offerHeadingTwo">
                <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#offerCollapseTwo">
                    Buy One Get One Free Manicure
                </button>
            </h2>

            <div id="offerCollapseTwo" class="accordion-collapse collapse"
                 data-bs-parent="#offersAccordion">
                <div class="accordion-body">
                    <p>
                        Treat yourself and a friend to a stunning manicure. Book one and get the second one free.
                        Limited time offer!
                    </p>
                    <div class="guten">
                        <a href="./bookings/booking.php" class="btn offer-btn btn-secondary">Book Now</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ITEM 3 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="offerHeadingThree">
                <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#offerCollapseThree">
                    Free Lash Tint with Lash Extensions
                </button>
            </h2>

            <div id="offerCollapseThree" class="accordion-collapse collapse"
                 data-bs-parent="#offersAccordion">
                <div class="accordion-body">
                    <p>
                        Enhance your lashes with our professional extensions and receive a free lash tint.
                        Achieve the perfect look!
                    </p>
                    <div class="guten">
                        <a href="./bookings/booking.php" class="btn offer-btn btn-secondary">Book Now</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
        <!-- TESTIMONIALS -->
        <section class="testimonials display-none">
            <h1 class="home-heading">What Our Clients Say</h1>
            <div class="row testimonial-holder">
                <div class="card col-lg-3 col-md-3 col-sm-12 cards">
                    <div class="card-body review-body">
                        <p class="reviewer">Jane Doe</p>
                        <h2 class="card-title">Excellent Service!</h2>
                        <p class="card-text">"I had an amazing experience at Snows Nails. The staff were friendly and
                            professional, and the treatments were top-notch. Highly recommend!"</p>
                        <h3 class="rating">★★★★★</h3>
                    </div>
                </div>
                <div class="card col-lg-3 col-md-3 col-sm-12 cards">
                    <div class="card-body review-body">
                        <p class="reviewer">John Smith</p>
                        <h2 class="card-title">Wonderful Atmosphere</h2>
                        <p class="card-text">"The salon has a wonderful atmosphere and the staff made me feel very
                            comfortable. I will definitely be coming back for more treatments."</p>
                        <h3 class="rating">★★★★★</h3>
                    </div>
                </div>
                <div class="card col-lg-3 col-md-3 col-sm-12 cards">
                    <div class="card-body review-body">
                        <p class="reviewer">Emily Johnson</p>
                        <h2 class="card-title">Great Results</h2>
                        <p class="card-text">"I am so happy with the results of my facial treatment. My skin feels
                            rejuvenated and looks fantastic. Thank you, Snows Nails!"</p>
                        <h3 class="rating">★★★★★</h3>
                    </div>
                </div>
                <div class="card col-lg-3 col-md-3 col-sm-12 cards">
                    <div class="card-body review-body">
                        <p class="reviewer">Sarah Brown</p>
                        <h2 class="card-title">Highly Professional</h2>
                        <p class="card-text">"The team at Snows Nails is highly professional and attentive. They really
                            listen to your needs and provide excellent service. I couldn't be happier!"</p>
                        <h3 class="rating">★★★★★</h3>
                    </div>
                </div>
            </div>
        </section>
    </main>
 <?php // Include the footer file

include "footer.php";
?>
