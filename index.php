<?php
require ('helpers.php');

$pageTitle = 'Главная';

$show_complete_tasks = rand(0, 1);

session_start();

if (isset($_SESSION['userid'])) {
    $userId = $_SESSION['userid'];
} else {
    header("Location: auth.php"); exit;
}

// db queries
$conn = mysqli_connect('127.0.0.1', 'mysql', 'mysql', 'doit');
if ($conn === false) {
    print_r('DB connection error' . mysqli_connect_error());
}

$getCategoriesSql = "SELECT * FROM `categories` WHERE `user_id` = ?";
$getCategoriesStmt = mysqli_prepare($conn, $getCategoriesSql);
mysqli_stmt_bind_param($getCategoriesStmt, 'i', $userId);
mysqli_stmt_execute($getCategoriesStmt);
$getCategoriesRes = mysqli_stmt_get_result($getCategoriesStmt);
$tasksCategories = mysqli_fetch_all($getCategoriesRes, MYSQLI_ASSOC);

$getTasksSql = "SELECT * FROM `tasks` WHERE `user_id` = ?";
$getTasksStmt = mysqli_prepare($conn, $getTasksSql);
mysqli_stmt_bind_param($getTasksStmt, 'i', $userId);
mysqli_stmt_execute($getTasksStmt);
$getTasksRes = mysqli_stmt_get_result($getTasksStmt);
$tasksList = mysqli_fetch_all($getTasksRes, MYSQLI_ASSOC);
// db queries end

function getTacksCount(array $tasksList = [], int $taskCategoryId = 0) {
    $tasksCount = 0;

    foreach ($tasksList as $task) {
        if ($task['category_id'] == $taskCategoryId) {
            $tasksCount++;
        }
    }

    return $tasksCount;
}

// get an id of current category from url param
$currentCategoryId = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_NUMBER_INT);

// Search

$searchQuery = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_URL);

if ($searchQuery) {
    $sql = "SELECT * FROM `tasks` WHERE MATCH(name) AGAINST(?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $searchQuery);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $tasksList = mysqli_fetch_all($res, MYSQLI_ASSOC);
}

// show 404 if count of tasks in the current category < 1
$setNotFound = true;
foreach ($tasksList as $task) {
    if ($task['category_id'] === $currentCategoryId) {
        $setNotFound = false;
    }
}

if ($currentCategoryId !== null && $setNotFound) {
    http_response_code(404);
    echo "<h1>404 Not Found</h1>";
    echo "The page that you have requested could not be found.";
    exit();
}

$asideContent = include_template('aside.php', [
    'tasksCategories' => $tasksCategories,
    'tasksList' => $tasksList,
]);

$mainContent = include_template('main.php', [
    'show_complete_tasks' => $show_complete_tasks,
    'tasksCategories' => $tasksCategories,
    'tasksList' => $tasksList,
    'asideContent' => $asideContent,
    'currentCategoryId' => $currentCategoryId,
]);

$layout_content = include_template('layout.php', [
    'pageTitle' => $pageTitle,
    'mainContent' => $mainContent,
]);


print($layout_content);

?>
