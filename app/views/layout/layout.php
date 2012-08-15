<!doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>
            <?php if ($this->isTitle()) : ?>
                <?= Label::_($this->getTitleLabelKey()) ?>
                <?= Factory::getConfig()->titleSeparator ?>
            <?php endif; ?>
            <?= Label::_(Factory::getConfig()->siteNameLabelKey) ?>            
        </title>
        <?php foreach (View::getStylesList() as $stylePath) : ?>
            <link rel="stylesheet" href="<?= $stylePath ?>"/>
        <?php endforeach; ?>
    </head>
    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="<?= BASE_URI ?>"><?= Label::_(Factory::getConfig()->siteNameLabelKey) ?></a>
                    <div class="nav-collapse">
                        <?php if(Factory::getCurrentUser()->isAdmin()) : ?>
                            <?php $this->renderPartial('menu', array('layout' => true)) ?>
                            <ul class="nav pull-right">
                                <li class="active">
                                    <a> 
                                        <i class="icon-user icon-white"></i>
                                        <?= Label::_('USER_GREETING', array('name' => ucfirst(Factory::getCurrentUser()->getLogin()))) ?>
                                    </a>
                                </li>
                                <li class="divider-vertical"></li>
                                <li>
                                    <a href="<?= BASE_URI . 'auth/exit' ?>">
                                        <i class="icon-remove icon-white"></i>
                                        <?= Label::_('EXIT') ?>
                                    </a>
                                </li>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <?php if ($this->isMessages()) : ?>
                <?php foreach ($this->getMessages() as $message) : ?>
                    <div class="alert alert-<?= $message['type'] ?>">
                        <?php if ($message['type'] != 'error') : ?>
                            <a class="close" data-dismiss="alert">×</a>
                        <?php endif; ?>
                        <strong>
                            <?php if ($message['type'] == 'error') : ?>
                                <?= Label::_('ERROR') ?>
                            <?php elseif ($message['type'] == 'success') : ?>
                                <?= Label::_('SUCCESS') ?>
                            <?php elseif ($message['type'] == 'info') : ?>
                                <?= Label::_('INFO') ?>
                            <?php endif; ?>
                        </strong>
                        <?= Label::_($message['message']) ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if ($this->isTitle()) : ?>
                <?php if ($this->getShowTitle()) : ?>
                    <div class="row">
                        <div class="span2">
                            <a class="logo" href="<?= BASE_URI ?>"></a>
                        </div>
                        <div class="span10">
                            <h1>
                                <?= Label::_($this->getTitleLabelKey()) ?>
                            </h1>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <?= $content ?>
        </div>
        <script type="text/javascript">
            var BASE_URI = '<?= BASE_URI ?>';                
        </script>
        <?php foreach (View::getScriptsList() as $scriptPath) : ?>
            <script type="text/javascript" src="<?= $scriptPath ?>"></script>
        <?php endforeach; ?>
    </body>
</html>
