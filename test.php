<?php
$password = "123";
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "New Hash for '123': " . $hash . "<br>";
echo "Verify result: " . (password_verify($password, $hash) ? 'true' : 'false') . "<br>";

// Test existing hash
$stored_hash = '$2y$10$bNbGHpwCXgQGkTnx.TOZvePYX8K7hL6LrHgwL3IFUkZ7tMEfcXKLi';
echo "<br>Testing stored hash:<br>";
echo "Verify result with stored hash: " . (password_verify($password, $stored_hash) ? 'true' : 'false') . "<br>";

// Raw password test
echo "<br>Raw comparison (not secure, just for testing):<br>";
echo "Password entered: " . $password . "<br>";
echo "Password needed: 123<br>";
?> 