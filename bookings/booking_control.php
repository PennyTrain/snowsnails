<?php
session_start();
require_once "../config/db.php";
require_once "./auth_helpers.php";

if (isset($_POST["booking"])) {
    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $names = array_map("trim", $names);
    $names = array_map("intval", $services);
    $schedued_at = trim($_POST["scheduled_at"] ?? "");

    if (
        $first_name === "" ||
        $last_name === "" ||
        $email === "" ||
        $phone === "" ||
        $names === "" ||
        $scheduled_at === ""
    ) {
        $_SESSION["booking_error"] = "Please fill in all required fields.";
        header("Location: booking.php");
        exit();
    }

    if (!validateEmail($email)) {
        $_SESSION["booking_error"] = "Please enter a valid email address.";
        header("Location: booking.php");
        exit();
    }

    try {
        $check = $conn->prepare("SELECT 1 FROM users WHERE email = ? LIMIT 1");
        $check->execute([$email]);

        $hashed_password = hashValidatedPassword($password, $confirm_password);

        $role = "customer";

        if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") {
            $role = $_POST["role"] ?? "customer";
        }

        $insert = $conn->prepare("
            INSERT INTO users (first_name, last_name, email, phone, password, role)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $insert->execute([
            $first_name,
            $last_name,
            $email,
            $phone,
            $hashed_password,
            $role,
        ]);

        $_SESSION["name"] = $first_name;
        $_SESSION["email"] = $email;
        $_SESSION["role"] = $role;

        header("Location: user.php");
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION["register_error"] = "Email is already registered!";
            header("Location: register.php");
            exit();
        }
        $_SESSION["register_error"] =
            "Database error (register): " . $e->getMessage();
        header("Location: register.php");
        exit();
    } catch (Exception $e) {
        $_SESSION["register_error"] = $e->getMessage();
        header("Location: register.php");
        exit();
    }
}
