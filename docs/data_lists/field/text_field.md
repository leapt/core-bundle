# Data lists: Text field

## Example

!!! example ""

    ```php
    $builder
        ->addField('description', TextFieldType::class, [
            'truncate' => 100,
        ])
        ->getDatalist();
    ```

## Options

!!! info ""

    | Option | Default | Description |
    | --- | --- | --- |
    | `truncate` | not set | Truncates the value to the given number of characters, using the [`safe_truncate` Twig filter](../../twig_extensions.md#safe_truncate-filter). |

    This field type also supports the [common field options](common_options.md), including `escape` which is honored by this field type.

## Block name

The block rendered for this field type is `text_field`. Override it in your own Datalist theme if you need to
customize the markup.

[Go back to Data lists documentation](../../data_lists.md)
