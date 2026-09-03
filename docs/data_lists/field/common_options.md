# Data lists: Common field options

Every field type shares the following options, on top of the ones documented on its own page.

!!! info "Options"

    | Option | Default | Description |
    | --- | --- | --- |
    | `label` | Field name, capitalized | The label displayed in the column header / next to the value. |
    | `property_path` | Field name | The [PropertyAccess](https://symfony.com/doc/current/components/property_access.html) path used to read the value from the current row. Useful when the field name doesn't match a property directly (e.g. a nested property such as `author.name`). |
    | `default` | `null` | Value used instead when the resolved value is `null`. |
    | `escape` | `true` | Whether the value should be HTML-escaped when rendered. Only honored by field types that render raw text (e.g. `TextFieldType`). |
    | `sortable` | `false` | Whether the column can be sorted by clicking on its header. |
    | `sort_property_path` | `null` | The property path used for sorting. Required when `sortable` is `true`. |
    | `callback` | — | Computes the displayed value from the row, instead of reading it through `property_path`. Takes precedence over `property_path` when set. Must be a `callable`. |
    | `order` | — | Controls the display order of the field within the datalist. |

[Go back to Data lists documentation](../../data_lists.md)
