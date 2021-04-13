---
layout: default
permalink: /form_types.html
---

# Form Types

To be updated soon.

The bundle provides 5 Form Types:

- [File Type](#file-type)
- [Image Type](#image-type)
- [Recaptcha Type](#recaptcha-type)
- [Sound Type](#sound-type)
- [Video Type](#video-type)

## <a name="file-type"></a> File Type

## <a name="image-type"></a> Image Type

## Recaptcha Type

This Form Type is based on the [EWZRecaptchaBundle](https://github.com/excelwebzone/EWZRecaptchaBundle/), and allow you 
to generate a Recaptcha in your form, and validate it.

### Usage

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
            'constraints' => new LeaptCore\Recaptcha(),
        ]);
    }
}
```

### Layout

Use/extend the `@LeaptCore/Form/form_core_layout.html.twig`, or add it to your Twig's `form_themes` configuration 
so the field is rendered properly.

### Configuration

```yaml
# config/packages/leapt_core.yaml
leapt_core:
    recaptcha:
        public_key:  'your_public_key'
        private_key: 'your_private_key'
        enabled: true # true by default, but you can set it to false for your tests
```

## <a name="sound-type"></a> Sound Type

## <a name="video-type"></a> Video Type

----------

&larr; [Installation](/install.html)

[File Uploads](/file_uploads.html) &rarr;
