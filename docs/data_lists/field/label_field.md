# Data lists: Label field

!!! example "Example"

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

!!! info "Options"

    | Option | Default | Description |
    | --- | --- | --- |
    | `mappings` | required | Array that maps the value of the field with the displayed value (must be a string). Each entry may define an `attr` key, e.g. to set the HTML `class` attribute. |

    This field type also supports the [common field options](common_options.md).

## Block name

The block rendered for this field type is `label_field`. Override it in your own Datalist theme if you need to
customize the markup.

[Go back to Data lists documentation](../../data_lists.md)
