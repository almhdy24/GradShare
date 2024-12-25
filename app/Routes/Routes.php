<?php

// Import necessary classes
use Almhdy\Simy\Core\Router;
use Almhdy\Simy\Controllers\HomeController;
use Almhdy\Simy\Controllers\UserController;

// Create a new router instance
$router = new Router();

// Define routes for the HomeController
$router->get("/", [HomeController::class, "index"]);
$router->get("/about", [HomeController::class, "about"]);
$router->get("/download", [HomeController::class, "download"]);
$router->get("/profile", [HomeController::class, "profile"]);
$router->get("/delete_project/{id}", [HomeController::class, "delete"]);
$router->get("/view/{id}", [HomeController::class, "viewProject"]);

// Define routes for the UserController
$router->get("/logout", [UserController::class, "logout"]);
$router->map("/login", [UserController::class, "login"], ["GET", "POST"]);
$router->map("/register", [UserController::class, "register"], ["GET", "POST"]);

// Define routes for file upload
$router->map("/upload", [HomeController::class, "upload"], ["GET", "POST"]);

// Return the configured router
return $router;
