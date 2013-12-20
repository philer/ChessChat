        </main><!-- #main -->
        <footer id="footer">
            <nav id="footerMenu">
                <ul>
                    <li><?php
                        echo new Link('site.legalnotice', 'LegalNotice');
                    ?></li><li><?php
                        echo new Link('user.list', 'User/list');
                    ?></li>
                </ul>
            </nav>
            <div style="float:right;">
                <span>logged in as <?php echo Core::getUser() ?></span>
<?php if (DEBUG_MODE) { ?>
                |
                <span>time: <?php echo round(microtime(true) - START_TIME, 4); ?></span>
                |
                <span>queries: <?php echo Core::getDB()->getQueryCount(); ?></span>
<?php } ?>
            </div>
            <div id="copyright"><?php echo $this->lang('site.copyrightby') ?>Phil &amp; Larissa</div>
        </footer>
<?php include("_overlay.tpl.php"); ?>
    </body>
</html>
