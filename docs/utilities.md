---
layout: default
permalink: /utilities.html
---

# Utilities

The bundle provides two utility classes:

- [PasswordStrengthChecker](#password-strength)
- [StringUtil](#string)

## <a name="password-strength"></a> PasswordStrengthChecker

This class has only one method: `getStrength`, and returns the strength of a given password (between 0 and 100, 
100 being the best value).

If the second argument - `$username` is provided, the method will remove the username from the password 
if it contains it.

See also the [PasswordStrength constraint](/validators.html#password-strength).

### Usage

```php
use Leapt\CoreBundle\Util\PasswordStrengthChecker;

$passwordStrengthChecker = new PasswordStrengthChecker();
$score = $passwordStrengthChecker->getStrength($password, $username);

```

## <a name="string"></a> StringUtil

The StringUtil has 4 public & static methods, their names speak for themselves:

- `camelize`
- `underscore`
- `unaccent`
- `slugify`

### Usage

```php
use Leapt\CoreBundle\Util\StringUtil;

$name = 'Café means coffee';

$withoutAccents = StringUtil::slugify($name); // Cafe means coffee
$slug = StringUtil::slugify($name); // cafe-means-coffee
```

----------

&larr; [Navigation Helper](/navigation_helper.html)

[Validator Constraints](/validators.html) &rarr;
