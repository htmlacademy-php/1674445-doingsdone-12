<?php
//    var_dump($errors['name']);
?>
<div class="content">
    <?php print($aside_content); ?>

    <main class="content__main">
        <h2 class="content__main-heading">Добавление проекта</h2>

        <form class="form" action="add-project.php" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="form__row">
                <label class="form__label" for="name">Название <sup>*</sup></label>

                <input class="form__input <?php if($errors['name']) {
                    print('form__input--error');
                } ?>" type="text" name="name" id="name" value="<?=htmlspecialchars(get_post_val('name')); ?>" placeholder="Введите название">
                <?php if (isset($errors['name'])): ?>
                    <p class="form__message"><?php print($errors['name']); ?></p>
                <?php endif ?>
            </div>

            <div class="form__row form__row--controls">
                <input class="button" type="submit" name="" value="Добавить">
            </div>
        </form>
    </main>
</div>
