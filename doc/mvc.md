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

Managing the title
------------------
As with any Splash templates, you can modify the title of the template using the `setTitle`.
For instance, calling `$wordpressTemplate->setTitle("My page")` will set the title in both the Wordpress template and the &lt;title&gt; tag.

Specific to Moufpress: you can also use the `@Title` annotation in your controller to set the title of the page. For instance:

```php
/**
 * @URL mytest
 * @Title My page
 */
public function index() {
	$this->content->addFile(ROOT_PATH.'src/views/myview.php', $this);
	$this->template->toHtml();
}
```

Troubleshooting: if the title is not displayed correctly in the &lt;title&gt; tag (especially, if you see the "WP Router Placeholder Page" text instead of your title,
it is likely that you are using the "All-in-one SEO pack" plugin. This module rewrites the title in a way that is not compatible with Moufpress. A simple
workaround to disable this feature of the "All-in-one SEO pack" plugin in Moufpress is to edit your `header.php` file in your theme and add a space in the title tag.

For instance, write:

```php
<title ><?php wp_title( '|', true, 'right' ); ?></title>
```

instead of 

```php
<title><?php wp_title( '|', true, 'right' ); ?></title>
```

What next?
----------

Learn more about:

<a href="scripts-and-styles.md" class="btn btn-primary">Web library (JS/CSS) &gt;</a>

<a href="widgets.md" class="btn">Widgets integration &gt;</a>

<a href="authentication_and_right_management.md" class="btn">Authentication and authorization &gt;</a>
