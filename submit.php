 <?php
// Include the header file
include 'header.php';
?>
    <!-- CONTENT  -->
    <main class="container">
        <div class="row booked-message">
            <h1>
                We will see you soon <span id="display-name"></span>!
                We have you down for <span id="display-appointment"></span>!
            </h1>
            <p class="small-print">Can we politely remind everyone, we do require at least 24 hours notice to cancel or
                change your appointment. Failure to do so will result in 50% of your treatment value fee being charged.
                We reserve the right to refuse to rebook your appointment if you fail to comply with our policy. </p>
        </div>
        <!-- CAROUSEL -->
        <div id="carouselExampleCaptions" class="carousel slide display-none-sm" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
            </div>
            <div class="carousel-submit carousel-inner">
                <div class="carousel-item active">
                    <picture>
                        <source media="(max-width: 800px)" srcset="./assets/images/resized-images/nails800.png">
                        <source media="(max-width: 465px)" srcset="./assets/images/resized-images/nails465.png">      
                        <source media="(max-width: 300px)" srcset="./assets/images/resized-images/nails300.png">
                        <img src="./assets/images/nails.jpg" class="d-block w-100" alt="Stylish nail art">
                    </picture>
                    <div class="carousel-caption d-none d-md-block">
                    </div>
                </div>
                <div class="carousel-item">
                    <picture>
                        <source media="(max-width: 800px)" srcset="./assets/images/resized-images/face-massage800.png">
                        <source media="(max-width: 465px)" srcset="./assets/images/resized-images/face-massage465.png">      
                        <source media="(max-width: 300px)" srcset="./assets/images/resized-images/face-massage300.png">
                        <img src="./assets/images/face-massage.jpg" class="d-block w-100" alt="Relaxing face massage scene">
                    </picture>
                    <div class="carousel-caption d-none d-md-block">
                    </div>
                </div>
                <div class="carousel-item">
                    <picture>
                        <source media="(max-width: 800px)" srcset="./assets/images/resized-images/girls-lashes800.png">
                        <source media="(max-width: 465px)" srcset="./assets/images/resized-images/girls-lashes465.png">      
                        <source media="(max-width: 300px)" srcset="./assets/images/resized-images/girls-lashes300.png">
                        <img src="./assets/images/girls-lashes.jpg" class="d-block w-100" alt="Girl with styled eyelashes">
                    </picture>
                    <div class="carousel-caption d-none d-md-block">
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="never-display">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="never-display">Next</span>
            </button>
        </div>
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
 <?php
// Include the footer file
include 'footer.php';
?>