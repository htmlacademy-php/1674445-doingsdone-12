<div class="content">
    <?php print($aside_content); ?>

    <main class="content__main">
        <h2 class="content__main-heading">Регистрация аккаунта</h2>

        <form class="form" action="register.php" method="post" autocomplete="off">
            <div class="form__row">
                <label class="form__label" for="email">E-mail <sup>*</sup></label>

                <input class="form__input <?php if($errors['email']) {
                    print('form__input--error');
                } ?>" type="text" name="email" id="email" value="<?=htmlspecialchars(get_post_val('email')); ?>" placeholder="Введите e-mail">
                <p class="form__message"><?php print($errors['email'] ?? ''); ?></p>
            </div>

            <div class="form__row">
                <label class="form__label" for="password">Пароль <sup>*</sup></label>

                <input class="form__input <?php if($errors['password']) {
                    print('form__input--error');
                } ?>" type="password" name="password" id="password" value="<?php print(get_post_val('password')); ?>" placeholder="Введите пароль">
                <p class="form__message"><?php print($errors['password'] ?? ''); ?></p>
            </div>

            <div class="form__row">
                <label class="form__label" for="name">Имя <sup>*</sup></label>

                <input class="form__input <?php if($errors['name']) {
                    print('form__input--error');
                } ?>" type="text" name="name" id="name" value="<?php print(get_post_val('name')); ?>" placeholder="Введите имя">
                <p class="form__message"><?php print($errors['name'] ?? ''); ?></p>
            </div>

            <div class="form__row form__row--controls">
                <?php if (count($errors)): ?>
                    <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
                <?php endif; ?>

                <input class="button" type="submit" name="" value="Зарегистрироваться">
            </div>
        </form>
    </main>
</div>
