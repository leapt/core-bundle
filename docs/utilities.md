# Utilities

## StringUtil

The StringUtil has 6 public & static methods, their names speak for themselves:

- `camelize`
- `underscore`
- `unaccent`
- `slugify`
- `ucfirst`
- `lcfirst`

!!! example "Usage"

    ```php
    use Leapt\CoreBundle\Util\StringUtil;
    
    $name = 'Café means coffee';
    $withoutAccents = StringUtil::unaccent($name); // Cafe means coffee
    $slug = StringUtil::slugify($name); // cafe-means-coffee
    ```

!!! info "New in 6.2"

    `ucfirst` and `lcfirst` are available since version 6.2.

`ucfirst` and `lcfirst` are multibyte safe equivalents of PHP's native `ucfirst()` and `lcfirst()` functions:

!!! example "Usage"

    ```php
    use Leapt\CoreBundle\Util\StringUtil;

    StringUtil::ucfirst('école'); // École
    StringUtil::lcfirst('École'); // école
    ```
