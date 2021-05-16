<div class="content">
    <?php print($aside_content); ?>

    <main class="content__main">
        <h2 class="content__main-heading">Список задач</h2>

        <form class="search-form" action="index.php" method="get" autocomplete="off">
            <input class="search-form__input" type="text" name="q" value="" placeholder="Поиск по задачам">

            <input class="search-form__submit" type="submit" name="" value="Искать">
        </form>

        <div class="tasks-controls">
            <nav class="tasks-switch">
                <a href="/" class="tasks-switch__item <?php if (!$tasks_filter_date) {
                    print('tasks-switch__item--active');} ?>">Все задачи</a>
                <a href="/index.php?date=today" class="tasks-switch__item <?php if ($tasks_filter_date == 'today') {
                    print('tasks-switch__item--active');} ?>">Повестка дня</a>
                <a href="/index.php?date=tomorrow" class="tasks-switch__item <?php if ($tasks_filter_date == 'tomorrow') {
                    print('tasks-switch__item--active');} ?>">Завтра</a>
                <a href="/index.php?date=outdated" class="tasks-switch__item <?php if ($tasks_filter_date == 'outdated') {
                    print('tasks-switch__item--active');} ?>">Просроченные</a>
            </nav>

            <label class="checkbox">
                <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
                <input class="checkbox__input visually-hidden show_completed" name="show_comleted" type="checkbox"
                       <?php if ($show_complete_tasks == 1): ?>checked<?php endif; ?>
                >
                <span class="checkbox__text">Показывать выполненные</span>
            </label>
        </div>

        <table class="tasks">
            <?php foreach ($tasks_list as $task): ?>
                <?php if (!$show_complete_tasks && $task['status']) { continue; } ?>
                <?php if ($current_category_id !== null && ($task['category_id'] != $current_category_id)) { continue; } ?>

                <tr class="tasks__item task <?php if ($task['status']) {
                    print('task--completed');
                } ?>">
                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <?php if ($task['status'] == 1): ?>
                                <input class="checkbox__input visually-hidden" type="checkbox" checked value="<?=htmlspecialchars($task['id']); ?>">
                            <?php else: ?>
                                <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="<?=htmlspecialchars($task['id']); ?>">
                            <?php endif; ?>
                            <span class="checkbox__text">
                                        <?=htmlspecialchars($task['name']); ?>
                                    </span>
                        </label>
                    </td>

                    <td class="task__file">
                        <?php if ($task['file_url']): ?>
                            <?php
                                $file_url_array = explode("/", $task['file_url']);
                                $file_name = $file_url_array[count($file_url_array) - 1];
                            ?>
                            <?php if ($file_name): ?>
                                <a class="download-link" href="<?=htmlspecialchars($task['file_url']); ?>"><?=htmlspecialchars($file_name); ?></a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>

                    <td class="task__date"><?=htmlspecialchars($task['expire_date']); ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if(!count($tasks_list)): ?>
                <p>Ничего не найдено</p>
            <?php endif; ?>
        </table>
    </main>
</div>
