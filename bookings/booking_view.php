<?php
require_once "../helpers/auth.php";
require_once "../config/db.php";

session_start();

if (!isset($_SESSION["email"])) {
    header("Location: ../users/login.php");
    exit();
}

$user = getCurrentUserData($conn);
$user_id = (int) $user["user_id"];

$booking_id = isset($_GET["booking_id"]) ? (int) $_GET["booking_id"] : 0;

if ($booking_id <= 0) {
    header("Location: booking_users.php");
    exit();
}

try {
    $stmt = $conn->prepare("CALL get_booking_summary(:booking_id)");
    $stmt->bindValue(":booking_id", $booking_id, PDO::PARAM_INT);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt->closeCursor();
} catch (PDOException $e) {
    die("Stored procedure not found. Make sure get_booking_summary exists.");
}

if (!$rows) {
    header("Location: booking_users.php");
    exit();
}

$booking = $rows[0];

$services = [];

foreach ($rows as $row) {
    $services[] = [
        "service_name" => $row["service_name"],
        "scheduled_at" => $row["scheduled_start"],
        "price" => $row["service_price"],
        "duration" => $row["service_duration"],
        "notes" => null,
    ];
}

$status = strtolower($booking["status"]);

$statusClass = match ($status) {
    "confirmed" => "bg-primary",
    "completed" => "bg-success",
    "cancelled" => "bg-danger",
    "no_show" => "bg-warning text-dark",
    default => "bg-secondary",
};

include_once "../header.php";
?>

<div class="service-container">

<div class="container py-4">

    <div class="row justify-content-center">

        <div class="col-12 col-lg-9">

            <!-- HEADER -->
            <div>

                <h1 class="heading">
                    Booking Details
                </h1>

            </div>

            <!-- BOOKING CARD -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <h3 class="card-title mb-2">
                                Booking #<?= htmlspecialchars(
                                    $booking["booking_ref"],
                                ) ?>
                            </h3>
                            <p class="mb-1">
                                <strong>Name:</strong>
                                <?= htmlspecialchars(
                                    $booking["first_name"] .
                                        " " .
                                        $booking["last_name"],
                                ) ?>
                            </p>
                            <p class="mb-1">
                                <strong>Email:</strong>
                                <?= htmlspecialchars($booking["email"]) ?>
                            </p>
                            <p class="mb-1">
                                <strong>Phone:</strong>
                                <?= htmlspecialchars($booking["phone"]) ?>
                            </p>
                            <p class="mb-1">
                                <strong>Scheduled:</strong>
                                <?= htmlspecialchars(
                                    $booking["scheduled_start"],
                                ) ?>
                            </p>
                            <p class="mb-1">
                            <strong>Status:</strong>
                                <span class="badge <?= $statusClass ?>">
                                    <?= htmlspecialchars(
                                        ucwords(
                                            str_replace(
                                                "_",
                                                " ",
                                                $booking["status"],
                                            ),
                                        ),
                                    ) ?>
                                </span>
                            </p>
                        </div>
                        <div class="text-end">
                            <p class="mb-1">
                                <strong>Total Price:</strong>
                                £<?= number_format(
                                    (float) ($booking["total_price"] ?? 0),
                                    2,
                                ) ?>
                            </p>
                            <p class="mb-1">
                                <strong>Total Duration:</strong>
                                <?= htmlspecialchars(
                                    (string) ($booking["total_duration"] ?? 0),
                                ) ?> mins
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- SERVICES -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-3">
                        Services
                    </h4>
                    <?php if (empty($services)): ?>
                        <p class="text-muted mb-0">
                            No services found for this booking.
                        </p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($services as $service): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                        <div>
                                            <h5 class="mb-1">
                                                <?= htmlspecialchars(
                                                    $service["service_name"],
                                                ) ?>
                                            </h5>
                                            <p class="mb-1 text-muted">
                                                Scheduled:
                                                <?= htmlspecialchars(
                                                    $service["scheduled_at"],
                                                ) ?>
                                            </p>
                                            <?php if (
                                                !empty($service["notes"])
                                            ): ?>
                                                <p class="mb-1">
                                                    <strong>Notes:</strong>
                                                    <?= htmlspecialchars(
                                                        $service["notes"],
                                                    ) ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-end">
                                            <p class="mb-1">
                                                <strong>Price:</strong>
                                                £<?= number_format(
                                                    (float) $service["price"],
                                                    2,
                                                ) ?>
                                            </p>
                                            <p class="mb-0">
                                                <strong>Duration:</strong>
                                                <?= (int) $service[
                                                    "duration"
                                                ] ?>
                                                mins
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            </div>
            <div>
                <a href="" class="btn btn-secondary"> ober here</a>
            </div>

        </div>

    </div>

</div>

<?php include_once "../footer.php"; ?>
