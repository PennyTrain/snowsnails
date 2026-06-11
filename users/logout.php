<?php
session_start();

require_once "../config/db.php";
require_once "../helpers/errors.php";
require_once "../helpers/auth.php";

protectedUserPage($conn);
$user = getCurrentUserData($conn);
include "../header.php";
?>
<main class="container">
    <div class="row service-container">

        <div class="col-12 text-center">

            <?php if (!empty($user["img_url"])): ?>
                <img
                    src="<?= htmlspecialchars($user["img_url"]) ?>"
                    class="profile-img"
                    alt="User account"
                >
            <?php endif; ?>

            <h1 class="heading">WAIT!</h1>

            <p class="sub-heading">
                Are you sure you want to log out?
            </p>

            <form method="post" action="user_control.php">

                <div >

                    <button
                        type="submit"
                        name="confirm_logout"
                        class="btn btn-danger"
                    >
                        Yes, Log Me Out
                    </button>

                    <a
                        href="../index.php"
                        class="btn btn-secondary"
                    >
                        No, Take Me Back
                    </a>

                </div>

            </form>

        </div>

    </div>
</main>
<?php include "../footer.php"; ?>


<!-- https://www.youtube.com/watch?v=LiomRvK7AM8 -->