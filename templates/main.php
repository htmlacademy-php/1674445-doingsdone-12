<div class="content">
    <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>

        <nav class="main-navigation">
            <ul class="main-navigation__list">
                <?php
                foreach ($tasksCategories as $category): ?>
                    <li class="main-navigation__list-item">
                        <a class="main-navigation__list-item-link" href="#"><?php print(htmlspecialchars($category)) ?></a>
                        <span class="main-navigation__list-item-count">
                                    <?php
                                    print(getTacksCount($tasksList, $category));
                                    ?>
                                </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <a class="button button--transparent button--plus content__side-button"
           href="pages/form-project.html" target="project_add">Добавить проект</a>
    </section>

    <main class="content__main">
        <h2 class="content__main-heading">Список задач</h2>

        <form class="search-form" action="index.php" method="post" autocomplete="off">
            <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

            <input class="search-form__submit" type="submit" name="" value="Искать">
        </form>

        <div class="tasks-controls">
            <nav class="tasks-switch">
                <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
                <a href="/" class="tasks-switch__item">Повестка дня</a>
                <a href="/" class="tasks-switch__item">Завтра</a>
                <a href="/" class="tasks-switch__item">Просроченные</a>
            </nav>

            <label class="checkbox">
                <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
                <input class="checkbox__input visually-hidden show_completed" type="checkbox"
                       <?php if ($show_complete_tasks === 1): ?>checked<?php endif; ?>
                >
                <span class="checkbox__text">Показывать выполненные</span>
            </label>
        </div>

        <table class="tasks">
            <?php foreach ($tasksList as $task): ?>
                <?php if (!$show_complete_tasks && $task['taskCompleteStatus']) { continue; } ?>

                <tr class="tasks__item task <?php if ($task['taskCompleteStatus']) {
                    print('task--completed');
                } ?>">
                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <?php if ($task['taskCompleteStatus']): ?>
                                <input class="checkbox__input visually-hidden" type="checkbox" checked>
                            <?php else: ?>
                                <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1">
                            <?php endif; ?>
                            <span class="checkbox__text"><?php print(htmlspecialchars($task['taskName'])); ?></span>
                        </label>
                    </td>

                    <td class="task__file">
                        <a class="download-link" href="#">Home.psd</a>
                    </td>

                    <td class="task__date"><?php print($task['taskCompleteDate']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
</div>
