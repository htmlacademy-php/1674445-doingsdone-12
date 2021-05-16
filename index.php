<?php
session_start();

require ('helpers.php');

$page_title = 'Главная';

$show_complete_tasks = filter_input(INPUT_GET, 'show_completed', FILTER_SANITIZE_STRING);

if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid'];
} else {
    header("Location: guest.php"); exit;
}

// db queries
$conn = db_connect('doingsdone');

$get_categories_sql = "SELECT * FROM `categories` WHERE `user_id` = ?";
$get_categories_stmt = mysqli_prepare($conn, $get_categories_sql);
mysqli_stmt_bind_param($get_categories_stmt, 'i', $user_id);
mysqli_stmt_execute($get_categories_stmt);

$get_categories_result = mysqli_stmt_get_result($get_categories_stmt);
$tasks_categories = mysqli_fetch_all($get_categories_result, MYSQLI_ASSOC);

$tasks_filter_date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING);
if ($tasks_filter_date == 'outdated') {
    $get_tasks_sql = "SELECT * FROM `tasks` WHERE `user_id` = ? and expire_date < CURDATE()";
} elseif ($tasks_filter_date == 'today') {
    $get_tasks_sql = "SELECT * FROM `tasks` WHERE `user_id` = ? and
                            (expire_date = CURRENT_DATE())";
} elseif ($tasks_filter_date == 'tomorrow') {
    $get_tasks_sql = "SELECT * FROM `tasks` WHERE `user_id` = ? and
                            (expire_date < DATE_ADD(NOW(), INTERVAL 1 DAY) and expire_date > CURDATE())";
} else {
    $get_tasks_sql = "SELECT * FROM `tasks` WHERE `user_id` = ?";
}
$get_tasks_stmt = mysqli_prepare($conn, $get_tasks_sql);
mysqli_stmt_bind_param($get_tasks_stmt, 'i', $user_id);
mysqli_stmt_execute($get_tasks_stmt);
$get_tasks_res = mysqli_stmt_get_result($get_tasks_stmt);
$tasks_list = mysqli_fetch_all($get_tasks_res, MYSQLI_ASSOC);
// db queries end

// get an id of current category from url param
$current_category_id = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_NUMBER_INT);

// Search

$search_query = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_URL);

if ($search_query) {
    $sql = "SELECT * FROM `tasks` WHERE MATCH(name) AGAINST(?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $search_query);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $tasks_list = mysqli_fetch_all($res, MYSQLI_ASSOC);
}

// show 404 if count of tasks in the current category < 1
$set_not_found = true;

foreach ($tasks_list as $task) {
    if ($task['category_id'] === $current_category_id) {
        $set_not_found = false;
    }
}

// invert tasks status
// get data from url param
$current_task_id = filter_input(INPUT_GET, 'task_id', FILTER_SANITIZE_NUMBER_INT);
$current_task_status = filter_input(INPUT_GET, 'check', FILTER_SANITIZE_NUMBER_INT);

if ($current_task_id) {
    if ($current_task_status == 1) {
        $status = 0;
    } else {
        $status = 1;
    }
    $sql = "UPDATE `tasks` SET `status` = ? WHERE `id` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $status, $current_task_id);
    mysqli_stmt_execute($stmt);
    header("Location: index.php"); exit;
}
// invert tasks status end

$aside_content = include_template('aside.php', [
    'tasks_categories' => $tasks_categories,
    'tasks_list' => $tasks_list,
]);

$main_content = include_template('main.php', [
    'show_complete_tasks' => $show_complete_tasks,
    'tasks_categories' => $tasks_categories,
    'tasks_list' => $tasks_list,
    'aside_content' => $aside_content,
    'current_category_id' => $current_category_id,
    'tasks_filter_date' => $tasks_filter_date,
]);

$layout_content = include_template('layout.php', [
    'page_title' => $page_title,
    'main_content' => $main_content,
]);


print($layout_content);

?>
