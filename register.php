<?php
session_start();

require ('helpers.php');
$page_title = 'Регистрация';

//get data
$conn = db_connect('doingsdone');

$get_users_query = "SELECT * FROM `users`";
$get_users_query_result = mysqli_query($conn, $get_users_query);
$usersList = mysqli_fetch_all($get_users_query_result, MYSQLI_ASSOC);

// validation
$errors = [];

foreach ($_POST as $key => $value) {
    if ($key == 'email') {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) == false) {
            $errors[$key] = 'Введите корректный емайл';
        } else {
            foreach ($usersList as $user) {
                if ($user['email'] == $_POST['email']) {
                    $errors[$key] = 'Этот емайл занят, выберите другой';
                }
            }
        }
    }

    if ($key == 'password') {
        $len = strlen($_POST[$key]);
        if ($len < 1) {
            $errors[$key] = 'Введите пароль';
        }
        if ($len < 8 and $len > 0) {
            $errors[$key] = 'Длина пароля должна быть не менее 8 символов';
        }
    }
    if ($key == 'name') {
        $len = strlen($_POST[$key]);
        if ($len < 1) {
            $errors[$key] = 'Введите логин';
        } else {
            foreach ($usersList as $user) {
                if ($user['name'] == $_POST['name']) {
                    $errors[$key] = 'Этот логин занят, выберите другой';
                }
            }
        }

    }
}

$errors = array_filter($errors);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($errors) === 0) {
    $email = get_post_val('email');
    $name = get_post_val('name');
    $password_hash = password_hash(get_post_val('password'), PASSWORD_DEFAULT);

    $add_user_query = "INSERT INTO `users` (email, name, password)
                  VALUES (?, ?, ?)";

    $stmp = db_get_prepare_stmt($conn, $add_user_query, [$email, $name, $password_hash]);
    $add_user_query_res = mysqli_stmt_execute($stmp);

    if (!$add_user_query_res) {
        $error = mysqli_error($conn);
        print("Ошибка MySQL: " . $error);
    } else {
        header("Location: index.php"); exit;
    }
}

//templating
$aside_content = include_template('aside.php');
$main_content = include_template('registerMain.php', [
    'aside_content' => $aside_content,
    'errors' => $errors,
]);
$layout_content = include_template('layout.php', [
    'page_title' => $page_title,
    'main_content' => $main_content,
]);

print($layout_content);
?>
