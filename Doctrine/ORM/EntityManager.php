<?php
namespace Snowcap\CoreBundle\Doctrine\ORM;

use Doctrine\ORM\EntityManager as BaseEntityManager;
use Doctrine\ORM\Configuration;
use Doctrine\Common\EventManager;
use Doctrine\ORM\ORMException;
use Doctrine\DBAL\Connection;

use Snowcap\CoreBundle\Doctrine\ORM\Event\PreFlushEventArgs;

class EntityManager extends BaseEntityManager {
    /**
     * Factory method to create EntityManager instances.
     *
     * @param mixed $conn An array with the connection parameters or an existing
     *      Connection instance.
     * @param Configuration $config The Configuration instance to use.
     * @param EventManager $eventManager The EventManager instance to use.
     * @return EntityManager The created EntityManager.
     */
    public static function create($conn, Configuration $config, EventManager $eventManager = null)
    {
        if (!$config->getMetadataDriverImpl()) {
            throw ORMException::missingMappingDriverImpl();
        }

        if (is_array($conn)) {
            $conn = \Doctrine\DBAL\DriverManager::getConnection($conn, $config, ($eventManager ?: new EventManager()));
        } else if ($conn instanceof Connection) {
            if ($eventManager !== null && $conn->getEventManager() !== $eventManager) {
                 throw ORMException::mismatchedEventManager();
            }
        } else {
            throw new \InvalidArgumentException("Invalid argument: " . $conn);
        }

        return new EntityManager($conn, $config, $conn->getEventManager());
    }

    public function flush()
    {
        if ($this->getEventManager()->hasListeners('preFlush')) {
            $this->getEventManager()->dispatchEvent('preFlush', new PreFlushEventArgs($this));
        }
        parent::flush();
    }


}