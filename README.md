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
2. Install the [WP-Router plugin](https://wordpress.org/plugins/wp-router/).
   Note: even if the plugin page stats this plugin works up to version 3.4 of Wordpress,
   we have tested with Wordpress 3.8.1 and it works great. 
3. [Install the Mouf PHP framework](http://mouf-php.com/packages/mouf/mouf/doc/installing_mouf.md) _in the same directory_ as Wordpress
   This means you should have the **composer.json** file of Composer in the same directory as the **wp-config.php** of Wordpress.
4. Modify **composer.json** and add the **moufpress** module. Your **composer.json** should contain at least these lines: 
```
{
	"require" : {
		"mouf/mouf" : "~2.0",
		"mouf/integration.wordpress.moufpress" : "~1.0",
	},
	"minimum-stability" : "dev"
}
```
5. Run the install process in Mouf: connect to Mouf UI and run the install process for all the packages 
   (including Moufpress install process of course)
6. When you downloaded Moufpress, Composer automatically copied a Moufpress plugin in the `wp-content/plugins` directory of
   Wordpress. You need to install this plugin. Connect to your Wordpress admin,  select the **Plugins > Installed plugins** 
   menu, and click on the "Activate" button for the "Moufpress" plugin. Also, do not forget to activate
   the **WP Router** module first. 

Think about:
- Put tutorial for MVC
- Put the cache back in place
- Put links to sections in README.