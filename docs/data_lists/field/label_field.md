# Label field

!!! example "Usage"

    ```php
    $builder
        // Example with strings
        ->addField('visibility', LabelFieldType::class, [
            'mappings' => [
                 'everyone' => [
                    'label' => 'Everyone',
                    'attr' => ['class' => 'badge bg-success']
                ],
                'author' => [
                    'label' => 'Author',
                    'attr' => ['class' => 'badge bg-warning']
                ],
                'admin' => [
                    'label' => 'Admin',
                    'attr' => ['class' => 'badge bg-danger']
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
```

As shown above, you can either use scalars or BackedEnums.

!!! info "Options"

    Mandatory options:
    
    * `mappings`: Array that is used to map the value of the field with the displayed value (must be string). The `attr` key is an option of the mappings where you can define e.g. the HTML class attribute.


[Go back to Data lists documentation](../../data_lists.md)
