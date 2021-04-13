# Validator Constraints

The bundle currently provides 3 validator constraints:

- [PasswordStrength](#passwordstrength)
- [Recaptcha](#recaptcha)
- [Slug](#slug)

They are all located under the `Leapt\CoreBundle\Validator\Constraints` namespace.

All validators are available as PHP 8 attributes since version 3.1.1.

## PasswordStrength

The PasswordStrengthChecker is based on a snipped provided in Symfony 1.

### Options

| Name | Description | Default value |
| ---- | ----------- | ------------- |
| min | Minimum length for the password. | null |
| max | Maximum length for the password. | null |
| score | Required strength to pass validation, between 0 and 100. | 50 |
| minMessage | Message shown if the password is too short. | This password is too short. It should have {{ limit }} characters or more. |
| maxMessage | Message shown if the password is too long. | This password is too long. It should have {{ limit }} characters or less. |
| scoreMessage | Message shown if the password is not strong enough. | This password is not strong enough. |

### Usage

=== "Attributes"
    ```php
    use Leapt\CoreBundle\Validator\Constraints as LeaptAssert;

    #[LeaptAssert\PasswordStrength(min: 6, max: 72, score: 80')]
    public string $plainPassword;
    ```

=== "Annotations"
    ```php
    use Leapt\CoreBundle\Validator\Constraints as LeaptAssert;

    /**
     * @LeaptAssert\PasswordStrength(min=6, max=72, score=80)
     */
    public string $plainPassword;
    ```

## Recaptcha

This validator should be used with the [Recaptcha Type](form_types.md#recaptcha-type), and is based on the
[EWZRecaptchaBundle](https://github.com/excelwebzone/EWZRecaptchaBundle/).

### Options

| Name | Description | Default value |
| ---- | ----------- | ------------- |
| message | Message shown if the captcha is not valid. | This value is not a valid captcha. |
| invalidHostMessage | Message shown if the host is not valid. | The captcha was not resolved on the right domain. |

### Usage

=== "Attributes"
    ```php
    use Leapt\CoreBundle\Validator\Constraints as LeaptAssert;

    #[LeaptAssert\Recaptcha(message: 'Invalid captcha.')]
    public $recaptcha;
    ```

=== "Annotations"
    ```php
    use Leapt\CoreBundle\Validator\Constraints as LeaptAssert;

    /**
     * @LeaptAssert\Recaptcha(message="Invalid captcha.")
     */
    public $recaptcha;
    ```

## Slug

Helps to validate that the provided value matches a valid slug format.

### Options

| Name | Description | Default value |
| ---- | ----------- | ------------- |
| message | Message shown if the slug is not valid. | A slug can only contain lowercase letters, numbers and hyphens. |
| pattern | Pattern used to check the slug format. | `/^([a-z0-9-]+)$/` |

### Usage

=== "Attributes"
    ```php
    use Leapt\CoreBundle\Validator\Constraints as LeaptAssert;

    #[LeaptAssert\Slug]
    public string $slug;
    ```

=== "Annotations"
    ```php
    use Leapt\CoreBundle\Validator\Constraints as LeaptAssert;

    /**
     * @LeaptAssert\Slug()
     */
    public string $slug;
    ```
