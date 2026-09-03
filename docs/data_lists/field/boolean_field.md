# Data lists: Boolean field

## Example

!!! example ""

    ```php
    $builder
        ->addField('isActive', BooleanFieldType::class, [
            'true_label' => 'Active',
            'false_label' => 'Inactive',
        ])
        ->getDatalist();
    ```

## Options

!!! info ""

    | Option | Default | Description |
    | --- | --- | --- |
    | `true_label` | `null` | Label displayed when the value is `true`. Falls back to "Yes" when not set. |
    | `false_label` | `null` | Label displayed when the value is `false`. Falls back to "No" when not set. |

    This field type also supports the [common field options](common_options.md).

## Block name

The block rendered for this field type is `boolean_field`. Override it in your own Datalist theme if you need to
customize the markup.

[Go back to Data lists documentation](../../data_lists.md)
