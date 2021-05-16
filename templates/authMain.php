<div class="content">
    <?php print($aside_content); ?>

    <main class="content__main">
        <h2 class="content__main-heading">Вход на сайт</h2>

        <form class="form" action="auth.php" method="post" autocomplete="off">
            <div class="form__row">
                <label class="form__label" for="email">E-mail <sup>*</sup></label>

                <input class="form__input <?php if($errors['email']) {
                    print('form__input--error');
                } ?>" type="text" name="email" id="email" value="<?php print(get_post_val('email')); ?>" placeholder="Введите e-mail">
                <p class="form__message"><?php print($errors['email'] ?? ''); ?></p>
            </div>

            <div class="form__row">
                <label class="form__label" for="password">Пароль <sup>*</sup></label>
                <input class="form__input <?php if($errors['email']) {
                    print('form__input--error');
                } ?>" type="password" name="password" id="password" value="<?php print(get_post_val('password')); ?>" placeholder="Введите пароль">
                <p class="form__message"><?php print($errors['password'] ?? ''); ?></p>
            </div>

            <div class="form__row form__row--controls">
                <input class="button" type="submit" name="" value="Войти">
            </div>
        </form>

    </main>
</div>
