# Navigation Helper

The Navigation helper is a set of tools to figure out which page you are on.

Note that, if the documentation only covers the Twig Extension way (which covers the most common needs), 
you can also inject the `Leapt\CoreBundle\Navigation\NavigationRegistry` service into your service/controller 
to perform the same.

## Active paths

Allows checking if the specified path is the current one.

The Twig Extension provides 4 functions:

- `set_active_paths`: set an array of active paths
- `get_active_paths`: get the array of active paths
- `add_active_path`: add one active path (as a string)
- `is_active_path`: check if the provided path is the current one, returns a boolean. Note that this function will check 
if the specified path is either in the provided active paths, or the current request URI.

!!! example "Usage"

    ```twig
    {% do add_active_path(path('app_news_index')) %}
    
    <a
        href="{{ path('app_news_index') }}"
        class="{{ is_active_path(path('app_news_index')) ? 'active' : '' }}"
    >
        News list
    </a>
    ```

## Breadcrumbs

Easily build breadcrumbs. 3 functions are provided:

- `append_breadcrumb`: append one breadcrumb to the list, given a path and a label
- `prepend_breadcrumb`: same as `append_breadcrumb`, but simply prepends it instead of appending it
- `get_breadcrumbs`: get an array of previously set breadcrumbs

!!! example "Usage"

    ```twig
    {% do append_breadcrumbs(path('app_news_index'), 'News list') %}
    {% do prepend_breadcrumbs(path('app_default_index'), 'ACME Website') %}
    
    {% for breadcrumb in get_breadcrumbs() %}
        {# Index 0 is the path, index 1 is the label #}
        <a href="{{ breadcrumb[0] }}">{{ breadcrumb[1] }}</a>
    {% endfor %}
    ```
