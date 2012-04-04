<?php
namespace Snowcap\CoreBundle\Doctrine\ORM\Event;

/**
 * Provides event arguments for the preFlush event.
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.doctrine-project.com
 * @since       2.0
 * @version     $Revision$
 * @author      Roman Borschel <roman@code-factory.de>
 * @author      Benjamin Eberlei <kontakt@beberlei.de>
 */
class PreFlushEventArgs extends \Doctrine\Common\EventArgs
{
    /**
     * @var EntityManager
     */
    private $_em;

    public function __construct($em)
    {
        $this->_em = $em;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->_em;
    }
}