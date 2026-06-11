<?php
session_start();

require_once "config/db.php";
require_once "helpers/auth.php";

protectedPage($conn);

// Total active users
$totalStmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM users
    WHERE deleted_at IS NULL
");
$totalStmt->execute();
$totalUsers = (int) $totalStmt->fetchColumn();

// Subscription counts
$subStmt = $conn->prepare("
    SELECT
        SUM(CASE WHEN subscribed = 1 THEN 1 ELSE 0 END) AS subscribed_count,
        SUM(CASE WHEN subscribed = 0 THEN 1 ELSE 0 END) AS unsubscribed_count
    FROM users
    WHERE deleted_at IS NULL
");
$subStmt->execute();
$subData = $subStmt->fetch(PDO::FETCH_ASSOC);

$subscribedCount = (int) ($subData["subscribed_count"] ?? 0);
$unsubscribedCount = (int) ($subData["unsubscribed_count"] ?? 0);

// Role counts
$roleStmt = $conn->prepare("
    SELECT role, COUNT(*) AS total
    FROM users
    WHERE deleted_at IS NULL
    GROUP BY role
");
$roleStmt->execute();
$roleRows = $roleStmt->fetchAll(PDO::FETCH_ASSOC);

$adminCount = 0;
$customerCount = 0;
$employeeCount = 0;
$otherRoleCount = 0;

foreach ($roleRows as $row) {
    switch ($row["role"]) {
        case "admin":
            $adminCount = (int) $row["total"];
            break;
        case "customer":
            $customerCount = (int) $row["total"];
            break;
        case "employee":
            $employeeCount = (int) $row["total"];
            break;
        default:
            $otherRoleCount += (int) $row["total"];
            break;
    }
}

// Gender counts
$genderStmt = $conn->prepare("
    SELECT gender, COUNT(*) AS total
    FROM users
    WHERE deleted_at IS NULL
    GROUP BY gender
");
$genderStmt->execute();
$genderRows = $genderStmt->fetchAll(PDO::FETCH_ASSOC);

$male = 0;
$female = 0;
$otherGender = 0;

foreach ($genderRows as $row) {
    switch ($row["gender"]) {
        case "male":
            $male = (int) $row["total"];
            break;
        case "female":
            $female = (int) $row["total"];
            break;
        default:
            $otherGender += (int) $row["total"];
            break;
    }
}

// New users this month
$newUsersStmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM users
    WHERE deleted_at IS NULL
      AND YEAR(created_at) = YEAR(CURDATE())
      AND MONTH(created_at) = MONTH(CURDATE())
");
$newUsersStmt->execute();
$newUsersThisMonth = (int) $newUsersStmt->fetchColumn();

include_once "header.php";
?>

<main class="container">
    <div class="row service-container">
        <div class="col-12">

            <h1 class="heading">Dashboard</h1>

            <div class="row g-4 mb-4">
                <div class="col-12 col-md-4">
                    <div class="card shadow-sm text-center h-100">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <p class="card-text fs-2"><?= $totalUsers ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card shadow-sm text-center h-100">
                        <div class="card-body">
                            <h5 class="card-title">Subscribed</h5>
                            <p class="card-text fs-2"><?= $subscribedCount ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card shadow-sm text-center h-100">
                        <div class="card-body">
                            <h5 class="card-title">New This Month</h5>
                            <p class="card-text fs-2"><?= $newUsersThisMonth ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-12 col-md-4">
                    <div class="card shadow-sm text-center h-100">
                        <div class="card-body">
                            <h5 class="card-title">Admins</h5>
                            <p class="card-text fs-2"><?= $adminCount ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card shadow-sm text-center h-100">
                        <div class="card-body">
                            <h5 class="card-title">Customers</h5>
                            <p class="card-text fs-2"><?= $customerCount ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card shadow-sm text-center h-100">
                        <div class="card-body">
                            <h5 class="card-title">Employees</h5>
                            <p class="card-text fs-2"><?= $employeeCount ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm p-4 mb-4">
                <h3 class="sub-heading">Subscription Breakdown</h3>
                <div style="max-width: 500px; margin: 0 auto;">
                    <canvas id="subscriptionChart"></canvas>
                </div>
            </div>

            <div class="card shadow-sm p-4 mb-4">
                <h3 class="sub-heading">Role Breakdown</h3>
                <div style="max-width: 500px; margin: 0 auto;">
                    <canvas id="roleChart"></canvas>
                </div>
            </div>

            <div class="card shadow-sm p-4">
                <h3 class="sub-heading">Gender Breakdown</h3>
                <div style="max-width: 500px; margin: 0 auto;">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>

        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const subscriptionCtx = document.getElementById("subscriptionChart");
    const roleCtx = document.getElementById("roleChart");
    const genderCtx = document.getElementById("genderChart");

    if (subscriptionCtx) {
        new Chart(subscriptionCtx, {
            type: "doughnut",
            data: {
                labels: ["Subscribed", "Not Subscribed"],
                datasets: [{
                    data: [<?= $subscribedCount ?>, <?= $unsubscribedCount ?>],
                    backgroundColor: ["#28a745", "#dc3545"]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: "bottom" }
                }
            }
        });
    }

    if (roleCtx) {
        new Chart(roleCtx, {
            type: "pie",
            data: {
                labels: ["Admins", "Customers", "Employees"],
                datasets: [{
                    data: [<?= $adminCount ?>, <?= $customerCount ?>, <?= $employeeCount ?>],
                    backgroundColor: ["#343a40", "#007bff", "#17a2b8"]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: "bottom" }
                }
            }
        });
    }

    if (genderCtx) {
        new Chart(genderCtx, {
            type: "pie",
            data: {
                labels: ["Female", "Male", "Other"],
                datasets: [{
                    data: [<?= $female ?>, <?= $male ?>, <?= $otherGender ?>],
                    backgroundColor: ["#f8a5c2", "#74b9ff", "#dfe6e9"]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: "bottom" }
                }
            }
        });
    }
});
</script>

<?php include_once "footer.php"; ?>