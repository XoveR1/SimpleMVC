<div class="modal hide fade in" id="loginModal" style="display: block; width: 360px; margin-left: -180px">
    <form action="<?= BASE_URI . 'auth/sign' ?>" method="post">
        <div class="modal-header">
            <h3><?= Label::_('LOGIN') ?></h3>
        </div>
        <div class="modal-body">
            <?php if (count($dataErrors) > 0) : ?>
                <?php foreach ($dataErrors as $msg) : ?>
                    <div class="alert alert-<?= $msg['type'] ?>">
                        <?= Label::_($msg['message']) ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <div class="control-group <?php if (isset($fieldsErrors['login'])) echo $fieldsErrors['login']['type'] ?>">
                <label for="login"><?= Label::_('LOGIN') ?></label>
                <input id="login" type="text" name="loginData[login]" class="input-append" value="<?= isset($loginData['login']) ? $loginData['login'] : '' ?>">
                <?php if (isset($fieldsErrors['login'])) : ?>
                    <p class="help-inline"><?= Label::_($fieldsErrors['login']['message']) ?></p>
                <?php endif; ?>
            </div>
            <div class="control-group <?php if (isset($fieldsErrors['password'])) echo $fieldsErrors['password']['type'] ?>">
                <label for="password"><?= Label::_('PASSWORD') ?></label>
                <input id="password" type="password" name="loginData[password]" class="input-append">
                <?php if (isset($fieldsErrors['password'])) : ?>
                    <p class="help-inline"><?= Label::_($fieldsErrors['password']['message']) ?></p>
                <?php endif; ?>
            </div>
            <label class="checkbox">
                <input type="checkbox" checked="" name="loginData[remember]" />
                <?= Label::_('REMEMBER_ME') ?>
            </label>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><?= Label::_('LOGIN') ?></button>
        </div>
        <input type="hidden" name="formToken" value="<?= $formToken ?>" />
    </form>
</div>
<div class="modal-backdrop fade in"></div>