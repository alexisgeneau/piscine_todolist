<?php

require_once 'TaskManager.php';
require_once 'UserManager.php';

class Controller
{
    private static $taskManager;

    private static $userManager;

    public static function initUserManager()
    {
        if (!self::$userManager) {
            self::$userManager = new UserManager();
        }
    }

    public static function register()
    {
        self::initUserManager();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            try {
                self::$userManager->registerUser($username, $password);
                header('Location: /login');
                exit;
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }

        $loader = new \Twig\Loader\FilesystemLoader('../views');
        $twig = new \Twig\Environment($loader);

        echo $twig->render('register.twig');
    }

    public static function login()
    {
        self::initUserManager();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = self::$userManager->authenticate($username, $password);

            if ($user) {
                session_start();
                $_SESSION['user'] = $user;
                header('Location: /');
                exit;
            } else {
                echo "Nom d'utilisateur ou mot de passe incorrect.";
            }
        }

        $loader = new \Twig\Loader\FilesystemLoader('../views');
        $twig = new \Twig\Environment($loader);

        echo $twig->render('login.twig');
    }

    public static function logout()
    {
        session_start();
        session_destroy();
        header('Location: /');
        exit;
    }


    public static function init()
    {
        if (!self::$taskManager) {
            self::$taskManager = new TaskManager();
        }
    }

    public static function index()
    {
        session_start();
        self::init();

        $session = $_SESSION;

        $tasks = self::$taskManager->getAllTasks();

        if(count($tasks)) {
            if (isset($_SESSION['user'])) {
                $userId = $_SESSION['user']['id'];
                $tasks = array_filter($tasks, function ($task) use ($userId) {
                    return $task['is_public'] || $task['user_id'] === $userId;
                });
            } else {
                $tasks = array_filter($tasks, fn($task) => $task['is_public']);
            }
        }

        $loader = new \Twig\Loader\FilesystemLoader('../views');
        $twig = new \Twig\Environment($loader);

        echo $twig->render('index.twig', ['tasks' => $tasks, 'session' => $session]);
    }

    public static function addTask()
    {
        session_start();
        self::init();

        if (!empty($_POST['task_name'])) {
            $taskName = trim($_POST['task_name']);
            $isPublic = isset($_POST['is_public']) ? true : false;

            $userId = isset($_SESSION['user']) ? $_SESSION['user']['id'] : null;

            self::$taskManager->addTask($taskName, $isPublic, $userId);
        }

        header('Location: /');
        exit;
    }

    public static function markAsCompleted()
    {
        self::init();

        if (!empty($_GET['id'])) {
            $taskId = (int)$_GET['id'];
            self::$taskManager->updateTaskStatus($taskId, true);
        }

        header('Location: /');
        exit;
    }

    public static function uncompleteTask()
    {
        self::init();

        if (!empty($_GET['id'])) {
            $taskId = (int)$_GET['id'];
            self::$taskManager->updateTaskStatus($taskId, false);
        }

        header('Location: /');
        exit;
    }

    public static function deleteTask()
    {
        session_start();
        self::init();

        if (!empty($_GET['id'])) {
            $taskId = (int)$_GET['id'];

            if (isset($_SESSION['user'])) {
                $userId = $_SESSION['user']['id'];
                $task = self::$taskManager->getTaskById($taskId);

                if ($task && $task['user_id'] === $userId) {
                    self::$taskManager->deleteTask($taskId);
                }
            }
        }

        header('Location: /');
        exit;
    }
}
