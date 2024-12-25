<?php
namespace Almhdy\Simy\Controllers;
use Almhdy\Simy\Core\Controller;
use Almhdy\Simy\Core\Validation\Validator;
use Almhdy\Simy\Core\Request;
use Almhdy\Simy\Core\Password;
use Almhdy\Simy\Core\Session\SessionManager;
use Almhdy\JsonShelter\JsonShelter;

class UserController extends Controller
{
  private JsonShelter $db;
  public function __construct()
  {
    // Create a new JsonShelter instance
    $baseDir = $this->env("json_db"); // Base directory path
    $secretKey = $this->env("SECRET_KEY"); // Your secret key
    $secretIv = $this->env("SECRET_IV"); // Your secret IV

    $this->db = new JsonShelter($baseDir, $secretKey, $secretIv);
    // Disable Encryption
    // $this->db->disableEncryption();
  }
  public function register()
  {
    // Check if the user is already logged in
    if (
      $this->request()
        ->user()
        ->isLoggedIn()
    ) {
      // Redirect user to the last visited page or homepage
      $previousUrl = $_SERVER["HTTP_REFERER"] ?? "/";
      return $this->redirect($previousUrl);
    }
    // http method
    $method = $this->request()->getMethod();
    // Check if the request method is GET
    if ($method === "GET") {
      $this->view("user/register");
      return; // Ensure we exit after processing GET
    }

    // Check if the request method is POST
    if ($method === "POST") {
      // Prepare user input with defaults
      $data = [
        "username" => $this->request()->input("username") ?? "",
        "email" => $this->request()->input("email") ?? "",
        "password" => $this->request()->input("password") ?? "",
      ];

      // Instantiate Validator and validate data
      $validator = new Validator();
      $isValid = $validator->validate($data, [
        "username" => "required|string|min:2|max:32",
        "password" => "required|min:6|max:32",
        "email" => "required|email|min:8|max:56",
      ]);

      // Handle validation failure
      if (!$isValid) {
        // Return validation errors and reload the form
        $errors = $validator->getErrors();
        $this->view("user/register", ["errors" => $errors, "data" => $data]);
        return;
      }

      // Hash the new password using the Password class
      $data["password"] = (new Password())->hash($data["password"]);
      // Add data
      $data["createdAt"] = time();
      try {
        $this->db->create("users", $data);
        $this->view("user/success", $data);
      } catch (Exception $e) {
        // Log the exception details for debugging
        error_log("Registration error: " . $e->getMessage());
        // Show a generic error message to the user
        $this->view("errors/error", [
          "message" => "An error occurred during registration.",
        ]);
      }
    }
  }
  public function login()
  {
    // Check if the user is already logged in
    if (
      $this->request()
        ->user()
        ->isLoggedIn()
    ) {
      // Redirect user to the last visited page or homepage,
      // but not to the login page to avoid redirect loops
      $previousUrl = $_SERVER["HTTP_REFERER"] ?? "/";
      if ($previousUrl !== "/login") {
        return $this->redirect($previousUrl);
      } else {
        return $this->redirect("/");
      }
    }

    // Load login view if the request method is GET
    if ($this->request()->getMethod() === "GET") {
         // Capture the next parameter for redirection after login
        if (isset($_GET['__next'])) {
            $_SESSION['redirect_after_login'] = $_GET['__next'];
        }
      return $this->view("user/login");
    }

    // Process login if the request method is POST
    if ($this->request()->getMethod() === "POST") {
      // Retrieve user input data with defaults
      $data = [
        "username" => $this->request()->input("username") ?? "",
        "password" => $this->request()->input("password") ?? "",
      ];

      // Validate user input
      $validator = new Validator();
      $isValid = $validator->validate($data, [
        "username" => "string|min:2|max:32",
        "password" => "required|min:6|max:32",
      ]);

      // Process login if validation passes
      if ($isValid) {
        try {
          // Fetch user by username
          $user = $this->db->where("users", ["username" => $data["username"]]);

          // Verify user and password
          if (
            $user &&
            Password::verify($data["password"], $user[0]["password"])
          ) {
            // User authentication successful
            $sessionManager = new SessionManager();
            $sessionManager->startSession();

            // Store user data in session
            $sessionManager->setSessionData("user", $user[0]);
            $sessionManager->setSessionData("is_logged_in", true);

            // Redirect user to the last visited page or homepage
            $previousUrl = $_SESSION["redirect_after_login"] ?? "/";
            unset($_SESSION["redirect_after_login"]); // Clear the session variable after use
            return $this->redirect($previousUrl);
          } else {
            return $this->view("user/login", [
              "errors" => "Invalid Credentials",
            ]);
          }
        } catch (Exception $e) {
          // Handle exception (log it, notify user, etc.)
          return $this->view("user/login", [
            "error" => "An error occurred while logging in.",
          ]);
        }
      } else {
        return $this->view("user/login", ["errors" => $validator->getErrors()]);
      }
    }
  }
  // Function to handle login errors
  private function handleLoginError($message)
  {
    $this->view("user/login", ["errors" => $message]);
  }

  public function logout()
  {
    $this->request()
      ->user()
      ->logout();
    $this->redirect("/login");
  }
}
