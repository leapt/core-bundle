<?php

declare(strict_types=1);

use Doctrine\ORM\Events;
use Leapt\CoreBundle\Controller\FeedController;
use Leapt\CoreBundle\Controller\SitemapController;
use Leapt\CoreBundle\Datalist\DatalistFactory;
use Leapt\CoreBundle\Feed\FeedManager;
use Leapt\CoreBundle\FileStorage\FileStorageManager;
use Leapt\CoreBundle\FileStorage\FilesystemStorage;
use Leapt\CoreBundle\FileStorage\FlysystemStorage;
use Leapt\CoreBundle\Form\Extension\CollectionTypeExtension;
use Leapt\CoreBundle\Form\Extension\HoneypotExtension;
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
            ->arg('$feedManager', service(FeedManager::class))
            ->arg('$validator', service('validator'))
            ->arg('$twig', service('twig'))
            ->public()

        ->set(SitemapController::class)
            ->arg('$sitemapManager', service(SitemapManager::class))
            ->arg('$router', service('router'))
            ->arg('$twig', service('twig'))
            ->arg('$httpKernel', service('http_kernel'))
            ->public()

        // Datalist
        ->set(DatalistFactory::class)
            ->arg('$formFactory', service('form.factory'))
            ->arg('$router', service('router'))

        // File entity event subscriber
        ->set(FileSubscriber::class)
            ->arg('$fileStorageManager', service(FileStorageManager::class))
            ->tag('doctrine.event_listener', ['event' => Events::preFlush])
            ->tag('doctrine.event_listener', ['event' => Events::onFlush])
            ->tag('doctrine.event_listener', ['event' => Events::postPersist])
            ->tag('doctrine.event_listener', ['event' => Events::postUpdate])
            ->tag('doctrine.event_listener', ['event' => Events::preRemove])
            ->tag('doctrine.event_listener', ['event' => Events::postRemove])

        // File storages
        ->set(FilesystemStorage::class)
            ->arg('$uploadDir', param('leapt_core.upload_dir'))

        ->set(FlysystemStorage::class)
            ->arg('$storages', [])

        ->set(FileStorageManager::class)
            ->arg('$filesystemStorage', service(FilesystemStorage::class))
            ->arg('$flysystemStorage', service(FlysystemStorage::class))

        // Form types
        ->set(FileType::class)
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
        ->set(CollectionTypeExtension::class)
            ->tag('form.type_extension')

        ->set(HoneypotExtension::class)
            ->arg('$translator', service('translator'))
            ->arg('$enableGlobally', param('leapt_core.honeypot.enable_globally'))
            ->arg('$inputName', param('leapt_core.honeypot.input_name'))
            ->arg('$cssClass', param('leapt_core.honeypot.css_class'))
            ->tag('form.type_extension')

        // Navigation
        ->set(NavigationRegistry::class)
            ->arg('$requestStack', service('request_stack'))

        // Recaptcha
        ->set(LocaleResolver::class)
            ->arg('$defaultLocale', param('leapt_core.recaptcha.locale_key'))
            ->arg('$useLocaleFromRequest', param('leapt_core.recaptcha.locale_from_request'))
            ->arg('$requestStack', service('request_stack'))

        // RSS feeds
        ->set(FeedManager::class)
            ->public()

        ->set(RequestListener::class)
            ->tag('kernel.event_listener', ['event' => 'kernel.request', 'method' => 'onKernelRequest'])

        // Sitemap
        ->set(SitemapManager::class)
            ->public()

        // Twig extensions
        ->set(DatalistExtension::class)
            ->arg('$requestStack', service('request_stack'))
            ->tag('twig.extension')

        ->set(DateExtension::class)
            ->arg('$translator', service('translator'))
            ->tag('twig.extension')

        ->set(FacebookExtension::class)
            ->arg('$appId', param('leapt_core.facebook.app_id'))
            ->tag('twig.extension')

        ->set(GoogleExtension::class)
            ->arg('$accountId', param('leapt_core.google_analytics.tracking_id'))
            ->arg('$debug', param('leapt_core.google_analytics.debug'))
            ->call('setDomainName', [param('leapt_core.google_analytics.domain_name')])
            ->call('setAllowLinker', [param('leapt_core.google_analytics.allow_linker')])
            ->call('setTagsManagerId', [param('leapt_core.google_tags_manager.id')])
            ->tag('twig.extension')

        ->set(GravatarExtension::class)
            ->tag('twig.extension')

        ->set(NavigationExtension::class)
            ->arg('$registry', service(NavigationRegistry::class))
            ->tag('twig.extension')

        ->set(PaginatorExtension::class)
            ->arg('$template', param('leapt_core.paginator.template'))
            ->arg('$requestStack', service('request_stack'))
            ->tag('twig.extension')

        ->set(QrCodeExtension::class)
            ->tag('twig.extension')

        ->set(SiteExtension::class)
            ->tag('twig.extension')

        ->set(TextExtension::class)
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
