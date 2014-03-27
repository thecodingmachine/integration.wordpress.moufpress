MoufPress: a MVC framework for Wordpress based on Mouf
======================================================

Why should I care?
------------------

Moufpress is a **MVC framework for Wordpress**. Actually, it is a bridge between [Wordpress](http://wordpress.org/) and
the [Splash MVC framework](http://mouf-php.com/packages/mouf/mvc.splash/index.md) 
used by [Mouf-PHP](http://mouf-php.com) (a dependency injection based framework).

MoufPress offers the following features:

- **controllers**, declared through a nice graphical DI container
- use of **annotations** in your controllers (for instance: `@URL` to declare a new route, `@Logged` to restrict access to logged users, etc...)
- support for any kind of **views** supported in Splash MVC (this includes plain PHP files, [Twig templates](http://twig.sensiolabs.org/), etc...)
- a [nice web-based UI to scafold your controllers and views](http://mouf-php.com/packages/mouf/mvc.splash/doc/writing_controllers.md)
- integration of your views inside the Wordpress theme
- (very) easy Ajax support
- creating [**Wordpress widgets**](doc/widgets.md) through dependency injection in Mouf
- integration of Mouf's [**authentication and authorization**](doc/authentication_and_right_management.md) system into Wordpress
- integration of Mouf's [**web library (JS/CSS)**](doc/scripts-and-styles.md) system into Wordpress

Another interesting feature is that your code is **100% compatible** with Splash MVC. This means that:

- You can write a controller in Splash MVC and deploy it later in Wordpress (or the opposite)
- Since there is also a Drupal module for Splash ([Druplash](http://mouf-php.com/packages/mouf/integration.drupal.druplash/README.md)),
  you can actually **write a controller in Wordpress and deploy it in Drupal** (or the other way around).
  Yes, you read it correctly, you can develop an application that will run on both Wordpress and Drupal (!)
  Haha! I see you're interested. Let's get started!

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
	"autoload" : {
		"psr-0" : {
			"MyApp" : "src/"
		}
	},
	"require" : {
		"mouf/mouf" : "~2.0",
		"mouf/integration.wordpress.moufpress" : "~1.0",
	},
	"minimum-stability" : "dev"
}
```
   Do not forget to customize your vendor name (the `MyApp` part of the autoloader section).
5. Create the empty `src/` directory at the root of your project.
6. Run the install process in Mouf: connect to Mouf UI and run the install process for all the packages 
   (including Moufpress install process of course)
7. When you downloaded Moufpress, Composer automatically copied a Moufpress plugin in the `wp-content/plugins` directory of
   Wordpress. You need to install this plugin. Connect to your Wordpress admin,  select the **Plugins > Installed plugins** 
   menu, and click on the "Activate" button for the "Moufpress" plugin. Also, do not forget to activate
   the **WP Router** module first if you haven't already done it.


Getting started
---------------

[In the next section, we will learn **how to create a controller and a view**.](doc/mvc.md)

Or if you already know Splash, you can directly jump to another part of this documentation:

- [widgets integration](doc/widgets.md)
- [authentication and authorization](doc/authentication_and_right_management.md)
- [web library (JS/CSS)](doc/scripts-and-styles.md)

TODO:
- Put the cache back in place
