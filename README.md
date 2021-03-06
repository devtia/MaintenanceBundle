# MaintenanceBundle

This bundle allows you to display a custom template when your site is under maintenance with a simple change in the parameters
and a rapid deployment. This allows you to correct any errors in your application and inform your users almost immediately.

You can also define only some routes (with PHP regex) to display the message.

## Installation

Download the Bundle.

```bash 
composer require "devtia/maintenance-bundle"
```

Enable the Bundle

```php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new  Devtia\MaintenanceBundle\MaintenanceBundle(),
        );

        // ...
    }

    // ...
}
```

## Usage

You need put something like this in your config.yml

```yml
maintenance:
  #default false. Set to true for enable the bundle and deploy your code
  enable_maintenance: false 
  #not required. You can set multiple routes and a custom template for each one. The routes must be compatible with PHP regex but without initial and end slash ('/')
  routes_prefixes:
    - ['\/admin\/', '%kernel.project_dir%/src/Resources/views/Maintenance/custom_maintenance.html.twig'] #if you left second parameter empty, the bundle use the default template
            
```

## Template functionallity

The bundle provides a default template like this:

![screenshot](https://raw.githubusercontent.com/devtia/MaintenanceBundle/master/src/Resources/doc/default-template.jpg)

You can create a custom template creating a file at this path:

```
ROOT_PATH_TO_YOUR_PROJECT . '/app/Resources/Devtia/MaintenanceBundle/views/maintenance.html.twig
```

If you want more control of your template or need more than one you can define multiple templates in the configuration.

Each template need to be related to a Regex for a group of routes. In the configuration example, the bundle shows
the custom_maintenance.html.twig template in all routes that match with 'admin' pattern.

## Regex

You can learn about PHP regex in this [link](https://www.php.net/manual/es/reference.pcre.pattern.syntax.php) and try them [here](https://regex101.com/).

The MaintenanceBundle simplifies the configuration add to ALL your routes_prefixes the initial and end slash ('/') and the wildcard .* at the end. With the configuration example shows above, the bundle create this regex:

```
/\/admin\/.*/
```

This example match with routes like '/admin/', '/admin/login', '/admin/users/create',...

## Contact

You can contact with me on [jaime@devtia.com](jaime@devtia.com).
