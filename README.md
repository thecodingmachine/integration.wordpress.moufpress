MoufPress: a MVC framework for Wordpress based on Mouf
======================================================

MoufPress is an adaptation of the [Splash MVC framework](http://mouf-php.com/packages/mouf/mvc.splash/index.md) 
used by [Mouf-PHP](http://mouf-php.com).
Using MoufPress, you can write controllers, views, etc... as you would in Splash, and still, deploy your application
inside a Wordpress site.

Installation
------------

You will first need to install Wordpress and Mouf side by side.

1. Start by installing [Wordpress](http://wordpress.org/) as you would normally do.
2. [Install the Mouf PHP framework](http://mouf-php.com/packages/mouf/mouf/doc/installing_mouf.md) _in the same directory_ as Wordpress
   This means you should have the **composer.json** file of Composer in the same directory as the **wp-config.php** of Wordpress.
3. Modify **composer.json** and add the **moufpress** module.