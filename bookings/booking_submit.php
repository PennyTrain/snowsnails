<?php
session_start();
include_once "../header.php";

$booking_ref = $_SESSION["booking_ref"] ?? null;
$booking_user_id = $_SESSION["booking_user_id"] ?? null;
?>

    <!-- CONTENT  -->
    <main class="container">
    <div class="row service-container">
        <?php if (!empty($booking_ref)): ?>
            <h1 class="heading">Your Reference Number is:</h1>
            <h1 class="heading">
                <strong><?= htmlspecialchars($booking_ref) ?></strong>
            </h1>
            <a href="booking_users.php" class="btn btn-secondary">
                View All Bookings
            </a>
            <p class="small-print">
                Can we politely remind everyone, we do require at least
                24 hours notice to cancel or change your appointment.
                Failure to do so will result in 50% of your treatment
                value fee being charged. We reserve the right to refuse
                to rebook your appointment if you fail to comply with
                our policy.
            </p>
        <?php else: ?>
            <div class="text-center">
                <h1 class="heading">
                    It appears you haven't made a booking yet.
                </h1>
                <p class="mb-4">
                    Ready to treat yourself?
                </p>
                <a href="booking_create.php" class="btn btn-secondary">
                    Make a Booking
                </a>
            </div>
        <?php endif; ?>
    </div>
        <!-- CAROUSEL -->
        <!-- MAP -->
        <section class="testimonials display-none">
            <h1 class="home-heading">Where We Are</h1>
            <div class="row map-container">
                <iframe
                    title="map"
                    loading="lazy"
                    style="width: 100%; height: 450px; border: 0;"
                    allowfullscreen
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCuBfkrmtyc3Rd8u2uYxb0xa2ozAvCz7fY&q=5+Lyon+Street+West,Bognor+Regis,United+Kingdom"
                >
                </iframe>
            </div>
        </section>
    </main>
 <?php // Include the footer file

include_once "../footer.php";
?>
