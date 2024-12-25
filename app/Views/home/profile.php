    <style>
        body {
            background-color: #f8f9fa;
        }
        .profile-container {
            max-width: 500px;
            margin: auto;
            margin-top: 50px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .logout-button {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="profile-container text-center">
    <h2>User Profile</h2>
<?php // Format the createdAt timestamp into a readable date
// Format the createdAt timestamp into a readable date
// Format the createdAt timestamp into a readable date
$formattedDate = date("F j, Y, g:i a", $user["createdAt"]); ?>
    
    <div class="mt-4">
        <h4>Username: <?php echo htmlspecialchars($user["username"]); ?></h4>
        <p>Email: <?php echo htmlspecialchars($user["email"]); ?></p>
        <p>Account Created: <?php echo $formattedDate; ?></p>
    </div>

    <div class="logout-button">
        <form action="<?= $this->env(
                      "base_url"
                    ) ?>logout" method="get">
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
</div>