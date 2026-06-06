    <!-- FOOTER -->
     <?php
     $isLoggedIn = isset($_SESSION["email"]);
     $user = [];

     if ($isLoggedIn) {
         $user = getCurrentUserData($conn);
     }
     ?>
    <footer>
<?php if ($isLoggedIn && empty($user["subscribed"])): ?>

    <section class="subscribe">
        <div>
            <h2 class="heading">Join Our Mailing List!</h2>
            <p class="text">
                Join our mailing list to be the first to know about new and exciting treatments,
                promotions and offers from Snows Nails!
            </p>
        </div>

        <div class="row subscribe-form">
<form id="subscribe" class="text" method="post" action="/users/user_control.php">
    <!-- this is a hidden redirect feild so that after the form is submitted it returns the user to the same page -->
    <input type="hidden" name="return_to" value="<?= htmlspecialchars(
        $_SERVER["REQUEST_URI"],
    ) ?>">

    <label for="submit-firstname">First Name:</label>
    <input type="text" id="submit-firstname" name="firstname" value="<?= htmlspecialchars(
        $user["first_name"] ?? "",
    ) ?>" required>

    <label for="submit-lastname">Last Name:</label>
    <input type="text" id="submit-lastname" name="lastname" value="<?= htmlspecialchars(
        $user["last_name"] ?? "",
    ) ?>" required>

    <label for="submit-email">Email:</label>
    <input type="email" id="submit-email" name="email" value="<?= htmlspecialchars(
        $user["email"] ?? "",
    ) ?>" required>

    <input type="submit" name="subscribe" value="Subscribe">
</form>
        </div>

        <div class="small-print text">
            <p>
                By clicking subscribe you agree to receive emails. You can opt out anytime.
            </p>
        </div>
    </section>

<?php elseif (!$isLoggedIn): ?>

    <section class="exclusive">
        <h2 class="heading">Do you want exclusive offers?</h2>
        <p class="text">
            Create an account to sign up to our exclusive mailing list.
            This way you will receive the latest information about our products, services and promotions.
        </p>
        <button class="mailing-list" onclick="window.location.href='/users/user_create.php'">
            Register
        </button>
    </section>

<?php endif; ?>


        <div class="row footer-info">
            <div class="col-lg-4 footer-news">
                <h3 class="sub-heading">NEWS</h3>
                <p class="text">
                    <span>£5 Discount for all students and those with a blue light card!</span>
                                <section class="footer-social">
    <ul class="footer-social-list">

        <li class="footer-social-item">
            <a class="link"
               href="https://www.instagram.com/snownails_bognor_regis?igsh=MXh3ZDlhbXlrM2pqMw=="
               target="_blank" aria-label="Instagram">
                <i class="fa-brands fa-instagram footer-social-icon"></i>
            </a>
        </li>

        <li class="footer-social-item">
            <a class="link"
               href="https://www.facebook.com/share/17xjCHRv9W/?mibextid=wwXIfr"
               target="_blank" aria-label="Facebook">
                <i class="fa-brands fa-facebook-f footer-social-icon"></i>
            </a>
        </li>

        <li class="footer-social-item">
            <a class="link"
               href="https://www.tiktok.com/@snownail?_r=1&_t=ZT-94WDSdqtvvp"
               target="_blank" aria-label="TikTok">
                <i class="fa-brands fa-tiktok footer-social-icon"></i>
            </a>
        </li>

    </ul>
</section>

                </p>
            </div>
                        <div class="col-lg-4 footer-times">
                <h3 class="sub-heading">OPEN HOURS</h3>
                <table class="open-hours-table">
                    <tr>
                        <th>Day</th>
                        <th>Hours</th>
                    </tr>
                    <tr>
                        <td>Monday</td>
                        <td>9:30am - 6pm</td>
                    </tr>
                    <tr>
                        <td>Tuesday</td>
                        <td>9:30am - 6pm</td>
                    </tr>
                    <tr>
                        <td>Wednesday</td>
                        <td>9:30am - 6pm</td>
                    </tr>
                    <tr>
                        <td>Thursday</td>
                        <td>9:30am - 6pm</td>
                    </tr>
                    <tr>
                        <td>Friday</td>
                        <td>9:30am - 6pm</td>
                    </tr>
                    <tr>
                        <td>Saturday</td>
                        <td>9:30am - 6pm</td>
                    </tr>
                    <tr>
                        <td>Sunday</td>
                        <td>Closed</td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-4 footer-contact">
                <h3 class="sub-heading">CONTACT US</h3>
                <p class="text">
                    <span>Snows Nails</span>
                    <span>5 Lyon St West</span>
                    <span>Bognor Regis PO211BY</span>
                    <span>Phone: 07538599019</span>
                </p>
            </div>
        </div>
    </footer>
    <script src="/assets/js/script.js"></script>
    <!-- The Link for all The Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/f302dda9d1.js" crossorigin="anonymous"></script>
    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>

