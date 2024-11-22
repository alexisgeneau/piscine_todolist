<?php

require_once '../src/Controller.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/':
        Controller::index();
        break;

    case '/task/add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Controller::addTask();
        } else {
            http_response_code(405);
            echo "Méthode non autorisée.";
        }
        break;

    case '/task/complete':
        Controller::markAsCompleted();
        break;

    case '/task/uncomplete':
        Controller::uncompleteTask();
        break;

    case '/task/delete':
        Controller::deleteTask();
        break;

    case '/register':
        Controller::register();
        break;

    case '/login':
        Controller::login();
        break;

    case '/logout':
        Controller::logout();
        break;

    default:
        http_response_code(404);
        echo "Page non trouvée.";
        break;

}
