<?php

namespace Snowcap\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Util\PropertyPath;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Snowcap\CoreBundle\Form\DataTransformer\FileDataTransformer;
use Snowcap\CoreBundle\File\CondemnedFile;

class FileType extends AbstractType
{
    /**
     * @var string
     */
    private $uploadDir;

    /**
     * @return string
     */
    public function getName()
    {
        return 'snowcap_core_file';
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'file';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setOptional(array('file_path'))
            ->setDefaults(array(
                'compound' => true,
                'download_label' => 'form.types.file.download.label',
                'translation_domain' => 'SnowcapCoreBundle'
            ));
    }


    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $filePath = $options['file_path'];
        $uploadDir = $this->uploadDir;

        $builder
            ->add('file', 'file', array('error_bubbling' => true))
            ->add('delete', 'checkbox', array(
                'label' => 'form.types.file.delete.label',
                'error_bubbling' => true,
            ))
            ->addViewTransformer(new FileDataTransformer())
            ->addEventListener(\Symfony\Component\Form\FormEvents::POST_BIND, function($event) use($filePath, $uploadDir) {
                // We need to store the path to the file to delete in the Condemned file instance
                $data = $event->getData();
                if($data['file'] instanceof CondemnedFile) {
                    $parentForm = $event->getForm()->getParent();
                    $propertyPath = new PropertyPath($filePath);
                    $imagePath = $propertyPath->getValue($parentForm->getData());
                    $data['file']->setPath($uploadDir . '/' . $imagePath);
                }
            });
        ;
    }

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface     $form
     * @param array                                     $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('file_path', $options)) {
            $parentData = $form->getParent()->getData();
            try {
                $propertyPath = new PropertyPath($options['file_path']);
                $fileUrl = $propertyPath->getValue($parentData);
            }
            catch(\Exception $e) {
                $fileUrl = null;
            }
            // set an "image_url" variable that will be available when rendering this field
            $view->vars['file_url'] = $fileUrl;
        }
        $view->vars['download_label'] = $options['download_label'];
    }

    /**
     * @param string $rootDir
     */
    public function setUploadDir($uploadDir) {
        $this->uploadDir = $uploadDir;
    }
}