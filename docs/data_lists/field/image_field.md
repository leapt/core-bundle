# Data lists: Image field

## Example

!!! example ""

    ```php
    $builder
        ->addField('picture', ImageFieldType::class)
        ->getDatalist();
    ```

The value is resolved through the [Symfony `asset()` function](https://symfony.com/doc/current/reference/twig_reference.html#asset),
so it should hold a path relative to the configured asset package. When the value is `null`, a placeholder image is
displayed instead.

## Options

!!! info ""

    This field type doesn't add any option on top of the [common field options](common_options.md).

## Block name

The block rendered for this field type is `image_field`. Override it in your own Datalist theme if you need to
customize the markup.

[Go back to Data lists documentation](../../data_lists.md)
