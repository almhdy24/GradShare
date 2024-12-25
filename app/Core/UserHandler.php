<?php
namespace Almhdy\Simy\Core;

use Almhdy\Simy\Core\Session\SessionManager;

class UserHandler
{
  protected ?array $userData;
  protected SessionManager $session;

  public function __construct()
{
    // Initialize a session object
    $this->session = new SessionManager();
    $this->session->startSession();
    
    // Retrieve user data from session
    $userData = $this->session->getSessionData("user");

    // If it's not an array, set to default array
    $this->userData = is_array($userData) ? $userData : [];
}
  public function getUserData(): ?array
  {
    return $this->userData;
  }

  public function getUserId(): ?int
  {
    return $this->userData["id"] ?? null;
  }

  public function getUserName(): ?string
  {
    return $this->userData["name"] ?? null;
  }

  public function logout(): void
  {
    $this->session->destroySession();
    $this->userData = null;
  }

  public function isLoggedIn(): bool
  {
    $isLoggedIn = $this->session->getSessionData("is_logged_in");
    error_log("is_logged_in: " . var_export($isLoggedIn, true)); // Log the value for debugging
    return !empty($isLoggedIn) && $isLoggedIn === true; // Check as boolean directly
  }

  // Updates user data in the session
  public function updateUserData(array $data): void
  {
    $this->userData = array_merge($this->userData ?? [], $data);
    $this->session->setSessionData("user", json_encode($this->userData));
  }

  // Checks if the user has a specific role
  public function hasRole(string $role): bool
  {
    return in_array($role, $this->userData["roles"] ?? []);
  }

  // Retrieve user email
  public function getUserEmail(): ?string
  {
    return $this->userData["email"] ?? null;
  }

  // Method to set some user preferences
  public function setUserPreference(string $key, $value): void
  {
    if ($this->userData) {
      $this->userData["preferences"][$key] = $value;
      $this->session->setSessionData("user", json_encode($this->userData));
    }
  }

  // You can add more methods as needed for functionality
}
