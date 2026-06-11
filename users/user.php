<?php
session_start();
require_once "../config/db.php";
require_once "../helpers/auth.php";
protectedUserPage($conn);
// Get current user data
$user = getCurrentUserData($conn);

include_once "../header.php";
?>
<main class="container">
    <section class="row justify-content-center service-container">
        <div class="col-12 col-lg-8">

            <div class=" ">
                <div class="card-body">

                    <div class="profile-header">
                        <?php if (!empty($user["img_url"])): ?>
                            <img
                                src="<?= htmlspecialchars($user["img_url"]) ?>"
                                class="profile-img"
                                alt="User account"
                            >
                        <?php endif; ?>

                        <h2 class="heading mb-0">
                            <?= htmlspecialchars($user["first_name"]) ?>'s Profile
                        </h2>
                    </div>

                    <div class="profile-content mt-4">
                        <p><strong>Name:</strong> <?= htmlspecialchars($user["first_name"]) ?> <?= htmlspecialchars($user["last_name"]) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($user["email"]) ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($user["phone"]) ?></p>
                    </div>

                    <div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
                        <button onclick="window.location.href='user_update.php'" class="btn btn-secondary">
                            Update
                        </button>
                        <button onclick="window.location.href='logout.php'" class="btn btn-secondary">
                            Logout
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </section>
</main>

 <?php // Include the footer file

include_once "../footer.php";
?>
