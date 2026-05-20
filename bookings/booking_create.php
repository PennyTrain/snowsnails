<?php
session_start();

require_once "../config/db.php";
require_once "../helpers/auth.php";

$isLoggedIn = isset($_SESSION["email"]);
$user = [];

if ($isLoggedIn) {
    $user = getCurrentUserData($conn);
}

$stmt = $conn->prepare("
    SELECT s.service_id, s.name, s.price, c.name AS category
    FROM services s
    JOIN categories c ON s.category_id = c.category_id
    ORDER BY c.name, s.name
");
$stmt->execute();
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

include "../header.php";
?>

<main class="container">
    <div class="row service-container">

        <h1 class="heading">Book with us!</h1>

        <p class="form-desc display-none">
            <span>Experience the Ultimate in Beauty and Relaxation!</span><br>

            <?php if (!$isLoggedIn): ?>
                <span>Create an account and create a booking_create all in one below!</span>
            <?php endif; ?>
        </p>

<form class="form-container" method="post" action="booking_control.php">

            <!-- USER DETAILS -->
            <label for="bookfirstname" class="form-label">First Name:</label>
            <input
                type="text"
                id="bookfirstname"
                name="firstname"
                class="form-control"
                value="<?= $isLoggedIn
                    ? htmlspecialchars($user["first_name"] ?? "")
                    : "" ?>"
                required
            >

            <label for="booklastname" class="form-label">Last Name:</label>
            <input
                type="text"
                id="booklastname"
                name="lastname"
                class="form-control"
                value="<?= $isLoggedIn
                    ? htmlspecialchars($user["last_name"] ?? "")
                    : "" ?>"
                required
            >

            <label for="bookemail" class="form-label">Email:</label>
            <input
                type="email"
                id="bookemail"
                name="email"
                class="form-control"
                value="<?= $isLoggedIn
                    ? htmlspecialchars($user["email"] ?? "")
                    : "" ?>"
                required
            >

            <label for="bookphone" class="form-label">Phone Number:</label>
            <input
                type="text"
                id="bookphone"
                name="phone"
                class="form-control"
                value="<?= $isLoggedIn
                    ? htmlspecialchars($user["phone"] ?? "")
                    : "" ?>"
                required
            >

            <!-- SERVICES -->
            <div class="services-accordion mt-3">
                <label class="form-label">Select Services:</label>

                <?php
                $currentCategory = null;
                $index = 0;

                foreach ($services as $service):
                    if ($currentCategory !== $service["category"]):

                        if ($currentCategory !== null) {
                            echo "</div></div></div>";
                        }

                        $currentCategory = $service["category"];
                        $index++;
                        ?>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?= $index ?>">
                                <button
                                    class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse<?= $index ?>"
                                >
                                    <?= htmlspecialchars($currentCategory) ?>
                                </button>
                            </h2>

                            <div id="collapse<?= $index ?>" class="accordion-collapse collapse">
                                <div class="accordion-body">

                    <?php
                    endif; ?>

                    <div class="form-check">
                        <input
                            type="checkbox"
                            name="services[]"
                            value="<?= $service["service_id"] ?>"
                            id="service<?= $service["service_id"] ?>"
                            class="form-check-input"
                        >

                        <label
                            for="service<?= $service["service_id"] ?>"
                            class="form-check-label"
                        >
                            <?= htmlspecialchars($service["name"]) ?>
                            (£<?= number_format($service["price"], 2) ?>)
                        </label>
                    </div>

                <?php
                endforeach;
                ?>

                </div></div></div>
            </div>

            <!-- DATE -->
            <label for="scheduled_at" class="form-label mt-3">
                Appointment date and time:
            </label>

            <input
                id="scheduled_at"
                class="form-control"
                type="datetime-local"
                name="scheduled_at"
                required
            >

            <!-- SUBMIT -->
            <button type="submit" class="btn btn-secondary mt-3" name="booking_create">
                Submit
            </button>

        </form>

        <p class="small-print mt-4">
            Can we politely remind everyone, we do require at least 24 hours notice to cancel or
            change your appointment. We reserve the right to refuse to rebook your appointment if
            you fail to comply with our policy.
        </p>

    </div>
</main>

<?php include_once "../footer.php"; ?>

<!-- https://www.youtube.com/watch?v=LiomRvK7AM8 -->
