[![Build Status](https://secure.travis-ci.org/snowcap/SnowcapCoreBundle.png?branch=master)](http://travis-ci.org/snowcap/SnowcapCoreBundle)

Snowcap Core Bundle
==================================

The Snowcap Core Bundle is a bundle used at Snowcap to help us with some repetitive tasks, including (but not limited to):

* Dealing with file and image uploads
* RSS feed generation
* SEO-related tasks (sitemaps, etc)

## Prerequisites

This version of the bundle requires Symfony 2.2+. If you are using Symfony
2.1.x, please use the 2.1.x branch of the bundle.

## Installation

### Download SnowcapCoreBundle using composer

Add SnowcapCoreBundle in your composer.json:

```js
{
    "require": {
        "snowcap/core-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update snowcap/core-bundle
```

Composer will install the bundle to your project's `vendor/snowcap` directory.

### Enable the Bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Snowcap\CoreBundle\SnowcapCoreBundle(),
    );
}
```

### Running the tests

Before running the tests, you will need to install the bundle dependencies. Do it using composer :

``` bash
$ php composer.phar --dev install
```

Then you can simply launch phpunit

``` bash
$ phpunit
```

## Form types

SnowcapCoreBundle provides a few useful form types.

### File Field Type (snowcap_core_file)

The _snowcap_core_file_ field type is a simple file upload widget. It extends Symfony's default _file_ type,
and bring two extra features:

* It allows users to ask for the deletion of the current file
* The widget includes a "download" button that allows the user to download the file

#### Usage

```php
<?php
// src/Acme/SiteBundle/Form/CandidateType

public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder->add('cv', 'snowcap_core_file', array(
        'label' => 'Curriculum Vitae',
        'file_path' => 'cvPath',
    ));
}
```

When Symfony displays the form widget, it will also render a "donwload" button, which is basically a link pointing
to the file, as specified by the _file_path_ option.

#### Options

##### file_path

**type:** string or callable **required**

Either a public path that can be processed by Symfony's [PropertyAccess component](http://symfony.com/doc/current/components/property_access/index.html)
or a callable that takes the field data as sole argument and returns a path. This path will be used to build
the download button url.

##### allow_delete

**type:** boolean **default:** true

When true, will display a checkbox allowing users to ask for the deletion of the current file. When checked, on
form submission, the field data will be replaced by an instance of Snowcap\CoreBundle\File\CondemnedFile. It is up
to you to process that Condemned file instance (unless you use the SnowcapCoreBundle FileSubscriber).

##### delete_label
**type:** string **default:** null

The label that will be displayed next to the deletion checkbox.

##### download_label
**type:** string **default:** null

The label that will be displayed on the download button.

### Image Field Type

The _snowcap_core_image_ field type extends the _snowcap_core_file_ field type. It behaves the same way, except that it
is rendered differently: instead of displaying a "download" button, it will actually display the uploaded
image.

**Note:** If you are using [SnowcapImBundle](https://github.com/snowcap/SnowcapImBundle), in addition to the options
provided by _snowcap_core_file_, you can specify a _im_format_ option. It will be used to dynamically create a
thumbnail of the picture. Please refer to the SnowcapImBundle documentation for more information.
