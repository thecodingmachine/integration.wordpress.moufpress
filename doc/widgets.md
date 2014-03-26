Bridging Wordpress Widgets with Mouf
====================================

In Wordpress, a piece of HTML that can be displayed anywhere on the page (usually in the sidebar) is called a [**widget**](https://codex.wordpress.org/WordPress_Widgets).
In Mouf, the same concept is represented by [**Html elements**](http://mouf-php.com/packages/mouf/html.htmlelement/README.md). These are objects
implementing the [`HtmlElementInterface`](https://github.com/thecodingmachine/html.html_element/blob/2.0/src/Mouf/Html/HtmlElement/HtmlElementInterface.php).

Moufpress comes with a special Wordpress widget that can be used to display any Html element (i.e. any instance declared in Mouf and
implementing the `HtmlElementInterface`).

![Moufpress widget](doc/widget.png)

Have a look at the screenshot of the widgets panel above. You can directly select a Mouf instance to be displayed from
the Moufpress widget.

**Note:** if you want to control on which page your widget is displayed, you might want to have a look at the excellent 
[Display Widgets Plugin](https://wordpress.org/plugins/display-widgets/).

What can I use this for?
------------------------
Virtually anything!

You can decide to code your own class implementing the [`HtmlElementInterface`](https://github.com/thecodingmachine/html.html_element/blob/2.0/src/Mouf/Html/HtmlElement/HtmlElementInterface.php).
Once your class is written, do not forget to create an instance of your class in Mouf UI.

Or, you can decide to use one of the html element already developed for you. The list is huge:
[Evolugrid](http://mouf-php.com/packages/mouf/html.widgets.evolugrid/README.md) to display an ajax datagrid,
[BCE](http://mouf-php.com/packages/mouf/mvc.bce/readme.md) to display a form with direct mapping in database,
a Twig template using the Twig block, etc... There are many possibilities if you take the time to scan the existing Mouf packages!