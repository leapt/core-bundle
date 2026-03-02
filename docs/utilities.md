# Utilities

## StringUtil

The StringUtil has 4 public & static methods, their names speak for themselves:

- `camelize`
- `underscore`
- `unaccent`
- `slugify`

!!! example "Usage"

    ```php
    use Leapt\CoreBundle\Util\StringUtil;
    
    $name = 'Café means coffee';
    $withoutAccents = StringUtil::unaccent($name); // Cafe means coffee
    $slug = StringUtil::slugify($name); // cafe-means-coffee
    ```
