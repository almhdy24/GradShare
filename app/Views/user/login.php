<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link rel="stylesheet" href="<?= $this->env(
         "base_url"
       ) ?>assets/css/bootstrap.min.css">
    <title>GradShare - Login</title>
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .alert {
            margin-bottom: 1rem;
        }
        h1 {
            margin-bottom: 1.5rem;
        }
        .btn-primary {
            width: 100%;
        }
         .registration-link {
            text-align: center;
            margin-top: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
      <?php if (!empty($errors)): ?>
    <?php
        // Check if errors is not an array, and if it's a string, wrap it in an array
        if (!is_array($errors)) {
            $errors = [$errors];
        }

        // Iterate through each error
        foreach ($errors as $key => $value) {
            // If the value is an array, it indicates field-specific errors 
            if (is_array($value)) {
                foreach ($value as $error) {
                    echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($error) . '</div>';
                }
            } else {
                // Render the single error message directly
                echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($value) . '</div>';
            }
        }
    ?>
<?php endif; ?>

        <h1 class="text-center">Login</h1>
        <p class="text-center">Login to access GradShare features</p>
        <form action="login" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username <small>(required, 2-32 characters)</small></label>
                <input type="text" class="form-control" name="username" id="username" required placeholder="Enter your username">
                <div class="form-text">Must be between 2 and 32 characters.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password <small>(required, 6-32 characters)</small></label>
                <input type="password" class="form-control" name="password" id="password" required placeholder="Enter your password">
                <div class="form-text">Must be between 6 and 32 characters.</div>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <p class="mt-3 text-center">
                <a href="#" onclick="showMessage(event)">Forgot your password?</a>
            </p>       
        </form>
        <div class="registration-link">
            <p>Don't have an account? <a href="register">Register here</a></p>
        </div>
    </div>
<script>
function showMessage(event) {
    event.preventDefault(); // Prevent default anchor behavior
    alert("Please contact support to reset your password.");
}
</script>
</html>