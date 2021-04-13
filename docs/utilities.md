# Utilities

## PasswordStrengthChecker

This class has only one method: `getStrength`, and returns the strength of a given password (between 0 and 100, 
100 being the best value).

If the second argument - `$username` - is provided, the method will remove the username from the password 
if it contains it.

See also the [PasswordStrength constraint](validators.md#passwordstrength).

!!! example "Usage"

    ```php
    use Leapt\CoreBundle\Util\PasswordStrengthChecker;
    
    $passwordStrengthChecker = new PasswordStrengthChecker();
    $score = $passwordStrengthChecker->getStrength($password, $username);
    ```

## StringUtil

The StringUtil has 4 public & static methods, their names speak for themselves:

- `camelize`
- `underscore`
- `unaccent`
- `slugify`

!!! example "Usage"

    ```php
    use Leapt\CoreBundle\Util\StringUtil;
    
    $name = 'Café means coffee';
    $withoutAccents = StringUtil::unaccent($name); // Cafe means coffee
    $slug = StringUtil::slugify($name); // cafe-means-coffee
    ```
