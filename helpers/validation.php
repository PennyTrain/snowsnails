<?php

function validateEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validatePasswordMatch(string $password, string $confirm_password): void
{
    if ($password !== $confirm_password) {
        throw new Exception("Passwords do not match.");
    }
}

function validatePasswordLength(string $password, int $min = 8): void
{
    if (strlen($password) < $min) {
        throw new Exception("Password must be at least {$min} characters.");
    }
}

function hashValidatedPassword(
    string $password,
    string $confirm_password,
): string {
    validatePasswordMatch($password, $confirm_password);
    validatePasswordLength($password);

    return password_hash($password, PASSWORD_DEFAULT);
}
