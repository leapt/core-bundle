# Data lists: Label field

## Example

!!! example ""

    ```php
    $builder
        // Example with strings
        ->addField('visibility', LabelFieldType::class, [
            'mappings' => [
                 'everyone' => [
                    'label' => 'Everyone',
                    'attr' => ['class' => 'badge bg-success'],
                ],
                'author' => [
                    'label' => 'Author',
                    'attr' => ['class' => 'badge bg-warning'],
                ],
                'admin' => [
                    'label' => 'Admin',
                    'attr' => ['class' => 'badge bg-danger'],
                ],
             ],
        ])
        // Example with BackedEnum
        ->addField('status', LabelFieldType::class, [
            'mappings' => [
                Status::Draft->value => [
                    'label' => 'Draft publication',
                    'attr' => ['class' => 'badge bg-warning'],
                ],
                Status::Published->value => [
                    'label' => 'Published',
                    'attr' => ['class' => 'badge bg-success'],
                ],
            ],
        ])
        ->getDatalist();
    ```

As shown above, you can either use scalars or BackedEnums.

## Options

!!! info "New in 6.2"

    The `choice_translation_domain` option is available since version 6.2.

!!! info ""

    | Option | Default | Description |
    | --- | --- | --- |
    | `mappings` | required | Array that maps the value of the field with the displayed value (must be a string). Each entry may define an `attr` key, e.g. to set the HTML `class` attribute. |
    | `choice_translation_domain` | `null` | Mirrors Symfony's `ChoiceType` option of the same name: `null` translates the label using the Datalist's `translation_domain`, `false` disables translation entirely (the label is displayed as-is), and a string forces a specific translation domain. |

    This field type also supports the [common field options](common_options.md).

## Block name

The block rendered for this field type is `label_field`. Override it in your own Datalist theme if you need to
customize the markup.

[Go back to Data lists documentation](../../data_lists.md)
