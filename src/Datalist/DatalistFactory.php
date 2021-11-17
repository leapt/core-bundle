<?php

namespace Leapt\CoreBundle\Datalist;

use Leapt\CoreBundle\Datalist\Action\Type as ActionType;
use Leapt\CoreBundle\Datalist\Field\Type as FieldType;
use Leapt\CoreBundle\Datalist\Filter\Type as FilterType;
use Leapt\CoreBundle\Datalist\Type as DatalistType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class DatalistFactory
{
    /**
     * @var array
     */
    private $types = [];

    /**
     * @var array
     */
    private $fieldTypes = [];

    /**
     * @var array
     */
    private $filterTypes = [];

    /**
     * @var array
     */
    private $actionTypes = [];

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    private $router;

    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->initialize();
    }

    /**
     * @param string $type
     *
     * @return DatalistInterface
     */
    public function create($type = 'datalist', array $options = [])
    {
        return $this->createBuilder($type, $options)->getDatalist();
    }

    /**
     * @param string $name
     * @param string $type
     *
     * @return Datalist
     */
    public function createNamed($name, $type = 'datalist', array $options = [])
    {
        return $this->createNamedBuilder($name, $type, $options)->getDatalist();
    }

    /**
     * @param mixed $type
     *
     * @return DatalistBuilder
     */
    public function createBuilder($type = 'datalist', array $options = [])
    {
        $name = $type instanceof DatalistType\DatalistTypeInterface
            ? $type->getName()
            : $type;

        return $this->createNamedBuilder($name, $type, $options);
    }

    /**
     * @param $name
     * @param mixed $type
     *
     * @return DatalistBuilder
     *
     * @throws \InvalidArgumentException
     */
    public function createNamedBuilder($name, $type = 'datalist', array $options = [])
    {
        // Determine datalist type
        if (\is_string($type)) {
            $type = $this->getType($type);
        } elseif (!$type instanceof DatalistType\DatalistTypeInterface) {
            throw new \InvalidArgumentException('The type must be a string or an instance of DatalistTypeInterface');
        }

        // Handle datalist options
        $resolver = new OptionsResolver();
        $type->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($options);

        // Build datalist
        $builder = new DatalistBuilder($name, $type, $resolvedOptions, $this, $this->formFactory);
        $type->buildDatalist($builder, $resolvedOptions);

        return $builder;
    }

    /**
     * @return DatalistType\DatalistTypeInterface
     */
    public function getType($alias)
    {
        if (!\array_key_exists($alias, $this->types)) {
            throw new \InvalidArgumentException(sprintf('Unknown type "%s"', $alias));
        }

        return $this->types[$alias];
    }

    /**
     * @param Type\DatalistTypeInterface $type
     */
    public function registerType(DatalistType\DatalistTypeInterface $type)
    {
        $this->types[\get_class($type)] = $type;
    }

    /**
     * @param $alias
     *
     * @return FieldType\FieldTypeInterface
     *
     * @throws \InvalidArgumentException
     */
    public function getFieldType($alias)
    {
        if (!\array_key_exists($alias, $this->fieldTypes)) {
            throw new \InvalidArgumentException(sprintf('Unknown field type "%s"', $alias));
        }

        return $this->fieldTypes[$alias];
    }

    public function registerFieldType(FieldType\FieldTypeInterface $fieldType)
    {
        $this->fieldTypes[\get_class($fieldType)] = $fieldType;
    }

    /**
     * @param string $alias
     *
     * @return FilterType\FilterTypeInterface
     *
     * @throws \InvalidArgumentException
     */
    public function getFilterType($alias)
    {
        if (!\array_key_exists($alias, $this->filterTypes)) {
            throw new \InvalidArgumentException(sprintf('Unknown filter type "%s"', $alias));
        }

        return $this->filterTypes[$alias];
    }

    public function registerFilterType(FilterType\FilterTypeInterface $filterType)
    {
        $this->filterTypes[\get_class($filterType)] = $filterType;
    }

    /**
     * @param string $alias
     *
     * @return ActionType\ActionTypeInterface
     *
     * @throws \InvalidArgumentException
     */
    public function getActionType($alias)
    {
        if (!\array_key_exists($alias, $this->actionTypes)) {
            throw new \InvalidArgumentException(sprintf('Unknown action type "%s"', $alias));
        }

        return $this->actionTypes[$alias];
    }

    /**
     * @param string $alias
     */
    public function registerActionType(ActionType\ActionTypeInterface $actionType)
    {
        $this->actionTypes[\get_class($actionType)] = $actionType;
    }

    protected function initialize()
    {
        if (0 === \count($this->actionTypes)) {
            $actionTypes = [
                new ActionType\SimpleActionType($this->router),
            ];
            foreach ($actionTypes as $actionType) {
                $this->actionTypes[\get_class($actionType)] = $actionType;
            }
        }

        if (0 === \count($this->fieldTypes)) {
            $fieldTypes = [
                new FieldType\BooleanFieldType(),
                new FieldType\DateTimeFieldType(),
                new FieldType\HeadingFieldType(),
                new FieldType\ImageFieldType(),
                new FieldType\LabelFieldType(),
                new FieldType\TextFieldType(),
                new FieldType\UrlFieldType(),
            ];
            foreach ($fieldTypes as $fieldType) {
                $this->fieldTypes[\get_class($fieldType)] = $fieldType;
            }
        }

        if (0 === \count($this->filterTypes)) {
            $filterTypes = [
                new FilterType\ChoiceFilterType(),
                new FilterType\EntityFilterType(),
                new FilterType\SearchFilterType(),
            ];
            foreach ($filterTypes as $filterType) {
                $this->filterTypes[\get_class($filterType)] = $filterType;
            }
        }

        if (0 === \count($this->types)) {
            $datalistTypes = [
                new DatalistType\DatalistType(),
            ];
            foreach ($datalistTypes as $datalistType) {
                $this->types[\get_class($datalistType)] = $datalistType;
            }
        }
    }
}
