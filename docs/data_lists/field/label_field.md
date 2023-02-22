

??? usage "Usage"
```php
        $builder->addField('visibility',LabelFieldType::class, [ //Example with a simple text
                'label' => 'Visibility',
                'mappings'=> [
                     'everyone' => [
                        'label' => 'Everyone',
                        'attr' => ['class' => 'badge badge-succes']
                    ],
                    'author' => [
                        'label' => 'Author and Admin',
                        'attr' => ['class' => 'badge badge-warning']
                    ],
                    'admin' => [
                        'label' => 'Admin',
                        'attr' => ['class' => 'badge badge-danger']
                    ],
                 ],
        ])
            ->addField('status',LabelFieldType::class, [ //Example with an Enum
                'label' => 'Statut',
                'mappings'=> [
                    Status::Draft->value => [
                        'label' => 'Draft publication',
                        'attr' => ['class' => 'badge badge-warning']                    ],
                    Status::Published->value => [
                        'label' => 'Published',
                        'attr' => ['class' => 'badge badge-succes']                     ],
                ],
        ])
        ->getDatalist();
```
As shown before, you can either use use string, BackedEnum or boolean type as entry.

!!! info "Options"

    Mandatory options:
    
    * `mappings`: Array that is used to map the value of the field with the displayed value (must be string). The `attr` key is an option of the mappings where you can define e.g. the HTML class attribute.


[Go back to Data lists documentation](../../data_lists.md)
