<?php
require_once "../helpers/auth.php";
require_once "../config/db.php";
session_start();
protectedPage($conn);
$limit = 8; // how many i wanna show at a time
$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1; //skip some users then start grabbing,
$page = max($page, 1);
$offset = ($page - 1) * $limit; // so if page 2, skip limit amount of users and display following
// example if page 2:
// $page = 2
// $offset = (2 - 1) * 6 = 6

$countUsers = $conn->query("SELECT COUNT(*) FROM users");
// COUNT, counts how many users are in the table
// I have to count and not use the user id
// as a user id is not continuous, this is for pagination
$totalUsers = (int) $countUsers->fetchColumn(); // total amound of users
$totalPages = (int) ceil($totalUsers / $limit);
// ceil() round up to the nearest whole number,
// 43 / 6 = 7.16 → ceil → 8 pages
// 5. Fetch paginated data
$stmt = $conn->prepare("
    SELECT user_id, first_name, img_url, email
    FROM users
    ORDER BY user_id DESC
    LIMIT :limit OFFSET :offset
");

$stmt->bindValue(":limit", $limit, PDO::PARAM_INT); // forces number
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC); // uses column names as key
include_once "../header.php";
?>
<main class="container">
<div class="row service-container">

    <h2 class="heading text-center">Users</h2>

    <div class="container testimonials">
        <div class="row justify-content-center">
        <?php foreach ($users as $user): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex">
                <div class="card w-100 h-100 cards text-center shadow-sm">
                    <img class="card-img-top card-image"
                         src="<?= htmlspecialchars($user["img_url"]) ?>"
                         alt="User Profile">
                        <div class="card-body">
                            <p class="card-text">
                                <?= htmlspecialchars($user["first_name"]) ?>
                            </p>
                            <h6 class="card-title">
                                <?= htmlspecialchars($user["email"]) ?>
                            </h6>
                                    <a href="user_view.php?user_id=<?= urlencode(
                                        $user["user_id"],
                                    ) ?>"
                                       class="btn btn-secondary">
                                        View
                                    </a>
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
            </main>
<?php include_once "../footer.php"; ?>

