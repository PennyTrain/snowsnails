<?php
require_once "../helpers/auth.php";
require_once "../config/db.php";
$limit = 8; // how many i wanna show at a time
$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1; //skip some users then start grabbing,
$page = max($page, 1);
$offset = ($page - 1) * $limit; // so if page 2, skip limit amount of users and display following
// example if page 2:
// $page = 2
// $offset = (2 - 1) * 6 = 6

$countBookings = $conn->query("SELECT COUNT(*) FROM bookings");
// I have to count and not use the user id as a user id is not continuous
$totalBookings = (int) $countBookings->fetchColumn(); // total amound of users
$totalPages = (int) ceil($totalBookings / $limit);
// ceil() round up to the nearest whole number,
// 43 / 6 = 7.16 → ceil → 8 pages
// 5. Fetch paginated data
$stmt = $conn->prepare("
    SELECT booking_id, first_name, last_name, email, booking_ref, status, scheduled_start
    FROM bookings
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(":limit", $limit, PDO::PARAM_INT); // forces number
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC); // uses column names as key
include_once "../header.php";
?>

<div class="service-container">

    <h2 class="heading text-center">Bookings</h2>

    <div class="container testimonials">
        <div class="row justify-content-center">
<?php foreach ($bookings as $booking): ?>
    <div class="card mb-3 shadow-sm booking-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <h5 class="card-title mb-1">
                        Booking #<?= htmlspecialchars(
                            $booking["booking_ref"],
                        ) ?>
                    </h5>
                    <p class="mb-1 text-muted">
                        <?= htmlspecialchars(
                            $booking["first_name"] .
                                " " .
                                $booking["last_name"],
                        ) ?>
                    </p>
                    <p class="mb-1">
                        <strong>Date:</strong> <?= htmlspecialchars(
                            $booking["scheduled_start"],
                        ) ?>
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
        <?= htmlspecialchars(
            ucwords(str_replace("_", " ", $booking["status"])),
        ) ?>
    </span>
</p>
                </div>

                <div class="d-flex gap-2">
                    <form>
                    
                        <button type="submit" class="btn btn-danger">
                            Cancel
                        </button>

<a
    href="booking_view.php?booking_id=<?= urlencode($booking["booking_id"]) ?>"
    class="btn btn-secondary"
>
    View
</a>
</form>
                    
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
        </div>
    </div>
</div>
        <ul class="pagination justify-content-center">

            <!-- Previous -->
            <li class="page-item <?= $page <= 1 ? "disabled" : "" ?>">
                <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
            </li>

            <!-- Page Numbers -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $page ? "active" : "" ?>">
                    <a class="page-link" href="?page=<?= $i ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>

            <!-- Next -->
            <li class="page-item <?= $page >= $totalPages ? "disabled" : "" ?>">
                <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
            </li>

        </ul>
    </div>

</div>





    <?php include_once "../footer.php"; ?>
