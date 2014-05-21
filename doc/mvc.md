Building a controller and a view for Wordpress
==============================================

We will not describe the whole process of creating a controller and a view in the Moufpress documentation.
Indeed, Moufpress is just a compatibility layer on top of the Splash MVC framework. Therefore, you can
simply refer to the [Splash MVC video tutorial to get started](http://mouf-php.com/packages/mouf/mvc.splash/doc/writing_controllers.md).

Integrating with Wordpress theme
--------------------------------

When you run MoufPress' installer, a `wordpressTemplate` instance will be created. This instance represents the current
Wordpress theme.
Therefore, calling `$wordpressTemplate->toHtml()` will trigger the display of the Wordpress theme.
If you do not call this method, the Wordpress theme will not be displayed and anything outputed will be directly 
sent to the browser. This is a fairly easy way to do some Ajax since you won't be polluted by the Wordpress theme at all.

What next?
----------

Learn more about:

<a href="scripts-and-styles.md" class="btn btn-primary">Web library (JS/CSS) &gt;</a>

<a href="widgets.md" class="btn">Widgets integration &gt;</a>

<a href="authentication_and_right_management.md" class="btn">Authentication and authorization &gt;</a>