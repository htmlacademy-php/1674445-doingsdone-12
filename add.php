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
if (isset($_FILES['file'])) {
    $file_name = $_FILES['file']['name'];
    $upload_path = __DIR__ . '/uploads/';
    $file_url = '/uploads/' . $file_name;
    move_uploaded_file($_FILES['file']['tmp_name'], $upload_path . $file_name);
}

function get_files_value($name) {
    if (isset($_FILES[$name])) {
        $file_name = $_FILES[$name]['name'];
        $file_url = '/uploads/' . $file_name;
        return compact('file_name', 'file_url');
    }
}

function validate_filled($name) {
    if (empty($_POST[$name])) {
        return "Это поле должно быть заполнено";
    }
}

$errors = [];
$rules = [
    'name' => function() {
        return validate_filled('name');
    },
    'project' => function() {
        return validate_filled('project');
    },
    'date' => function() {
        if (!is_date_valid($_POST['date'])) {
            return 'Выберите дату';
        }

        $taskExpireDate = date_create_from_format('Y-m-d', $_POST['date']);
        $curr_date = date_create('now');

        if ($taskExpireDate < $curr_date) {
            return 'Выберите корректную дату';
        }
    },
    'file' => function() {
        if (isset($_FILES['file'])) {
            if ($_FILES['file']['size'] > 2000000) {
                return "Максимальный размер файла: 2000Кб";
            }
        }
    }
];

foreach ($_POST as $key => $value) {
    if (isset($rules[$key])) {
        $rule = $rules[$key];
        $errors[$key] = $rule($value);
    }
}

foreach ($_FILES as $key => $value) {
    if (isset($rules[$key])) {
        $rule = $rules[$key];
        $errors[$key] = $rule($value);
    }
}

$errors = array_filter($errors);

// отправка запросов
if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($errors) === 0) {
    $curr_date = date_format(date_create('now'), 'Y-m-d');
    $add_task_query = "INSERT INTO `tasks` (publish_date, status, name, file_url, expire_date, user_id, category_id)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

    $name = get_post_val('name');
    $date = get_post_val('date');
    $project = get_post_val('project');
    $status = 0;
    $file_url = get_files_value('file')['file_url'];


    $stmp = db_get_prepare_stmt($conn, $add_task_query, [$curr_date, $status, $name, $file_url, $date, $user_id, $project]);
    $add_task_queryResult = mysqli_stmt_execute($stmp);

    if (!$add_task_queryResult) {
        $error = mysqli_error($conn);
        print("Ошибка MySQL: " . $error);
    } else {
        header("Location: index.php"); exit;
    }
}

// шаблонизация
$aside_content = include_template('aside.php', [
    'tasks_categories' => $tasks_categories,
    'tasks_list' => $tasks_list,
]);

$main_content = include_template('addTaskMain.php', [
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
