<?php
namespace Almhdy\Simy\Controllers;
use Almhdy\Simy\Core\Controller;
use AsyncFileUploader\AsyncFileUploader;
use Almhdy\Simy\Core\Response;
use Almhdy\Simy\Core\Pagination;
use Almhdy\JsonShelter\JsonShelter;
use Almhdy\Simy\Core\Validation\Validator;
use Almhdy\Simy\Core\Session\SessionManager;
use Exception;

class HomeController extends Controller
{
  private JsonShelter $db;
  private SessionManager $sessionManager;
  public function __construct()
  {
    // Create a new JsonShelter instance
    $baseDir = $this->env("json_db"); // Base directory path
    $secretKey = $this->env("SECRET_KEY"); // Your secret key
    $secretIv = $this->env("SECRET_IV"); // Your secret IV

    $this->db = new JsonShelter($baseDir, $secretKey, $secretIv);
    // Disable Encryption
    // $this->db->disableEncryption();

    $this->sessionManager = new SessionManager(true); // Enable cookies if desired
    $this->sessionManager->startSession(); // Start the session

    // Check if the user is logged in
    if (
      !$this->request()
        ->user()
        ->isLoggedIn()
    ) {
      // Capture the intended URI access
      $requestedUri = $this->request()->getUri();

      // Save the URI as __next in the session
      $_SESSION["__next"] = $requestedUri;

      // Redirect to the login page with the __next parameter
      return $this->redirect("/login?__next=" . urlencode($requestedUri));
    }
  }
  public function index()
  {
    // Load user ID from session
    $userData = $this->sessionManager->getSessionData("user");

    // Check if userData exists and has an ID
    if ($userData && isset($userData["id"])) {
      $userId = $userData["id"];

      // Fetch projects for the current user
      $projects = $this->db->where("projects", ["userId" => $userId]);

      // Ensure $projects is an array, even if empty
      if ($projects === null) {
        $projects = [];
      }
    } else {
      // Handle case where user data isn't available
      $projects = []; // No user data, set projects to empty array
    }
    // Check if the projects array is null or empty
    if (is_null($projects) || empty($projects)) {
      $projects = []; // Set to empty array if null or empty
    }

    // Determine pagination parameters
    $totalItems = count($projects); // Total number of projects
    $itemsPerPage = 3; // Define how many projects to show per page
    $currentPage = max(1, isset($_GET["page"]) ? (int) $_GET["page"] : 1); // Ensure current page is at least 1

    // Instantiate the Pagination class with the relevant parameters
    $pagination = new Pagination($totalItems, $itemsPerPage, $currentPage);

    // Calculate the offset for slicing the projects array
    $offset = ($currentPage - 1) * $itemsPerPage;

    // Extract the relevant subset of projects for the current page
    $pagedProjects = array_slice($projects, $offset, $itemsPerPage);

    // Render the header with the page title
    $this->view("templates/header", ["title" => "GradShare"]);

    // Render the main content with paged projects and pagination data
    $this->view("home/index", [
      "projects" => $pagedProjects,
      "pagination" => $pagination,
      "noProjectsMessage" => $totalItems === 0 ? "No projects found." : null, // Optional message
    ]);

    // Render the footer after displaying the content
    $this->view("templates/footer");
  }
  public function upload()
  {
    $method = $this->request()->getMethod();

    if ($method === "GET") {
      // Load the upload form
      $this->view("templates/header", [
        "title" => "GradShare - Upload Your Project",
      ]);
      $this->view("home/upload");
      $this->view("templates/footer");
      return; // Ensure no further processing occurs
    }

    if ($method === "POST") {
      $uploadDir = $this->env("UPLOAD_PATH"); // Specify your upload directory
      $allowedTypes = [
        "application/zip",
        "application/x-zip-compressed",
        "application/gzip",
        "application/x-gzip",
        "application/x-tar",
        "application/x-bzip",
        "application/x-bzip2",
        "application/x-tar.gz",
      ];
      $maxFileSize = 50 * 1024 * 1024; // Maximum file size of 50MB

      try {
        // Initialize the file uploader
        $uploader = new AsyncFileUploader(
          $uploadDir,
          $allowedTypes,
          $maxFileSize
        );
        $response = $uploader->upload();

        if (!isset($response["success"]) || !$response["success"]) {
          throw new Exception("File upload failed.");
        }

        // Retrieve user input data with defaults
        $data = [
          "projectName" => $this->request()->input("project_name", ""),
          "projectDescription" => $this->request()->input(
            "project_description",
            ""
          ),
          "filePath" => $response["path"], // Use the path from the response
          "createdAT" => time(),
          "userId" => $this->sessionManager->getSessionData("user")["id"],
        ];

        // Validate user input using a dedicated method
        $errors = $this->validateUploadData($data);
        if (!empty($errors)) {
          return Response::json($errors);
        }

        // Insert the project data into the database
        $this->db->create("projects", $data);

        return Response::json(["success" => "Project uploaded successfully."]);
      } catch (Exception $e) {
        // Handle exceptions and log the error
        error_log($e->getMessage());
        return Response::json(["error" => $e->getMessage()], 500);
      }
    }
  }

