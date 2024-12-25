<?php
if (!empty($message)) {
  // Determine alert type based on the content of the message
  $alertType = (stripos($message, "success") !== false) ? "success" : "danger";

  // Create a sanitized and formatted alert message
  $escapedMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
  echo "<div class='fade show mt-4 p-3 alert alert-$alertType' role='alert'>";
  echo $escapedMessage;
  echo "</div>";
}
?>