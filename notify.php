<?php
require_once 'vendor/autoload.php';
require_once 'DbHelper.php';
require_once 'helpers.php';

$dbHelper = new DbHelper("mysql", "mysql", "127.0.0.1", "doit");

$tasksData = null;

if (!$dbHelper->getLastError()) {
    $tasksData = mysqli_fetch_all($dbHelper->executeQuery(
        "SELECT u.id, u.email, u.name, t.name, t.user_id, t.expire_date, t.id as task_id
            FROM users u
            JOIN tasks t
            ON u.id = t.user_id
            WHERE t.status = 0
            AND expire_date = CURRENT_DATE();"));
}
else {
    print($dbHelper->getLastError());
}

if (!$dbHelper->getLastError()) {
    $tasksByUser = mysqli_fetch_all($dbHelper->executeQuery(
        'SELECT u.id, u.email, u.name, t.name, t.user_id, t.expire_date, t.id as task_id
            FROM users u
            JOIN tasks t
            ON u.id = t.user_id
            WHERE t.status = 0
            AND expire_date = CURRENT_DATE()'
    ));
}
else {
    print($dbHelper->getLastError());
}

$notificationData = array();

//print '<h2>$tasksData</h2>';
//dump($tasksData);
//
//$notificationDataSchema = array(
//    'oleg' => array(
//        'name' => 'Oleg',
//        'email' => 'umkasanki@gmail.com',
//        'tasks' => array(
//            array(
//                'task name',
//                'task date',
//            ),
//            array(
//                'task 2 name',
//                'task 2 date',
//            ),
//        )
//    ),
//    'denis' => array(
//        'name' => 'Denis',
//        'email' => 'denis@tishkin.com',
//        'tasks' => array(
//            array(
//                'task 3 name',
//                'task 3 date',
//            ),
//            array(
//                'task 5 name',
//                'task 5 date',
//            ),
//        )
//    ),
//);
//
//$userArr = array(
//    ('user' . '3') => array(
//        'name' => 'Roma',
//        'email' => 'roma@tishkin.com',
//        'tasks' => array(
//            array(
//                'task 3 name',
//                'task 3 date',
//            ),
//            array(
//                'task 5 name',
//                'task 5 date',
//            ),
//        )
//    ),
//);
//
//$notificationDataSchema = array_merge($notificationDataSchema, array(
//    ('user' . '3') => array(
//        'name' => 'Roma',
//        'email' => 'roma@tishkin.com',
//        'tasks' => array(
//            array(
//                'task 3 name',
//                'task 3 date',
//            ),
//            array(
//                'task 5 name',
//                'task 5 date',
//            ),
//        )
//    ),
//));
//
//print '<h2>$notificationDataSchema</h2>';
//dump($notificationDataSchema);
//
//foreach ($notificationDataSchema as $key => $value) {
//    print '<h3>new email</h3>';
//    print '<div>' . $value['name'] . '</div>';
//    print '<div>' . $value['email'] . '</div>';
//
//    foreach ($value['tasks'] as $task) {
//        print '<li>' . $task[0] . '</li>';
//        print '<li>' . $task[1] . '</li>';
//        print '<br>';
//    }
//    print '<hr>';
//}

//dump($tasksData);

foreach ($tasksData as $taskLine) {
    $userHandler = 'user' . $taskLine[0];
    if (!array_key_exists($userHandler, $tasksData)) {
        $notificationData = array_merge($notificationData, array(
            $userHandler => array(),
        ));
        $notificationData[$userHandler]['email'] = $taskLine[1];
        $notificationData[$userHandler]['name'] = $taskLine[2];
    }
    $notificationData[$userHandler]['tasks'][] = array(
        $taskLine[3],
        $taskLine[5]
    );
}

//print '<h2>$notificationData</h2>';
//dump($notificationData);

//foreach ($notificationData as $userData) {
//    print '<br>';
//    print '<h3>new email</h3>';
//    print '<div>' . $userData['name'] . '</div>';
//    print '<div>' . $userData['email'] . '</div>';
//
//    foreach ($userData['tasks'] as $task) {
//        dump($task);
//        print '<li>' . $task[0] . '</li>';
//        print '<li>' . $task[1] . '</li>';
//        print '<br>';
//    }
//    print '<hr>';
//}

if (count($notificationData)) {
    // Create the Transport
    $transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
        ->setUsername('keks@phpdemo.ru')
        ->setPassword('htmlacademy')
    ;
    // Create the Mailer using your created Transport
    $mailer = new Swift_Mailer($transport);

    foreach ($notificationData as $userData) {
        try {
            // Create a message
            $message = new Swift_Message();
            $message->setSubject('Уведомление от сервиса «Дела в порядке');
            $message->setFrom(['keks@phpdemo.ru']);
            $message->addTo($userData['email']);
            $messageText = '<b>Уважаемый, ' . $userData['name'] . ' следующие задачи:</b><br>';

            foreach ($userData['tasks'] as $task) {
                $messageText .= ('- У вас запланирована задача' . $task[0] . ' на  ' . $task[1] . '<br>');
            }

            $message->setBody($messageText, 'text/html');
            $result = $mailer->send($message);

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

?>