  // Dedicated validation method
  private function validateUploadData($data)
  {
    $validator = new Validator();
    $isValid = $validator->validate($data, [
      "projectName" => "required|string|max:255",
      "projectDescription" => "required|string|max:1000",
    ]);

    return $isValid ? [] : $validator->getErrors();
  }

  public function viewProject(?int $id)
  {
    // Validate project ID
    if ($id === null || $id <= 0) {
      $this->view("templates/header", [
        "title" => "GradShare - Invalid Project ID",
      ]);
      $this->view("home/error", ["message" => "Invalid id"]);
      $this->view("templates/footer");
      return;
    }

    // Attempt to read the project from the database
    $project = $this->db->read("projects", $id);

    // Check if the project exists
    if ($project === null) {
      $this->view("errors/404");
      return;
    }

    // Render the project details
    $this->view("templates/header", ["title" => "GradShare - Project Details"]);
    $this->view("home/view", ["project" => $project]);
    $this->view("templates/footer");
  }
  public function download()
  {
    // Check for the 'file' parameter
    if (isset($_GET["file"])) {
      $file = $_GET["file"];

      // Sanitize the file name
      $filePath = basename($file);
      $fullPath = $this->env("UPLOAD_PATH") . "/" . $filePath;

      // Check if the file exists
      if (file_exists($fullPath)) {
        // Clear the output buffer if any
        if (ob_get_level()) {
          ob_end_clean(); // End the output buffering to prevent issues
        }

        // Set headers to force download
        header("Content-Description: File Transfer");
        header("Content-Type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . $filePath . '"');
        header("Expires: 0");
        header("Cache-Control: must-revalidate");
        header("Pragma: public");
        header("Content-Length: " . filesize($fullPath));

        // Read the file and send it to the output
        readfile($fullPath);
        exit();
      } else {
        // File not found
        http_response_code(404);
        $this->view("errors/404");
        exit(); // Ensure no further output
      }
    } else {
      // No file parameter given
      http_response_code(400);
      $this->view("errors/404");
      exit(); // Ensure no further output
    }
  }
  public function profile()
  {
    // Attempt to retrieve user session data
    $sessionData = $this->sessionManager->getSessionData("user");

    // Check if user session data exists
    if (!$sessionData || !isset($sessionData["id"])) {
      // Redirect to the login page or show an error message
      $this->view("templates/header", ["title" => "Access Denied"]);
      $this->view("home/error", [
        "message" => "You must be logged in to access your profile.",
      ]);
      $this->view("templates/footer");
      return; // Exit the method early
    }

    $userId = $sessionData["id"];

    try {
      // Retrieve user data
      $user = $this->fetchUserById($userId);

      // Render the profile view
      $this->renderProfileView($user);
    } catch (Exception $e) {
      $this->handleError($e);
    }
  }
  private function fetchUserById($userId)
  {
    // Retrieve user data from database
    $user = $this->db->where("users", ["id" => $userId]);

    // Check if user exists
    if (empty($user)) {
      throw new Exception("User not found.");
    }

    // Assuming only one user row expected, use array destructuring
    return $user[0];
  }

  private function renderProfileView($user)
  {
    $this->view("templates/header", ["title" => "GradShare - User Profile"]);
    $this->view("home/profile", ["user" => $user]);
    $this->view("templates/footer");
  }

  private function handleError(Exception $e)
  {
    // Log the error message for debugging
    error_log($e->getMessage());

    // Render a user-friendly error view
    $this->view("templates/header", ["title" => "Error"]);
    $this->view("home/error", [
      "message" =>
        "An error occurred while retrieving your profile. Please try again.",
    ]);
    $this->view("templates/footer");
  }
  public function delete(int $id)
  {
    $userId = $this->sessionManager->getSessionData("user")["id"];

    // Initialize message variable
    $message = "";

    try {
      $project = $this->db->read("projects", $id);

      // Check if the project exists
      if (!$project) {
        $message = "Project not found.";
        throw new Exception($message);
      }

      // Validate user ownership
      if ($project["userId"] !== $userId) {
        $message = "Unauthorized access: You do not own this project.";
        throw new Exception($message);
      }

      // Sanitize the file name
      $filePath = basename($project["filePath"]);
      $fullPath = $this->env("UPLOAD_PATH") . "/" . $filePath;

      // Check if the file exists before attempting to delete
      if (file_exists($fullPath)) {
        if (!unlink($fullPath)) {
          $message = "Error deleting file: " . $fullPath;
          throw new Exception($message);
        }
      } else {
        $message = "File does not exist: " . $fullPath;
        throw new Exception($message);
      }

      // Delete the project from the database
      if (!$this->db->delete("projects", $id)) {
        $message = "Failed to delete project from database.";
        throw new Exception($message);
      }

      $message = "Project deleted successfully.";
      error_log("Project deleted successfully: " . $id);
    } catch (Exception $e) {
      // Log the error message
      error_log($e->getMessage());
      $message = $e->getMessage(); // Get the error message for the view
    }

    // Load the info view with the message
    $this->view("templates/header", ["title" => "GradShare - Info"]);
    $this->view("home/info", ["message" => $message]);
    $this->view("templates/footer");
  }
  public function about()
  {
    $this->view("templates/header", ["title" => "GradShare - About"]);
    $this->view("home/about");
    $this->view("templates/footer");
  }
}
