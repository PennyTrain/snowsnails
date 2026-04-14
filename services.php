<?php
include "header.php";
require_once "db.php";

$allowed_categories = ["1", "2", "3", "4"];
$category = $_GET["category_id"] ?? "";

if (!in_array($category, $allowed_categories)) {
    $category = "1"; // default fallback
}

$stmt = $conn->prepare("
    SELECT name, description
    FROM categories
    WHERE category_id = :category
");
$stmt->execute(["category" => $category]);
$categoryData = $stmt->fetch();

if (!$categoryData) {
    // fallback to category 1
    $stmt = $conn->prepare("
        SELECT name, description
        FROM categories
        WHERE category_id = 1
    ");
    $stmt->execute();
    $categoryData = $stmt->fetch();
}

$title = $categoryData["name"];
$description = $categoryData["description"];

$stmt = $conn->prepare("
    SELECT name, price, description
    FROM services
    WHERE category_id = :category
    ORDER BY name ASC
");
$stmt->execute(["category" => $category]);
$services = $stmt->fetchAll();
?>

<main>
    <section class="row service-container">
        <div class="col-lg-6 service-info">
            <h1 class="heading"><?= htmlspecialchars($title) ?></h1>
            <p class="text"><?= htmlspecialchars($description) ?></p>
            <a href="/bookings/booking.php" class="btn offer-button link">Book Now</a>
        </div>

        <div class="col-lg-6 service-table">
            <table>
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($services)): ?>
                        <?php foreach ($services as $service): ?>
                            <tr>
                                <td><?= htmlspecialchars($service["name"]) ?></td>
                                <td>£<?= number_format($service["price"], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2">No services found in this category.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php include "footer.php"; ?>
