<?php
session_start();
require_once "../config/db.php";
// Get current user data
$isLoggedIn = isset($_SESSION["email"]);

$user = []; // default empty array

if ($isLoggedIn) {
    $stmt = $conn->prepare("
        SELECT first_name, last_name, email, phone, img_url 
        FROM users 
        WHERE email = ?
    ");
    $stmt->execute([$_SESSION["email"]]);
    $user = $stmt->fetch();
}

$stmt = $conn->prepare("
    SELECT s.service_id, s.name, s.price, c.name AS category
    FROM services s
    JOIN categories c ON s.category_id = c.category_id
    ORDER BY c.name, s.name
");
$stmt->execute();
$services = $stmt->fetchAll();
include "../header.php";
?>

    <!-- CONTENT -->
    <main class="container">
        <!-- FORM -->
        <div class="row service-container">
            <h1 class="heading">Book with us!</h1>
            <p class="form-desc display-none">
                <span>Experience the Ultimate in Beauty and Relaxation!</span><br>
                <?php if (!$isLoggedIn): ?>
<span>Create an account and create a booking all in one below!</span>
<?php endif; ?>
                <!-- <span>At Beauty Bliss Therapies, we offer a wide range of luxurious treatments designed to enhance your
                    natural beauty and rejuvenate your spirit. Whether you're looking for a stunning manicure,
                    soothing facial, or relaxing massage, we have the perfect service to meet your needs. Book your
                    appointment today and discover the blissful difference!</span> -->
            </p>
            <form class="form-container">
                <label for="bookfirstname" class="form-label">First Name:</label>
<input 
    type="text" 
    id="bookfirstname" 
    name="firstname" 
    class="form-control"
    value="<?= $isLoggedIn ? htmlspecialchars($user["first_name"]) : "" ?>"
    aria-label="First Name"
    <?= $isLoggedIn ? "required" : "" ?>
>
                <label for="booklastname" class="form-label">Last Name:</label>
<input 
    type="text" 
    id="booklastname" 
    name="lastname" 
    class="form-control"
    value="<?= $isLoggedIn ? htmlspecialchars($user["last_name"]) : "" ?>"
    aria-label="Last Name"
    <?= $isLoggedIn ? "required" : "" ?>
>
                <label for="bookemail" class="form-label">Email:</label>
<input 
    type="email" 
    id="bookemail" 
    name="email" 
    class="form-control"
    value="<?= $isLoggedIn ? htmlspecialchars($user["email"]) : "" ?>"
    aria-label="Email"
    <?= $isLoggedIn ? "required" : "" ?>
>


                <label for="phone" class="form-label">Phone Number:</label>
<input 
    type="phone" 
    id="bookphone" 
    name="phone" 
    class="form-control"
    value="<?= $isLoggedIn ? htmlspecialchars($user["phone"]) : "" ?>"
    aria-label="Phone"
    <?= $isLoggedIn ? "required" : "" ?>
>




<!-- <?php if (!$isLoggedIn): ?>
    <label for="password">Password</label>
    <input type="password" id="password" name="password" class="form-control" required minlength="8">

    <label for="confirm_password">Confirm Password</label>
    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required minlength="8">
<?php endif; ?> -->

<div class="services-accordion">

<label class="form-label">Select Services:</label>


<?php
$currentCategory = "";
$index = 0;

foreach ($services as $service):
    if ($currentCategory !== $service["category"]):

        if ($currentCategory !== "") {
            echo "</div></div></div>"; // close previous category
        }

        $currentCategory = $service["category"];
        $index++;
        ?>

    <div class="accordion-item">
        <p class="accordion-header" id="heading<?= $index ?>">
            <button class="accordion-button collapsed" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#collapse<?= $index ?>">
                <?= htmlspecialchars($currentCategory) ?>
            </button>
        </p>

        <div id="collapse<?= $index ?>" class="accordion-collapse collapse">
            <div class="accordion-body">

<?php
    endif; ?>

                <div class="form-check">
                    <input 
                        type="checkbox"
                        name="name[]"
                        value="<?= $service["service_id"] ?>"
                        id="service<?= $service["service_id"] ?>"
                        class="form-check-input"
                    >

                    <label for="service<?= $service[
                        "service_id"
                    ] ?>" class="form-check-label">
                        <?= htmlspecialchars($service["name"]) ?> 
                        (£<?= number_format($service["price"], 2) ?>)
                    </label>
                </div>

<?php
endforeach;
?>

    </div></div></div> <!-- close last category -->

</div>


                        <label for="scheduled_at" class="form-label">Appointment date and time:</label>
<input id="scheduled_at" class="form-control" type="datetime-local" name="scheduled_at" value="">

                <button type="submit" class="btn btn-secondary" name="booking" formaction="submit.html">Submit</button>
            </form>
            <!-- GOOGLE MAP -->
            <!-- <div class="col-lg-6 map-container">
                <iframe title="map" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCuBfkrmtyc3Rd8u2uYxb0xa2ozAvCz7fY&q=Beauty+Bliss,22+Lowestoft+Road,Carlton+Colville,NR33+8JD">
                </iframe>
            </div> -->
            <!-- CANELATION POLICY -->
            <p class="small-print">Can we politely remind everyone, we do require at least 24 hours notice to cancel or
                change your appointment.
                We reserve the right to refuse to rebook your appointment if you fail to comply with our policy. </p>
        </div>
    </main>


<?php include "../footer.php"; ?>

<!-- https://www.youtube.com/watch?v=LiomRvK7AM8 -->