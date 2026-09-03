# Data lists: Heading field

Behaves exactly like the [Text field](text_field.md) (it actually extends `TextFieldType`), but is rendered inside a
`<th>` cell instead of a `<td>`, making it suitable e.g. for a row's title column.

## Example

!!! example ""

    ```php
    $builder
        ->addField('title', HeadingFieldType::class, [
            'truncate' => 50,
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

The block rendered for this field type is `heading_field`. Override it in your own Datalist theme if you need to
customize the markup.

[Go back to Data lists documentation](../../data_lists.md)
