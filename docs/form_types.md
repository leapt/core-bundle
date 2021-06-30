# Form Types

## File type

!!! example "Usage"

    === "Form"
        ```php
        use Leapt\CoreBundle\Form\Type\FileType;

        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('file', FileType::class, [
                    'label'        => 'press_radio_spot.field.file',
                    'file_path'    => 'path', // Required, see Options
                    'allow_delete' => true,
                    'file_label'   => 'file_type.label',
                ])
            ;
        }
        ```

    === "Model / Entity"
        ```php
        use Leapt\CoreBundle\Doctrine\Mapping as LeaptCore;
        
        class News
        {
            #[ORM\Column(type: 'string')]
            private ?string $image = null;
    
            #[LeaptCore\File(path: 'uploads/news', mappedBy: 'image')]
            private ?UploadedFile $file = null;
        }
        ```

??? info "Options"

    | Name | Description | Default value |
    | ---- | ----------- | ------------- |
    | file_path (required) | Property of the object that stores the file path. Used to display a download link. | |
    | delete_label | Text to display next to the delete checkbox. | Delete |
    | download_label | Text to display in the download link. | Download |
    | allow_delete | Display a checkbox that allows to remove the current file. | true |

See also [File uploads](file_uploads.md).

## Image type

To be updated soon.
See also [File uploads](file_uploads.md).

## Recaptcha type

This form type is based on the [EWZRecaptchaBundle](https://github.com/excelwebzone/EWZRecaptchaBundle/), and allows you 
to generate a Recaptcha V2 in your form, and validate it.

!!! example "Usage"
    ```php
    use Leapt\CoreBundle\Form\Type\RecaptchaType;
    use Leapt\CoreBundle\Validator\Constraints as LeaptCore;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;

    final class ContactType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('recaptcha', RecaptchaType::class, [
                'label' => false,
                'constraints' => new LeaptCore\Recaptcha(), // You can also set the constraint in your model
            ]);
        }
    }
    ```

!!! info "Layout"
    Use/extend the `@LeaptCore/Form/form_core_layout.html.twig`, or add it to your Twig's `form_themes` configuration, 
    so the field is rendered properly.

!!! info "Configuration"
    ```yaml
    # config/packages/leapt_core.yaml
    leapt_core:
        recaptcha:
            public_key:  'your_public_key'
            private_key: 'your_private_key'
            enabled: true # true by default, but you can set it to false for your tests
    ```

## RecaptchaV3 type

This form type is based on the [EWZRecaptchaBundle](https://github.com/excelwebzone/EWZRecaptchaBundle/), and allows you 
to generate a Recaptcha V3 in your form, and validate it.

It requires you to have the `google/recaptcha` package installed in your application. If it is not installed, a clear
error message should be displayed in debug mode when validating.

!!! example "Usage"
    ```php
    use Leapt\CoreBundle\Form\Type\RecaptchaV3Type;
    use Leapt\CoreBundle\Validator\Constraints as LeaptCore;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;

    final class ContactType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('recaptcha', RecaptchaV3Type::class, [
                'label' => false,
                'constraints' => new LeaptCore\RecaptchaV3(), // You can also set the constraint in your model
            ]);
        }
    }
    ```

!!! info "Layout"
    Use/extend the `@LeaptCore/Form/form_core_layout.html.twig`, or add it to your Twig's `form_themes` configuration, 
    so the field is rendered properly.

!!! info "Configuration"
    ```yaml
    # config/packages/leapt_core.yaml
    leapt_core:
        recaptcha:
            public_key:  'your_public_key'
            private_key: 'your_private_key'
            enabled: true # true by default, but you can set it to false for your tests
            hide_badge: true # false by default
    ```

## Sound type

To be updated soon.

## Video type

To be updated soon.
