<ul class="nav navbar">
    <?php foreach ($this->getMenuList() as $menuItem) : ?>
        <li id="menu_<?= $menuItem['menuKey'] ?>" class="<?= $menuItem['selected'] ? 'active' : '' ?> 
            <?= isset($menuItem['items']) ? 'dropdown' : '' ?>">
            <a <?php if (isset($menuItem['url'])) : ?>
                    href="<?= $menuItem['url'] ?>"
                <?php else : ?>
                    href="#menu_<?= $menuItem['menuKey'] ?>"
                <?php endif; ?>
                <?php if (isset($menuItem['items'])) : ?>
                    class="dropdown-toggle" data-toggle="dropdown"
                <?php endif; ?> 
                >
                    <?= Label::_($menuItem['labelKey']) ?>
                    <?php if (isset($menuItem['items'])) : ?>
                    <b class="caret"></b>
                <?php endif; ?> 
            </a>
            <?php if (isset($menuItem['items'])) : ?>
                <ul class="dropdown-menu">
                    <?php foreach ($menuItem['items'] as $subItem) : ?>
                        <li>
                            <a href="<?= $subItem['url'] ?>">
                                <?= Label::_($subItem['labelKey']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
