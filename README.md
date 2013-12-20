ChessChat
=========

A simple online chess system. It let's you play chess online!

**STILL IN DEVELOPMENT**

Installation steps
------------------
* Copy files (git clone)

* If you don't have precompiled css files, generate the stylesheets using lessc:
Compile all less files in style/less/
that don't have an underscore at the beginning of their name.
Save their output as *.css files of same name in the style/ directory.

* Copy all configuration files from config/default/ to config/ and adjust your settings.

* If you want SEO-URLs and have mod_rewrite enabled in your server's configuration,
rename default.htaccess to .htaccess and adjust the root path in the second line.

