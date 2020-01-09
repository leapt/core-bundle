<?php

namespace Leapt\CoreBundle\Datalist\Type;

/**
 * Class DatalistType.
 */
class DatalistType extends AbstractDatalistType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'datalist';
    }

    /**
     * @return string
     */
    public function getBlockName()
    {
        return 'datalist';
    }
}
