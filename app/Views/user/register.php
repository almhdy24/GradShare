<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link rel="stylesheet" href="<?= $this->env(
         "base_url"
       ) ?>assets/css/bootstrap.min.css">
    <title>GradShare - Register</title>
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
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Register</h1>
        
        <?php if (!empty($errors)): ?>
            <?php
            if (!is_array($errors)) {
              $errors = [$errors];
            }
            foreach ($errors as $value) {
              if (is_array($value)) {
                foreach ($value as $error) {
                  echo '<div class="alert alert-danger" role="alert">' .
                    htmlspecialchars($error) .
                    "</div>";
                }
              } else {
                echo '<div class="alert alert-danger" role="alert">' .
                  htmlspecialchars($value) .
                  "</div>";
              }
            }
            ?>
        <?php endif; ?>

        <div class="mt-5">
            <h2>Join GradShare Today!</h2>
            <p>Create an account to enjoy exclusive services and connect with peers in your academic journey.</p>

            <form id="registrationForm" action="register" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required minlength="2" maxlength="32" placeholder="Enter your username (2-32 characters)" aria-describedby="usernameHelp">
                    <small id="usernameHelp" class="form-text text-muted">Your username should be 2-32 characters long.</small>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required minlength="8" maxlength="56" placeholder="example@domain.com" aria-describedby="emailHelp">
                    <small id="emailHelp" class="form-text text-muted">Please enter a valid email address.</small>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required minlength="6" maxlength="32" placeholder="Enter your password" aria-describedby="passwordHelp">
                    <small id="passwordHelp" class="form-text text-muted">Your password should be at least 6 characters long.</small>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6" maxlength="32" placeholder="Confirm your password" aria-describedby="confirmPasswordHelp">
                    <small id="confirmPasswordHelp" class="form-text text-muted">Please confirm your password.</small>
                </div>
                <button type="submit" class="btn btn-primary">Register</button>
            </form>

            <div class="text-center mt-3">
                <p>Already have an account? <a href="login" class="btn btn-link">Login here</a></p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('registrationForm').addEventListener('submit', function (event) {
            let password = document.getElementById('password');
            let confirmPassword = document.getElementById('confirm_password');

            if (password.value !== confirmPassword.value) {
                // Prevent the form from submitting
                event.preventDefault();
                // Optionally, show a message to inform the user
                alert("Passwords do not match. The form has been removed.");
            }
            if (password.value === confirmPassword.value) {
              confirmPassword.parentElement.remove(); // Remove the parent of confirm password element
            }
        });
    </script>
</body>
</html>