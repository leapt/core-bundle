<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Snowcap\CoreBundle\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\Container;
use Sensio\Bundle\GeneratorBundle\Generator\Generator;

/**
 * Generates a bundle.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class BundleGenerator extends Generator
{
    private $filesystem;
    private $skeletonDir;

    public function __construct(Filesystem $filesystem, $skeletonDir)
    {
        $this->filesystem = $filesystem;
        $this->skeletonDir = $skeletonDir;
    }

    public function generate($namespace, $bundle, $dir)
    {
        $dir .= '/' . strtr($namespace, '\\', '/');
        if (file_exists($dir)) {
            throw new \RuntimeException(sprintf('Unable to generate the bundle as the target directory "%s" is not empty.', realpath($dir)));
        }

        $basename = substr($bundle, 0, -6);
        $parameters = array(
            'namespace'       => $namespace,
            'bundle'          => $bundle,
            'format'          => 'yml',
            'bundle_basename' => $basename,
            'extension_alias' => Container::underscore($basename),
        );

        $this->renderFile($this->skeletonDir, 'Bundle.php', $dir . '/' . $bundle . '.php', $parameters);
        $this->renderFile($this->skeletonDir, 'Extension.php', $dir . '/DependencyInjection/' . $basename . 'Extension.php', $parameters);
        $this->renderFile($this->skeletonDir, 'Configuration.php', $dir . '/DependencyInjection/Configuration.php', $parameters);
        $this->renderFile($this->skeletonDir, 'DefaultController.php', $dir . '/Controller/DefaultController.php', $parameters);

        $this->renderFile($this->skeletonDir, 'services.yml', $dir . '/Resources/config/services.yml', $parameters);

        $this->filesystem->mkdir($dir . '/Resources/translations');
        $this->filesystem->copy($this->skeletonDir . '/messages.en.yml', $dir . '/Resources/translations/messages.en.yml');

        $this->filesystem->mkdir($dir . '/Resources/public/less');
        $this->filesystem->touch($dir . '/Resources/public/less/screen.less');
        $this->filesystem->touch($dir . '/Resources/public/less/ie.less');
        $this->filesystem->mkdir($dir . '/Resources/public/images');
        $this->filesystem->touch($dir . '/Resources/public/images/.gitkeep');
        $this->filesystem->mkdir($dir . '/Resources/public/js');
        $this->filesystem->touch($dir . '/Resources/public/js/screen.js');

        $parameters = array(
            'bundle' => $bundle,
            'public_name' => strtolower($basename),
        );

        $this->renderHtml($this->skeletonDir, 'base.html.twig', $dir . '/Resources/views/base.html.twig', $parameters);
        $this->renderHtml($this->skeletonDir, 'index.html.twig', $dir . '/Resources/views/Default/index.html.twig', $parameters);
    }

    protected function renderHtml($skeletonDir, $template, $target, $parameters)
    {
        if (!is_dir(dirname($target))) {
            mkdir(dirname($target), 0777, true);
        }

        $content = file_get_contents($skeletonDir . '/' . $template);

        $filters = array();
        foreach ($parameters as $key => $parameter) {
            $filters['[%' . $key . '%]'] = $parameter;
        }

        file_put_contents($target, str_replace(array_keys($filters), array_values($filters), $content));
    }

}
