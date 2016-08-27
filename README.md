ChessChat
=========

A simple online chess system. It let's you play chess online!

**UNFINISHED - DEVELOPMENT STALLED**

This was a university project which has been concluded. I may continue working on it at some point but there are no actual plans. Looking at the code now I can see countless structural flaws that will require refactoring.

If you want to fork/contribute anyway drop me a note and I may be able to help out.
Here are a few things that need work:
* User profiles, notifications etc. So far the only thing that really exists is the chess-related stuff.
* Database connection uses mysqli without prepared statements (yeah, I know…). Use PDO instead.
* Use up-to-date conding practices like namespaces with PSR-4 autoloading.
* JS is pretty ugly. It's not much so it probably deserves a complete rewrite.


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

