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

$limit = 8;
$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;

$countStmt = $conn->prepare("
    SELECT COUNT(*)
    FROM bookings
    WHERE user_id = :user_id
");

$countStmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

$countStmt->execute();
$totalBookings = (int) $countStmt->fetchColumn();

$totalPages = (int) ceil($totalBookings / $limit);

$stmt = $conn->prepare("
    SELECT booking_id, first_name, last_name, email, booking_ref, status, scheduled_start
    FROM bookings
    WHERE user_id = :user_id
    ORDER BY scheduled_start DESC
    LIMIT :limit OFFSET :offset
");

$stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
$stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

$stmt->execute();

$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once "../header.php";
?>

<div class="service-container">

    <h2 class="heading text-center">Bookings</h2>

<div class="container testimonials">
    <div class="row justify-content-center">
        <?php if (empty($bookings)): ?>
            <div class="col-12">
                <p class="text-center text-muted">
                    You do not have any bookings yet.
                </p>
                <p class="text-center">                    <a href="booking_create.php" class="btn btn-secondary">Create a Booking</a></p>
            </div>
        <?php else: ?>
            <?php foreach ($bookings as $booking): ?>
                <div class="card mb-3 shadow-sm booking-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div>
                                <h5 class="card-title mb-1">
                                    Booking #<?= htmlspecialchars($booking["booking_ref"]) ?>
                                </h5>
                                <p class="mb-1 text-muted">
                                    <?= htmlspecialchars($booking["first_name"] . " " . $booking["last_name"]) ?>
                                </p>
                                <p class="mb-1">
                                    <strong>Date:</strong> <?= htmlspecialchars($booking["scheduled_start"]) ?>
                                </p>

                                <?php
                                $status = strtolower($booking["status"]);

                                $statusClass = match ($status) {
                                    "confirmed" => "bg-primary",
                                    "completed" => "bg-success",
                                    "cancelled" => "bg-danger",
                                    "no_show" => "bg-warning text-dark",
                                    default => "bg-secondary",
                                };
                                ?>

                                <p class="mb-1">
                                    <strong>Status:</strong>
                                    <span class="badge <?= $statusClass ?>">
                                        <?= htmlspecialchars(ucwords(str_replace("_", " ", $booking["status"]))) ?>
                                    </span>
                                </p>
                            </div>

                            <div class="d-flex gap-2">
                                <form>
                                    <button type="submit" class="btn btn-danger">
                                        Cancel
                                    </button>

                                    <a href="booking_view.php?booking_id=<?= urlencode($booking["booking_id"]) ?>"
                                       class="btn btn-secondary">
                                        View
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</div>





    <?php include_once "../footer.php"; ?>
