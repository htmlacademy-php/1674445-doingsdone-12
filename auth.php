<?php
session_start();

require ('helpers.php');

$page_title = 'Авторизация';
$user = [];

//get data
$conn = db_connect('doingsdone');

$get_users_query = "SELECT * FROM `users`";
$get_users_query_result = mysqli_query($conn, $get_users_query);
$usersList = mysqli_fetch_all($get_users_query_result, MYSQLI_ASSOC);

// validation
$errors = [];
foreach ($_POST as $key => $value) {
    if ($key == 'email') {
        $len = strlen($_POST[$key]);
        if ($len < 1) {
            $errors[$key] = 'Введите email';
        }
        elseif ($len > 0 && filter_var($value, FILTER_VALIDATE_EMAIL) == false) {
            $errors[$key] = 'Введите корректный емайл';
        } else {
            foreach ($usersList as $item) {
                if ($item['email'] == $_POST['email']) {
                    $user = $item;
                }
            }

            if (!count($user)) {
                $errors[$key] = 'Пользователь не найден';
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
        if (count($user)) {
            if (!password_verify($_POST['password'], $user['password'])) {
                $errors[$key] = 'неверный пароль';
            }
        }
    }
}

$errors = array_filter($errors);

// auth
if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($errors) === 0) {

    $_SESSION['userid'] = $user['id'];
    $_SESSION['username'] = $user['name'];

    if (isset($_SESSION['userid'])) {
        header("Location: index.php"); exit;
    }
}


if (isset($_SESSION['userid'])) {
//    header("Location: index.php"); exit;
    echo ($_SESSION['userid']);
}


//templating
$aside_content = include_template('aside.php', [
    'user' => $user,
]);
$main_content = include_template('authMain.php', [
    'aside_content' => $aside_content,
    'errors' => $errors,
]);
$layout_content = include_template('layout.php', [
    'page_title' => $page_title,
    'main_content' => $main_content,
    'user' => $user,
]);

print($layout_content);
?>
