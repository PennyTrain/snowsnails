<?php
session_start();

$isAdmin = isset($_SESSION["role"]) && $_SESSION["role"] === "admin";
$old = $_SESSION["old_register"] ?? [];
// strict
function old_value(array $old, string $key, string $default = ""): string
{
    return htmlspecialchars($old[$key] ?? $default, ENT_QUOTES, "UTF-8");
}

function selected_value(array $old, string $key, string $value): string
{
    return (($old[$key] ?? "") === $value) ? "selected" : "";
}

include "../header.php";
?>
<main class="container">
<div class="row service-container">
    <h1 class="heading">
        <?= $isAdmin ? "Create User" : "Create an Account" ?>
    </h1>

    <?php if (!empty($_SESSION["register_error"])): ?>
        <p class="error-message">
            <?= htmlspecialchars($_SESSION["register_error"], ENT_QUOTES, "UTF-8") ?>
        </p>
        <?php unset($_SESSION["register_error"]); ?>
    <?php endif; ?>

    <form action="user_control.php" method="post" class="form-container">
        <label for="first_name">First Name</label>
        <input
            type="text"
            id="first_name"
            name="first_name"
            class="form-control"
            value="<?= old_value($old, "first_name") ?>"
            required
        >

        <label for="last_name">Last Name</label>
        <input
            type="text"
            id="last_name"
            name="last_name"
            class="form-control"
            value="<?= old_value($old, "last_name") ?>"
            required
        >

        <label for="email">Email</label>
        <input
            type="email"
            id="email"
            name="email"
            class="form-control"
            value="<?= old_value($old, "email") ?>"
            required
        >

        <label for="phone">Phone</label>
        <input
            type="tel"
            id="phone"
            name="phone"
            class="form-control"
            value="<?= old_value($old, "phone") ?>"
        >

        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            class="form-control"
            required
            minlength="8"
        >

        <label for="confirm_password">Confirm Password</label>
        <input
            type="password"
            id="confirm_password"
            name="confirm_password"
            class="form-control"
            required
            minlength="8"
        >

<?php if ($isAdmin): ?>
    <label for="role">User Role</label>
    <select name="role" id="role" class="form-control">
        <option value="customer" <?= selected_value($old, "role", "customer") ?>>Customer</option>
        <option value="employee" <?= selected_value($old, "role", "employee") ?>>Employee</option>
        <option value="admin" <?= selected_value($old, "role", "admin") ?>>Admin</option>
    </select>

    <div id="employeeFields" class="<?= (($old["role"] ?? "") === "employee") ? "" : "d-none" ?>">
        <label for="salary">Salary</label>
        <input
            type="number"
            name="salary"
            id="salary"
            value="<?= old_value($old, "salary", "0") ?>"
            class="form-control"
        >

        <label for="when_hired" class="form-label mt-3">When Hired</label>
        <input
            id="when_hired"
            class="form-control"
            type="datetime-local"
            name="when_hired"
            value="<?= old_value($old, "when_hired") ?>"
        >

        <label for="title">Title</label>
        <select name="title" id="title" class="form-control">
            <option value="nail_tech" <?= selected_value($old, "title", "nail_tech") ?>>Nail Tech</option>
            <option value="massage_therapist" <?= selected_value($old, "title", "massage_therapist") ?>>Massage Therapist</option>
            <option value="esthetician" <?= selected_value($old, "title", "esthetician") ?>>Esthetician</option>
            <option value="specialist" <?= selected_value($old, "title", "specialist") ?>>Specialist</option>
            <option value="manager" <?= selected_value($old, "title", "manager") ?>>Manager</option>
        </select>
    </div>
<?php endif; ?>
        <button type="submit" name="register" class="btn btn-secondary">
            <?= $isAdmin ? "Create User" : "Register" ?>
        </button>

        <p class="text-center">
            Already Have an Account?
            <a href="login.php">Login</a>
        </p>
    </form>
</div>
</main>

<?php
unset($_SESSION["old_register"]);
include "../footer.php";
?>