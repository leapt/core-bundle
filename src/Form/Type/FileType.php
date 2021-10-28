<?php

namespace Leapt\CoreBundle\Form\Type;

use Leapt\CoreBundle\File\CondemnedFile;
use Leapt\CoreBundle\Form\DataTransformer\FileDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType as BaseFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class FileType.
 */
class FileType extends AbstractType
{
    private string $uploadDir;

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'leapt_core_file';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired([
                'file_path',
            ])
            ->setDefaults([
                'compound'        => true,
                'error_bubbling'  => false,
                'data_class'      => null,
                'delete_label'    => null,
                'download_label'  => null,
                'allow_delete'    => true,
                'file_label'      => null,
                'file_label_attr' => [],
            ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $filePath = $options['file_path'];
        $uploadDir = $this->uploadDir;

        $builder
            ->add('file', BaseFileType::class, [
                'label'          => $options['file_label'],
                'error_bubbling' => true,
                'label_attr'     => $options['file_label_attr'],
            ])
            ->add('delete', CheckboxType::class, ['error_bubbling' => true])
            ->addViewTransformer(new FileDataTransformer())
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($filePath, $uploadDir) {
                // We need to store the path to the file to delete in the Condemned file instance
                $data = $event->getData();
                if ($data['file'] instanceof CondemnedFile) {
                    $parentForm = $event->getForm()->getParent();
                    $accessor = PropertyAccess::createPropertyAccessor();
                    $imagePath = $accessor->getValue($parentForm->getData(), $filePath);
                    $data['file']->setPath($uploadDir . '/' . $imagePath);
                }
            });
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (\array_key_exists('file_path', $options)) {
            $parentData = $form->getParent()->getData();
            try {
                $fileUrl = null;
                if (null !== $parentData) {
                    if (\is_callable($options['file_path'])) {
                        $fileUrl = \call_user_func($options['file_path'], $parentData);
                    } else {
                        $accessor = PropertyAccess::createPropertyAccessor();
                        $fileUrl = $accessor->getValue($parentData, $options['file_path']);
                    }
                }
            } catch (\Exception $e) {
                $fileUrl = null;
            }
            // set a "file_url" variable that will be available when rendering this field
            $view->vars['file_url'] = $fileUrl;
        }
        $view->vars['download_label'] = $options['download_label'];
        $view->vars['delete_label'] = $options['delete_label'];
        $view->vars['allow_delete'] = $options['allow_delete'];
    }

    public function setUploadDir(string $uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }
}
