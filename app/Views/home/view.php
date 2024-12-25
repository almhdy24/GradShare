<?php
function timeAgo($timestamp)
{
  $timeDiff = time() - $timestamp; // Assuming $timestamp is in seconds
  if ($timeDiff < 60) {
    return "just now";
  } elseif ($timeDiff < 3600) {
    return floor($timeDiff / 60) . " minutes ago";
  } elseif ($timeDiff < 86400) {
    return floor($timeDiff / 3600) . " hours ago";
  } elseif ($timeDiff < 2592000) {
    return floor($timeDiff / 86400) . " days ago";
  } elseif ($timeDiff < 31536000) {
    return floor($timeDiff / 2592000) . " months ago";
  } else {
    return floor($timeDiff / 31536000) . " years ago";
  }
}
//the user who created the project
$currentUserId = $_SESSION["user"]["id"];
$projectUserId = $project["userId"];
?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Project Details</h5>
        </div>
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars(
              $project["projectName"]
            ); ?></h5>
            <p class="card-text"><?php echo htmlspecialchars(
              $project["projectDescription"]
            ); ?></p>
            <hr>
            <p class="card-text"><strong>Launch Date:</strong> <span id="createdAt"><?php echo timeAgo(
              $project["createdAT"]
            ); ?></span></p>
            <a href="/download?file=<?php echo urlencode(
              $project["filePath"]
            ); ?>" class="btn btn-primary">
                <i class="fas fa-download"></i> Download Project
            </a>
            <button id="shareButton" class="btn btn-secondary">
                <i class="fas fa-share"></i> Share
            </button>
        </div>
    </div>
<?php if ($projectUserId == $currentUserId): ?>
    <div class="mt-4">
        <h5>Administration Tools</h5>
        <div class="d-flex justify-content-between mt-2">
            <div>
                <button class="btn btn-warning" id="editProjectButton">
                    <i class="fas fa-edit"></i> Edit Project
                </button>
            </div>
            <div>
                <a href="/delete_project/<?php echo $project[
                  "id"
                ]; ?>" class="btn btn-danger" id="deleteProjectButton">
                    <i class="fas fa-trash"></i> Delete Project
                </a>
            </div>
        </div>
    </div>

    <script>
        // Function to create temporary notifications
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.innerText = message;
            notification.style.position = 'fixed';
            notification.style.bottom = '10px';
            notification.style.right = '10px';
            notification.style.backgroundColor = '#4CAF50'; // Green background
            notification.style.color = 'white';
            notification.style.padding = '10px';
            notification.style.borderRadius = '5px';
            notification.style.zIndex = '1000';
            document.body.appendChild(notification);
            // Remove notification after 3 seconds
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 3000);
        }

        // Event listener for the Edit Project button
        document.getElementById('editProjectButton').addEventListener('click', function() {
            showNotification('This feature is unavailable.');
        });

        // Event listener for delete confirmation
        document.getElementById('deleteProjectButton').addEventListener('click', function(event) {
            const confirmation = confirm('This action cannot be undone. Proceed with deletion?');
            if (!confirmation) {
                event.preventDefault(); // Prevent navigation if not confirmed
            }
        });

        // Event listener for share link button
        document.getElementById('shareButton').addEventListener('click', async function() {
            const shareLink = window.location.href; // The URL to be shared
            try {
                await navigator.clipboard.writeText(shareLink);
                showNotification('Share link copied to clipboard!');
            } catch (err) {
                alert('Could not copy text. Please try again.');
                console.error('Copy failed: ', err);
            }
        });
    </script>
<?php endif; ?>
