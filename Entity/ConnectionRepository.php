<?php

namespace Kitano\ConnectionBundle\Entity;

use Doctrine\ORM\EntityRepository;

use Kitano\ConnectionBundle\Entity\Connection;

/**
 * ConnectionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ConnectionRepository extends EntityRepository
{
    protected function extractInformations(NodeInterface $node)
    {
        $classMetadata = $this->_em->getClassMetadata(get_class($source));
        
        return array(
            'object_class' => $classMetadata->getName(),
            'object_id' => $classMetadata->getIdentifierValues($node),
        );
    }
    
    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $source
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $destination
     * @return \Kitano\ConnectionBundle\Entity\Connection
     */
    public function connect(Connection $connection)
    {
        $sourceInformations = $this->extractInformations($this->getSource());
        $destinationInformations = $this->extractInformations($this->getDestination());
        
        $connection->setSourceObjectId($sourceInformations["object_id"]);
        $connection->setSourceObjectClass($sourceInformations["object_class"]);
        $connection->setDestinationObjectId($destinationInformations["object_id"]);
        $connection->setDestinationObjectClass($destinationInformations["object_class"]);
        $connection->connect();
        
        $this->_em->persist($connection);
        $this->_em->flush();
        
        return $connection;
    }
    
    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $source
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $destination
     * @return \Kitano\ConnectionBundle\Entity\Connection
     */
    public function disconnect(Connection $connection)
    {
        $sourceInformations = $this->extractInformations($this->getSource());
        $destinationInformations = $this->extractInformations($this->getDestination());
        
        $connection->setSourceObjectId($sourceInformations["object_id"]);
        $connection->setSourceObjectClass($sourceInformations["object_class"]);
        $connection->setDestinationObjectId($destinationInformations["object_id"]);
        $connection->setDestinationObjectClass($destinationInformations["object_class"]);
        $connection->disconnect();
        
        $this->_em->persist($connection);
        $this->_em->flush();
        
        return $connection;
    }
}