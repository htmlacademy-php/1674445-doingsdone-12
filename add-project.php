<?php
session_start();

require ('helpers.php');

$page_title = 'Новый таск';

$user_id = check_user_session();

// db queries
$conn = db_connect('doingsdone');

$get_categories_sql = "SELECT * FROM `categories` WHERE `user_id` = ?";
$get_categories_stmt = mysqli_prepare($conn, $get_categories_sql);
mysqli_stmt_bind_param($get_categories_stmt, 'i', $user_id);
mysqli_stmt_execute($get_categories_stmt);
$get_categories_result = mysqli_stmt_get_result($get_categories_stmt);
$tasks_categories = mysqli_fetch_all($get_categories_result, MYSQLI_ASSOC);

$get_tasks_sql = "SELECT * FROM `tasks` WHERE `user_id` = ?";
$get_task_stmt = mysqli_prepare($conn, $get_tasks_sql);
mysqli_stmt_bind_param($get_task_stmt, 'i', $user_id);
mysqli_stmt_execute($get_task_stmt);
$get_tasks_res = mysqli_stmt_get_result($get_task_stmt);
$tasks_list = mysqli_fetch_all($get_tasks_res, MYSQLI_ASSOC);
// db queries end

// обработка формы

$errors = [];
foreach ($tasks_categories as $category) {
    if ($category['cat_name'] == get_post_val('name')) {
        $errors['name'] = 'Название проекта занято';
    }
}
foreach ($_POST as $key => $value) {
    if ($key == 'name') {
        $len = strlen($_POST[$key]);
        if ($len < 1) {
            $errors[$key] = 'Введите название проекта';
        }
    }
}

$errors = array_filter($errors);

// отправка запросов
if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($errors) === 0) {
    $add_category_query = "INSERT INTO `categories` (cat_name, user_id)
                  VALUES (?, ?)";
    $name = get_post_val('name');
    $stmp = db_get_prepare_stmt($conn, $add_category_query, [$name, $user_id]);
    $add_category_query_result = mysqli_stmt_execute($stmp);

    if (!$add_category_query_result) {
        $error = mysqli_error($conn);
        print("Ошибка MySQL: " . $error);
    }
}

// шаблонизация
$aside_content = include_template('aside.php', [
    'tasks_categories' => $tasks_categories,
    'tasks_list' => $tasks_list,
]);

$main_content = include_template('addProject.php', [
    'tasks_categories' => $tasks_categories,
    'tasks_list' => $tasks_list,
    'aside_content' => $aside_content,
    'errors' => $errors,
]);

$layout_content = include_template('layout.php', [
    'page_title' => $page_title,
    'main_content' => $main_content,
]);

print($layout_content);

?>
