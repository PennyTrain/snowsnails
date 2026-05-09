<?php
include_once "header.php";
require_once "./config/db.php";

$allowed_categories = ["1", "2", "3", "4"];
$category = $_GET["category_id"] ?? "";

// https://www.php.org/result/what-is-the-difference-between-using-118526
if (!in_array($category, $allowed_categories)) {
    $category = "1";
}

// A prepared statement is a safe way to run SQL queries with user input
// instead of
// $conn->query("SELECT * FROM categories WHERE category_id = $category");
$stmt = $conn->prepare("
    SELECT name, description
    FROM categories
    WHERE category_id = :category
");
$stmt->execute(["category" => $category]);
$categoryData = $stmt->fetch();

// fallback category is the nails service page, so that the user is not left
//looking at a 404 error
if (!$categoryData) {
    $stmt = $conn->prepare("
        SELECT name, description
        FROM categories
        WHERE category_id = 1
    ");
    $stmt->execute();
    $categoryData = $stmt->fetch();
}

// all stored vals from database so that i can dynamically update ui!
$title = $categoryData["name"];
$description = $categoryData["description"];

// now i need to get the services that are within each category
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
            <!-- text interpolation here -->
            <h1 class="heading"><?= htmlspecialchars($title) ?></h1>
            <p class="text"><?= htmlspecialchars($description) ?></p>
                        <a href="/bookings/booking.php" class="btn offer-btn btn-secondary">Book Now</a>
            <?php if ($category == 1): ?>
            <img src="./assets/images/lady-nails.jpg" class="service-img no-image" alt="A ladys nails">
            <?php endif; ?>
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
                                <td><?= htmlspecialchars(
                                    $service["name"],
                                ) ?></td>
                                <td>£<?= number_format(
                                    $service["price"],
                                    2,
                                ) ?></td>
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

<?php include_once "footer.php"; ?>
