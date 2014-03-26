Authentication and authorization
================================

Wordpress features a complete user management system. It also features a system to manage authorization via 
[Roles and capabilities](https://codex.wordpress.org/Roles_and_Capabilities).

Mouf on the other hand features an authentication system named [UserService](http://mouf-php.com/packages/mouf/security.userservice/README.md)
and a authorization system named [RightsService](http://mouf-php.com/packages/mouf/security.rightsservice/README.md).
The rights service in Mouf has the notion of "right" that maps the notion of "capability" in Wordpress. The notion
of "role" in Mouf is voluntarily absent (in order to allow developers to add whatever they want).

When you install Moufpress, the install process will create 2 instances related to authentication and authorization:

- `userService` : an instance of the `MoufpressUserService` class that is compatible with the `UserServiceInterface`
- `rightsService` : an instance of the `MoufpressRightService` class that is compatible with the `RightServiceInterface`

Many packages in Mouf rely on those 2 instances so installing those will allow those packages to integrate directly with 
Wordpress rights.

<div class="alert alert-info">Note: if you want to create additional rights in Mouf, you have to declare additional 
capabilities (since Mouf's right = Wordpress capability). These capabilities can be created using the 
<a href="https://codex.wordpress.org/Function_Reference/add_cap"><code>add_cap</code></a> Wordpress function. You can also
manage roles and capabilities using many well designed plugins like <a href="http://www.im-web-gefunden.de/wordpress-plugins/role-manager/">role-manager</a>.
</div>

A few exemples of what you can do with these objects:

```php
// Connects the user
Mouf::getUserService()->login('login', 'password');

// Returns whether a user is connected or not
$isLogged = Mouf::getUserService()->isLogged();

// Returns the login of the current logged user
$login = Mouf::getUserService()->getUserLogin();

// Returns whether the logged user has rights to 'read' articles (mapping the 'read' capability in Wordpress)
$isAllowed = Mouf::getRightsService()->isAllowed('read');
```