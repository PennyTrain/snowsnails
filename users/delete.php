<?php
session_start();

require_once "../config/db.php";
require_once "../helpers/auth.php";
require_once "../helpers/errors.php";

protectedPage($conn);

$user_id = isset($_GET["user_id"]) ? (int) $_GET["user_id"] : 0;

if ($user_id <= 0) {
    header("Location: users.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT user_id, first_name, last_name, email, role, img_url
    FROM users
    WHERE user_id = ?
");
$stmt->execute([$user_id]);
$viewedUser = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$viewedUser) {
    header("Location: users.php");
    exit();
}

include "../header.php";
?>

<main class="container">
    <div class="row service-container">
        <div class="col-12">
            <h1 class="heading">Delete User</h1>
<div class="profile-header">

    <?php if (!empty($viewedUser["img_url"])): ?>
        <img
            src="<?= htmlspecialchars($viewedUser["img_url"]) ?>"
            class="profile-img"
            alt="User account"
        >
    <?php endif; ?>


</div>
            <p class="heading">
                Are you sure you want to permanently delete
                <?= htmlspecialchars(
                    $viewedUser["first_name"] . " " . $viewedUser["last_name"],
                ) ?>?
            </p>

            <form
                action="user_control.php"
                method="post"
            >
                <input type="hidden" name="user_id" value="<?= (int) $viewedUser[
                    "user_id"
                ] ?>">

                <button type="submit" name="permanent_delete" class="btn btn-danger">
                    Permanently Delete
                </button>

                <a href="user_view.php?user_id=<?= (int) $viewedUser[
                    "user_id"
                ] ?>" class="btn btn-secondary">
                    Cancel
                </a>
            </form>
        </div>
    </div>
</main>

<?php include "../footer.php"; ?>
