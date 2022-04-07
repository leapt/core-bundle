# Introduction

The Leapt Core bundle is a bundle used to help with some repetitive tasks, including (but not limited to):

- [Data lists with search](data_lists.md)
- [File and image uploads](file_uploads.md)
- [Form types](form_types.md)
- [Navigation helper](navigation_helper.md)
- [Paginator](paginator.md)
- [RSS feed generation](rss_feeds.md)
- [SEO-related tasks (sitemaps, etc)](sitemaps.md)
- [Twig extensions](twig_extensions.md)
- [Utilities](utilities.md)
- [Validators: PasswordStrength, Recaptcha, Slug](validators.md)

If you find a bug or want to add a functionality,
[please create an issue or a pull request on Github](https://github.com/leapt/core-bundle)!

## Examples

| Grid layout example | Tiled layout example |
| --- | --- |
| ![Example of grid datalist layout](images/datalist-grid-demo.webp "Example of grid datalist layout") | ![Example of tiled datalist layout](images/datalist-tiled-demo.webp "Example of tiled datalist layout") |

| Paginator example | Form types example |
| --- | --- |
| ![Example of paginator](images/paginator-demo.webp "Example of paginator") | ![Example of form types](images/form-types-demo.webp "Example of form types") |

## Installation

This bundle requires PHP 8.0+.

As the bundle is compatible with Symfony 5 and Symfony Flex, the only thing you have to do
is requiring the package with composer:

```bash
composer require leapt/core-bundle
```

The bundle will automatically be registered in the `bundles.php` file.

## License

leapt/core-bundle is released under the MIT License. See the bundled LICENSE file for details.

## History

This bundle is a fork of the [Snowcap Core bundle](https://github.com/snowcap/SnowcapCoreBundle).
