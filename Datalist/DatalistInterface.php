<?php

namespace Leapt\CoreBundle\Datalist;

use Leapt\CoreBundle\Datalist\Action\DatalistActionInterface;
use Leapt\CoreBundle\Datalist\Datasource\DatasourceInterface;
use Leapt\CoreBundle\Datalist\Field\DatalistFieldInterface;
use Leapt\CoreBundle\Datalist\Filter\DatalistFilterInterface;
use Leapt\CoreBundle\Datalist\Type\DatalistTypeInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Interface DatalistInterface
 * @package Leapt\CoreBundle\Datalist
 */
interface DatalistInterface extends \IteratorAggregate
{
    /**
     * @return DatalistTypeInterface
     */
    public function getType();

    /**
     * @param DatalistFieldInterface $field
     * @return DatalistInterface
     */
    public function addField(DatalistFieldInterface $field);

    /**
     * @return array
     */
    public function getFields();

    /**
     * @param Filter\DatalistFilterInterface $filter
     * @return DatalistInterface
     */
    public function addFilter(DatalistFilterInterface $filter);

    /**
     * @return array
     */
    public function getFilters();

    /**
     * @param Filter\DatalistFilterInterface $filter
     */
    public function setSearchFilter(DatalistFilterInterface $filter);

    /**
     * @return Filter\DatalistFilterInterface
     */
    public function getSearchFilter();

    /**
     * @param Action\DatalistActionInterface $action
     * @return DatalistInterface
     */
    public function addAction(DatalistActionInterface $action);

    /**
     * @return array
     */
    public function getActions();

    /**
     * @param DatasourceInterface $datasource
     *
     * @return DatalistInterface
     */
    public function setDatasource($datasource);

    /**
     * @return DatasourceInterface
     */
    public function getDatasource();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @param string $name
     * @return bool
     */
    public function hasOption($name);

    /**
     * @param string $name
     * @param mixed $default
     */
    public function getOption($name, $default = null);

    /**
     * @param int $page
     *
     * @return DatalistInterface
     */
    public function setPage($page);

    /**
     * @return bool
     */
    public function isFilterable();

    /**
     * @return bool
     */
    public function isSearchable();

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @return DatalistInterface
     */
    public function setSearchForm(FormInterface $form);

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @return DatalistInterface
     */
    public function setFilterForm(FormInterface $form);

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getSearchForm();

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getFilterForm();

    /**
     * @param mixed $data
     * @return DatalistInterface
     */
    public function bind($data);

    /**
     * @return string
     */
    public function getRoute();

    /**
     * @param string $route
     * @return DatalistInterface
     */
    public function setRoute($route);

    /**
     * @return array
     */
    public function getRouteParams();

    /**
     * @param array $routeParams
     * @return array
     */
    public function setRouteParams($routeParams);
}