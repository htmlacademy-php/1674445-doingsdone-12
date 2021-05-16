<?php
//    var_dump($errors['name']);
?>
<div class="content">
    <?php print($aside_content); ?>

    <main class="content__main">
        <h2 class="content__main-heading">Добавление задачи</h2>

        <form class="form" action="add.php" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="form__row">
                <label class="form__label" for="name">Название <sup>*</sup></label>

                <input class="form__input <?php if($errors['name']) {
                    print('form__input--error');
                } ?>" type="text" name="name" id="name" value="<?=htmlspecialchars(get_post_val('name')); ?>" placeholder="Введите название">
                <?php if (isset($errors['name'])): ?>
                    <p class="form__message"><?php print($errors['name']); ?></p>
                <?php endif ?>
            </div>

            <div class="form__row">
                <label class="form__label" for="project">Проект <sup>*</sup></label>

                <select class="form__input form__input--select <?php if($errors['project']) {
                    print('form__input--error');
                } ?>" name="project" id="project">
                    <option value="">Выберите проект</option>
                    <?php foreach ($tasks_categories as $category): ?>
                        <option value="<?php print($category['cat_id']);?>"
                            <?php if($category['cat_id'] == get_post_val('project')) { print('selected'); } ?>
                        >
                            <?=htmlspecialchars($category['cat_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['project'])): ?>
                    <p class="form__message"><?php print($errors['project']); ?></p>
                <?php endif ?>
            </div>

            <div class="form__row">
                <label class="form__label" for="date">Дата выполнения</label>

                <input class="form__input form__input--date <?php if($errors['date']) {
                    print('form__input--error');
                } ?>" type="text" name="date" id="date" value="<?php print(get_post_val('date')); ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
                <?php if (isset($errors['date'])): ?>
                    <p class="form__message"><?php print($errors['date']); ?></p>
                <?php endif ?>
            </div>

            <div class="form__row">
                <label class="form__label" for="file">Файл</label>

                <div class="form__input-file <?php if($errors['file']) {
                    print('form__input--error');
                } ?>">
                    <input class="visually-hidden" type="file" name="file" id="file" value="<?php print(get_files_value('file')['file_name']); ?>">

                    <label class="button button--transparent" for="file">
                        <span>Выберите файл</span>
                    </label>
                    <p class="form__message"><?php print($errors['file'] ?? ''); ?></p>
                </div>
            </div>

            <div class="form__row form__row--controls">
                <input class="button" type="submit" name="" value="Добавить">
            </div>
        </form>
    </main>
</div>
