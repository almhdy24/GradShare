<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $this->env('base_url') ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $this->env('base_url') ?>assets/css/styles.css"> <!-- Custom CSS for additional styling -->
    <title>GradShare - Registration Successful</title>
    <style>
        body {
            background-color: #f8f9fa; /* Light background color */
            font-family: 'Arial', sans-serif; /* Clean font */
        }
        .alert {
            border-radius: 10px; /* Rounded corners for alert */
        }
        .btn-primary {
            border-radius: 50px; /* Rounded button */
            padding: 10px 20px; /* Button padding */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="alert alert-success text-center shadow-sm" role="alert">
                    <h4 class="alert-heading">Your Account Has Been Successfully Created!</h4>
                    <p>We appreciate your registration. You may now log in to access your account.</p>
                    <a href="login" class="btn btn-primary">Login Here</a>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= $this->env('base_url') ?>assets/js/bootstrap.bundle.min.js"></script> <!-- Optional JavaScript -->
</body>
</html>