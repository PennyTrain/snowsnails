<?php
session_start();
require_once "../config/db.php";
require_once "../helpers/auth.php";
protectedPage($conn);
$user_id = isset($_GET["user_id"]) ? (int) $_GET["user_id"] : 0;
if ($user_id <= 0) {
    header("Location: users.php");
    exit();
}
$stmt = $conn->prepare("
    SELECT *
    FROM users
    WHERE user_id = ?
");
$stmt->execute([$user_id]);
$viewedUser = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$viewedUser) {
    header("Location: users.php");
    exit();
}
include_once "../header.php";
?>
<div class="service-container">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9">
                <h1 class="heading">
                    User Details
                </h1>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                            <div>
                                <h3 class="card-title mb-3">
                                    <?= htmlspecialchars(
                                        ($viewedUser["first_name"] ?? "") .
                                        " " .
                                        ($viewedUser["last_name"] ?? "")
                                    ) ?>
                                </h3>
                                <p class="mb-1">
                                    <strong>User ID:</strong>
                                    <?= htmlspecialchars($viewedUser["user_id"] ?? "") ?>
                                </p>
                                <p class="mb-1">
                                    <strong>Email:</strong>
                                    <?= htmlspecialchars($viewedUser["email"] ?? "") ?>
                                </p>
                                <p class="mb-1">
                                    <strong>Phone:</strong>
                                    <?= htmlspecialchars($viewedUser["phone"] ?? "") ?>
                                </p>
                                <p class="mb-1">
                                    <strong>Role:</strong>
                                    <?= htmlspecialchars(
                                        ucfirst($viewedUser["role"] ?? "")
                                    ) ?>
                                </p>
                                <p class="mb-1">
                                    <strong>Subscribed:</strong>
                                    <?= !empty($viewedUser["subscribed"])
                                        ? "Yes"
                                        : "No" ?>
                                </p>
                                <p class="mb-1">
                                    <strong>Created:</strong>
                                    <?= htmlspecialchars(
                                        $viewedUser["created_at"] ?? "N/A"
                                    ) ?>
                                </p>
                                <p class="mb-1">
                                    <strong>Updated:</strong>
                                    <?= htmlspecialchars(
                                        $viewedUser["updated_at"] ?? "N/A"
                                    ) ?>
                                </p>
                                <p class="mb-1">
                                    <strong>Deleted At:</strong>
                                    <?= htmlspecialchars(
                                        $viewedUser["deleted_at"] ?? "Not Deleted"
                                    ) ?>
                                </p>
                            </div>
                            <div>
                                <?php if (!empty($viewedUser["img_url"])): ?>
                                    <img
                                        src="<?= htmlspecialchars($viewedUser["img_url"]) ?>"
                                        alt="Profile Image"
                                        class="img-fluid rounded"
                                        style="width: 200px; height: 200px; object-fit: cover;"
                                    >
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                                                            <a href="users_page.php" class="btn btn-secondary">Back</a>
    </div>
</div>
<?php include_once "../footer.php"; ?>