<section class="content__side">
    <?php if (isset($_SESSION['userid'])): ?>
        <h2 class="content__side-heading">Проекты</h2>

        <nav class="main-navigation">
            <ul class="main-navigation__list">
                <?php
                foreach ($tasks_categories as $category): ?>
                    <li class="main-navigation__list-item <?php if ($current_category_id === $category['cat_id']): ?>main-navigation__list-item--active<?php endif; ?>">
                        <a class="main-navigation__list-item-link" href="<?=htmlspecialchars('/index.php?category=' . $category['cat_id']); ?>">
                            <?=htmlspecialchars($category['cat_name']); ?>
                        </a>
                        <span class="main-navigation__list-item-count">
                                        <?php
                                        print(get_tasks_count($tasks_list, $category['cat_id']));
                                        ?>
                                    </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <a class="button button--transparent button--plus content__side-button"
           href="/add-project.php" target="project_add">Добавить проект</a>
    <?php else: ?>
        <p class="content__side-info">Если у вас уже есть аккаунт, авторизуйтесь на сайте</p>
        <a class="button button--transparent content__side-button" href="/auth.php">Войти</a>
    <?php endif; ?>
</section>
