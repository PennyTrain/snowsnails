<?php session_start();
require_once "../config/db.php";
require_once "../helpers/validation.php";
require_once "../helpers/auth.php";
require_once "../helpers/errors.php";

if (isset($_POST["booking_create"])) {
    handleBooking($conn);
} elseif (isset($_POST["booking_update"])) {
    handleBookingUpdate($conn);
} else {
    header("Location: /dab502/assignment/snowsnail/users/login.php");
    exit();
}

function handleBooking(PDO $conn): void
{
    $first_name = trim($_POST["firstname"] ?? "");
    $last_name = trim($_POST["lastname"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $scheduled_start = trim($_POST["scheduled_at"] ?? "");
    $services = $_POST["services"] ?? [];
    if (
        $first_name === "" ||
        $last_name === "" ||
        $email === "" ||
        $phone === "" ||
        $scheduled_start === "" ||
        !is_array($services) ||
        empty($services)
    ) {
        throwErr(
            "booking",
            "warning",
            "Please fill in all required fields and select at least one service.",
        );
        header("Location: /dab502/assignment/snowsnail/bookings/booking_create.php");
        exit();
    }
    if (!validateEmail($email)) {
        throwErr("booking", "danger", "Invalid email.");
        header("Location: /dab502/assignment/snowsnail/bookings/booking_create.php");
        exit();
    }
    try {
        $scheduledStart = new DateTime($scheduled_start);
        $scheduled_start_db = $scheduledStart->format("Y-m-d H:i:s");
        $services = array_values(array_unique(array_map("intval", $services)));
        $user_id = getCurrentBookingUserId($conn);
        $booking_ref = generateUniqueBookingReference($conn);
        $conn->beginTransaction();
        $bookingStmt = $conn->prepare(
            " INSERT INTO bookings ( booking_ref, user_id, employee_id, first_name, last_name, email, phone, scheduled_start, status ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) ",
        );
        $bookingStmt->execute([
            $booking_ref,
            $user_id,
            null,
            $first_name,
            $last_name,
            $email,
            $phone,
            $scheduled_start_db,
            "confirmed",
        ]);
        $booking_id = (int) $conn->lastInsertId();
        $serviceLookupStmt = $conn->prepare("
    SELECT service_id, duration, price
    FROM services
    WHERE service_id = ?
");

        $bookingServiceStmt = $conn->prepare("
    INSERT INTO booking_services
        (booking_id, service_id, scheduled_at, price, duration, notes)
    VALUES
        (?, ?, ?, ?, ?, ?)
");
        $currentStart = new DateTime($scheduled_start_db);
        foreach ($services as $service_id) {
            $serviceLookupStmt->execute([$service_id]);
            $service = $serviceLookupStmt->fetch(PDO::FETCH_ASSOC);

            if (!$service) {
                throw new Exception("Invalid service selected.");
            }

            $bookingServiceStmt->execute([
                $booking_id,
                (int) $service["service_id"],
                $currentStart->format("Y-m-d H:i:s"),
                (float) $service["price"],
                (int) $service["duration"],
                null,
            ]);

            $currentStart->modify(
                "+" . (int) $service["duration"] . " minutes",
            );
        }
        $conn->commit();
        $_SESSION["booking_ref"] = $booking_ref;
        $_SESSION["booking_user_id"] = $user_id;
        throwErr("booking", "success", "Booking created successfully.");
        header("Location: /dab502/assignment/snowsnail/bookings/booking_submit.php");
        exit();
    } catch (Throwable $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        error_log("Booking error: " . $e->getMessage());
        throwErr("booking", "danger", "Unable to create booking.");
        header("Location: /dab502/assignment/snowsnail/bookings/booking.php");
        exit();
    }
}

function handleBookingUpdate(PDO $conn): void
{
    $booking_id = (int) ($_POST["booking_id"] ?? 0);

    $first_name = trim($_POST["firstname"] ?? "");
    $last_name = trim($_POST["lastname"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $scheduled_start = trim($_POST["scheduled_at"] ?? "");
    $services = $_POST["services"] ?? [];

    if (
        $booking_id <= 0 ||
        $first_name === "" ||
        $last_name === "" ||
        $email === "" ||
        $phone === "" ||
        $scheduled_start === "" ||
        !is_array($services) ||
        empty($services)
    ) {
        throwErr(
            "booking",
            "warning",
            "Please fill in all required fields and select at least one service.",
        );

        header("Location: /dab502/assignment/snowsnail/bookings/booking_update.php?booking_id=" . $booking_id);
        exit();
    }

    if (!validateEmail($email)) {
        throwErr("booking", "danger", "Invalid email.");

        header("Location: /dab502/assignment/snowsnail/bookings/booking_update.php?booking_id=" . $booking_id);
        exit();
    }

    try {
        $scheduledStart = new DateTime($scheduled_start);
        $scheduled_start_db = $scheduledStart->format("Y-m-d H:i:s");

        $services = array_values(array_unique(array_map("intval", $services)));

        $conn->beginTransaction();

        $updateBooking = $conn->prepare("
            UPDATE bookings
            SET
                first_name = ?,
                last_name = ?,
                email = ?,
                phone = ?,
                scheduled_start = ?
            WHERE booking_id = ?
        ");

        $updateBooking->execute([
            $first_name,
            $last_name,
            $email,
            $phone,
            $scheduled_start_db,
            $booking_id,
        ]);

        $deleteServices = $conn->prepare("
            DELETE FROM booking_services
            WHERE booking_id = ?
        ");

        $deleteServices->execute([$booking_id]);

        $serviceLookupStmt = $conn->prepare("
            SELECT
                service_id,
                duration,
                price
            FROM services
            WHERE service_id = ?
        ");

        $bookingServiceStmt = $conn->prepare("
            INSERT INTO booking_services
            (
                booking_id,
                service_id,
                scheduled_at,
                price,
                duration,
                notes
            )
            VALUES
            (?, ?, ?, ?, ?, ?)
        ");

        $currentStart = new DateTime($scheduled_start_db);

        foreach ($services as $service_id) {
            $serviceLookupStmt->execute([$service_id]);

            $service = $serviceLookupStmt->fetch(PDO::FETCH_ASSOC);

            if (!$service) {
                throw new Exception("Invalid service selected.");
            }

            $bookingServiceStmt->execute([
                $booking_id,
                (int) $service["service_id"],
                $currentStart->format("Y-m-d H:i:s"),
                (float) $service["price"],
                (int) $service["duration"],
                null,
            ]);

            $currentStart->modify(
                "+" . (int) $service["duration"] . " minutes",
            );
        }

        $conn->commit();

        throwErr("booking", "success", "Booking updated successfully.");

        header("Location: /dab502/assignment/snowsnail/bookings/booking_view.php?booking_id=" . $booking_id);
        exit();
    } catch (Throwable $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }

        error_log("Booking update error: " . $e->getMessage());

        throwErr("booking", "danger", "Unable to update booking.");

        header("Location: /dab502/assignment/snowsnail/bookings/booking_update.php?booking_id=" . $booking_id);
        exit();
    }
}

function getCurrentBookingUserId(PDO $conn)
{
    if (empty($_SESSION["email"])) {
        return null;
    }
    $user = getCurrentUserData($conn);
    return !empty($user["user_id"]) ? (int) $user["user_id"] : null;
}

function generateUniqueBookingReference(PDO $conn, $length = 6)
{
    do {
        $ref = "SN-" . generateBookingReference($length);
        $stmt = $conn->prepare("SELECT 1 FROM bookings WHERE booking_ref = ?");
        $stmt->execute([$ref]);
    } while ($stmt->fetchColumn());
    return $ref;
}
function generateBookingReference($length = 6)
{
    $chars = "ABCDEFGHJKLMNPQRSTUVWXYZ123456789";
    $ref = "";
    for ($i = 0; $i < $length; $i++) {
        $ref .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $ref;
}
