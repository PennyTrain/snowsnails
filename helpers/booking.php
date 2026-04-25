<?php

function generateBookingReference($length = 6): string {
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ123456789';
    $ref = '';

    for ($i = 0; $i < $length; $i++) {
        $ref .= $chars[random_int(0, strlen($chars) - 1)];
    }

    return $ref;
}

function generateUniqueBookingReference(PDO $conn, $length = 6): string {
    do {
        $ref = generateBookingReference($length);

        $stmt = $conn->prepare("SELECT 1 FROM bookings WHERE booking_reference = ?");
        $stmt->execute([$ref]);

    } while ($stmt->fetch());

    return $ref;
}