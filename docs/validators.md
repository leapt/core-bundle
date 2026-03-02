# Validator Constraints

## Recaptcha

This validator should be used with the [Recaptcha Type](form_types.md#recaptcha-type), and is based on the
[EWZRecaptchaBundle](https://github.com/excelwebzone/EWZRecaptchaBundle/).

!!! example "Usage"

    ```php
    use Leapt\CoreBundle\Validator\Constraints as LeaptAssert;

    #[LeaptAssert\Recaptcha(message: 'Invalid captcha.')]
    public $recaptcha;
    ```

??? info "Options"

    | Name | Description | Default value |
    | ---- | ----------- | ------------- |
    | message | Message shown if the captcha is not valid. | This value is not a valid captcha. |
    | invalidHostMessage | Message shown if the host is not valid. | The captcha was not resolved on the right domain. |

See also [RecaptchaType](form_types.md#recaptcha-type).

## RecaptchaV3

This validator should be used with the [RecaptchaV3 Type](form_types.md#recaptchav3-type), and is based on the
[EWZRecaptchaBundle](https://github.com/excelwebzone/EWZRecaptchaBundle/).

It requires you to have the `google/recaptcha` package installed in your application. If it is not installed, a clear
error message should be displayed in debug mode when validating.

!!! example "Usage"

    ```php
    use Leapt\CoreBundle\Validator\Constraints as LeaptAssert;

    #[LeaptAssert\RecaptchaV3(message: 'Invalid captcha.')]
    public $recaptcha;

    // Or if you need technical details about why the captcha is invalid:
    #[LeaptAssert\RecaptchaV3(message: LeaptAssert\RecaptchaV3::TECHNICAL_MESSAGE)]
    public $recaptcha;
    ```

??? info "Options"

    | Name | Description | Default value |
    | ---- | ----------- | ------------- |
    | message | Message shown if the captcha is not valid. | The submitted captcha is invalid. |
    | invalidHostMessage | Message shown if the host is not valid. | The captcha was not resolved on the right domain. |

See also [RecaptchaType](form_types.md#recaptchav3-type).

## Slug

Helps to validate that the provided value matches a valid slug format.

!!! example "Usage"

    ```php
    use Leapt\CoreBundle\Validator\Constraints as LeaptAssert;

    #[LeaptAssert\Slug]
    public string $slug;
    ```

??? info "Options"

    | Name | Description | Default value |
    | ---- | ----------- | ------------- |
    | message | Message shown if the slug is not valid. | A slug can only contain lowercase letters, numbers and hyphens. |
    | pattern | Pattern used to check the slug format. | `/^([a-z0-9-]+)$/` |
