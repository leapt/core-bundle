# Data lists: DateTime field

## Example

!!! example ""

    ```php
    $builder
        // Formatted with a custom pattern
        ->addField('createdAt', DateTimeFieldType::class, [
            'format' => 'd/m/Y H:i',
        ])
        // Displayed as a relative time, e.g. "3 days ago"
        ->addField('updatedAt', DateTimeFieldType::class, [
            'time_ago' => true,
        ])
        ->getDatalist();
    ```

## Options

!!! info "New in 6.2"

    The `time_ago` option is available since version 6.2.

!!! info ""

    | Option | Default | Description |
    | --- | --- | --- |
    | `format` | `d/m/Y` | The date format used to display the value, following the [Twig `date` filter](https://twig.symfony.com/doc/3.x/filters/date.html) syntax. Ignored when `time_ago` is enabled. |
    | `time_ago` | `false` | When set to `true`, the value is displayed as a relative time (e.g. "3 days ago") using the [`time_ago` Twig filter](../../twig_extensions.md#time_ago-filter) instead of being formatted with `format`. |

    This field type also supports the [common field options](common_options.md).

## Block name

The block rendered for this field type is `datetime_field`. Override it in your own Datalist theme if you need to
customize the markup.

[Go back to Data lists documentation](../../data_lists.md)
