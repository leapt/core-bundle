# Honeypot

You can automatically add a honeypot form field to protect your forms from bots.

It can be configured either globally (see [Configuration Reference](configuration_reference.md)) or set on each
form type individually.

!!! info

    The Honeypot form extension was introduced in leapt/core-bundle 4.7.


## Enable it globally

There are three options available to set in your configuration:

```yaml
leapt_core:
    honeypot:
        enabled_globally: false
        field_name: repeat_email
        css_class: d-none
```

| Name             | Type   | Default value | Description                                                          |
|------------------|--------|---------------|----------------------------------------------------------------------|
| enabled_globally | bool   | false         | Enable the honeypot globally for all forms.                          |
| field_name       | string | repeat_email  | The field name that will be used on render.                          |
| css_class        | string | d-none        | The CSS class that will be used to hide the input for regular users. |


## Usage on specific form

The options you can define on your form type are likely the same that you can define in your global configuration.

Default values are the ones taken from your configuration.

| Name                | Type   | Description                                                          |
|---------------------|--------|----------------------------------------------------------------------|
| honeypot_enabled    | bool   | Enable the honeypot globally for all forms.                          |
| honeypot_field_name | string | The field name that will be used on render.                          |
| honeypot_css_class  | string | The CSS class that will be used to hide the input for regular users. |

!!! example "Usage"

    ```php
    final class YourFormType extends AbstractType
    {
        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'honeypot_enabled'    => true,
                'honeypot_field_name' => 'please_repeat_email',
                'honeypot_css_class'  => 'hide',
            ]);
        }
    }
    ```
