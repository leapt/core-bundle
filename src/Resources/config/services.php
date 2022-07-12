<?php

declare(strict_types=1);

use Leapt\CoreBundle\Controller\FeedController;
use Leapt\CoreBundle\Controller\SitemapController;
use Leapt\CoreBundle\Datalist\DatalistFactory;
use Leapt\CoreBundle\EasyAdmin\Field\Configurator\FileConfigurator;
use Leapt\CoreBundle\Feed\FeedManager;
use Leapt\CoreBundle\FileStorage\FileStorageManager;
use Leapt\CoreBundle\FileStorage\FilesystemStorage;
use Leapt\CoreBundle\FileStorage\FlysystemStorage;
use Leapt\CoreBundle\Form\Extension\CollectionTypeExtension;
use Leapt\CoreBundle\Form\Type\FileType;
use Leapt\CoreBundle\Form\Type\RecaptchaType;
use Leapt\CoreBundle\Form\Type\RecaptchaV3Type;
use Leapt\CoreBundle\Listener\FileSubscriber;
use Leapt\CoreBundle\Listener\RequestListener;
use Leapt\CoreBundle\Locale\LocaleResolver;
use Leapt\CoreBundle\Navigation\NavigationRegistry;
use Leapt\CoreBundle\Sitemap\SitemapManager;
use Leapt\CoreBundle\Twig\Extension\DatalistExtension;
use Leapt\CoreBundle\Twig\Extension\DateExtension;
use Leapt\CoreBundle\Twig\Extension\FacebookExtension;
use Leapt\CoreBundle\Twig\Extension\GoogleExtension;
use Leapt\CoreBundle\Twig\Extension\GravatarExtension;
use Leapt\CoreBundle\Twig\Extension\NavigationExtension;
use Leapt\CoreBundle\Twig\Extension\PaginatorExtension;
use Leapt\CoreBundle\Twig\Extension\QrCodeExtension;
use Leapt\CoreBundle\Twig\Extension\SiteExtension;
use Leapt\CoreBundle\Twig\Extension\TextExtension;
use Leapt\CoreBundle\Validator\Constraints\RecaptchaV3Validator;
use Leapt\CoreBundle\Validator\Constraints\RecaptchaValidator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $container->services()
        // Controllers
        ->set(FeedController::class)
            ->arg('$feedManager', service('leapt_core.feed_manager'))
            ->arg('$validator', service('validator'))
            ->arg('$twig', service('twig'))
            ->public()

        ->set(SitemapController::class)
            ->arg('$sitemapManager', service('leapt_core.sitemap_manager'))
            ->arg('$router', service('router'))
            ->arg('$twig', service('twig'))
            ->arg('$httpKernel', service('http_kernel'))
            ->public()

        // Datalist
        ->set(DatalistFactory::class)
            ->arg('$formFactory', service('form.factory'))
            ->arg('$router', service('router'))

        // EasyAdmin field configurators
        ->set(FileConfigurator::class)
            ->arg('$assetExtension', service('twig.extension.assets'))
            ->tag('ea.field_configurator')

        // File entity event subscriber
        ->set('leapt_core.file_subscriber')
            ->class(FileSubscriber::class)
            ->arg('$fileStorageManager', service('leapt_core.file_storage.manager'))
            ->tag('doctrine.event_subscriber')

        // File storages
        ->set('leapt_core.file_storage.filesystem')
            ->class(FilesystemStorage::class)
            ->arg('$uploadDir', param('leapt_core.upload_dir'))

        ->set('leapt_core.file_storage.flysystem')
            ->class(FlysystemStorage::class)
            ->arg('$storages', [])

        ->set('leapt_core.file_storage.manager')
            ->class(FileStorageManager::class)
            ->arg('$filesystemStorage', service('leapt_core.file_storage.filesystem'))
            ->arg('$flysystemStorage', service('leapt_core.file_storage.flysystem'))

        // Form types
        ->set('leapt_core.file_type')
            ->class(FileType::class)
            ->tag('form.type')
            ->call('setUploadDir', [param('leapt_core.upload_dir')])

        ->set(RecaptchaType::class)
            ->arg('$publicKey', param('leapt_core.recaptcha.public_key'))
            ->arg('$enabled', param('leapt_core.recaptcha.enabled'))
            ->arg('$ajax', param('leapt_core.recaptcha.ajax'))
            ->arg('$localeResolver', service(LocaleResolver::class))
            ->arg('$apiHost', param('leapt_core.recaptcha.api_host'))
            ->tag('form.type')

        ->set(RecaptchaV3Type::class)
            ->arg('$publicKey', param('leapt_core.recaptcha.public_key'))
            ->arg('$enabled', param('leapt_core.recaptcha.enabled'))
            ->arg('$hideBadge', param('leapt_core.recaptcha.hide_badge'))
            ->arg('$apiHost', param('leapt_core.recaptcha.api_host'))
            ->tag('form.type')

        // Form type extensions
        ->set('leapt_core.collection_type_extension')
            ->class(CollectionTypeExtension::class)
            ->tag('form.type_extension')

        // Navigation
        ->set('leapt_core.navigation')
            ->class(NavigationRegistry::class)
            ->arg('$requestStack', service('request_stack'))

        // Recaptcha
        ->set(LocaleResolver::class)
            ->arg('$defaultLocale', param('leapt_core.recaptcha.locale_key'))
            ->arg('$useLocaleFromRequest', param('leapt_core.recaptcha.locale_from_request'))
            ->arg('$requestStack', service('request_stack'))

        // RSS feeds
        ->set('leapt_core.feed_manager')
            ->class(FeedManager::class)
            ->public()

        ->set('leapt_core.request_listener')
            ->class(RequestListener::class)
            ->tag('kernel.event_listener', ['event' => 'kernel.request', 'method' => 'onKernelRequest'])

        // Sitemap
        ->set('leapt_core.sitemap_manager')
            ->class(SitemapManager::class)
            ->public()

        // Twig extensions
        ->set(DatalistExtension::class)
            ->arg('$requestStack', service('request_stack'))
            ->tag('twig.extension')

        ->set('leapt_core.twig_date')
            ->class(DateExtension::class)
            ->arg('$translator', service('translator'))
            ->tag('twig.extension')

        ->set('leapt_core.twig_facebook')
            ->class(FacebookExtension::class)
            ->arg('$appId', param('leapt_core.facebook.app_id'))
            ->tag('twig.extension')

        ->set('leapt_core.twig_google')
            ->class(GoogleExtension::class)
            ->arg('$accountId', param('leapt_core.google_analytics.tracking_id'))
            ->arg('$debug', param('leapt_core.google_analytics.debug'))
            ->call('setDomainName', [param('leapt_core.google_analytics.domain_name')])
            ->call('setAllowLinker', [param('leapt_core.google_analytics.allow_linker')])
            ->call('setTagsManagerId', [param('leapt_core.google_tags_manager.id')])
            ->tag('twig.extension')

        ->set('leapt_core.twig_gravatar')
            ->class(GravatarExtension::class)
            ->tag('twig.extension')

        ->set('leapt_core.twig_navigation')
            ->class(NavigationExtension::class)
            ->arg('$registry', service('leapt_core.navigation'))
            ->tag('twig.extension')

        ->set('leapt_core.twig_paginator')
            ->class(PaginatorExtension::class)
            ->arg('$template', param('leapt_core.paginator.template'))
            ->arg('$requestStack', service('request_stack'))
            ->tag('twig.extension')

        ->set(QrCodeExtension::class)
            ->tag('twig.extension')

        ->set('leapt_core.twig_site')
            ->class(SiteExtension::class)
            ->tag('twig.extension')

        ->set('leapt_core.twig_text')
            ->class(TextExtension::class)
            ->tag('twig.extension')

        // Validators
        ->set(RecaptchaValidator::class)
            ->arg('$enabled', param('leapt_core.recaptcha.enabled'))
            ->arg('$privateKey', param('leapt_core.recaptcha.private_key'))
            ->arg('$requestStack', service('request_stack'))
            ->arg('$httpProxy', param('leapt_core.recaptcha.http_proxy'))
            ->arg('$verifyHost', param('leapt_core.recaptcha.verify_host'))
            ->tag('validator.constraint_validator', ['alias' => 'leapt_core.recaptcha'])

        ->set(RecaptchaV3Validator::class)
            ->arg('$enabled', param('leapt_core.recaptcha.enabled'))
            ->arg('$secretKey', param('leapt_core.recaptcha.private_key'))
            ->arg('$scoreThreshold', param('leapt_core.recaptcha.score_threshold'))
            ->arg('$requestStack', service('request_stack'))
            ->arg('$logger', service('logger'))
            ->tag('validator.constraint_validator', ['alias' => 'leapt_core.recaptcha_v3'])
    ;
};
