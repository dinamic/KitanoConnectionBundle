<?php

namespace Kitano\ConnectionBundle\Entity;

use Doctrine\ORM\EntityRepository;

use Kitano\ConnectionBundle\ConnectionRepositoryInterface;
use Kitano\ConnectionBundle\Proxy\Connection;
use Kitano\ConnectionBundle\Model\NodeInterface;

/**
 * ConnectionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ConnectionRepository extends EntityRepository implements ConnectionRepositoryInterface
{
    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array $filters
     * @return type
     */
    public function getConnectionsWithSource(NodeInterface $node, array $filters = array())
    {
        $objectInformations = $this->extractInformations($node);
        
        $objectClass = $objectInformations["object_class"];
        $objectId = $objectInformations["object_id"];
        
        $queryBuilder = $this->createQueryBuilder("connection");
        $queryBuilder->where("connection.sourceObjectClass = :objectClass");
        $queryBuilder->andWhere("connection.sourceObjectId = :objectId");
        $queryBuilder->setParameter("objectClass", $objectClass);
        $queryBuilder->setParameter("objectId", $objectId);
        
        return $queryBuilder->getQuery()->getResult();
    }
    
    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array $filters
     * @return type
     */
    public function getConnectionsWithDestination(NodeInterface $node, array $filters = array())
    {
        $objectInformations = $this->extractInformations($node);
        
        $objectClass = $objectInformations["object_class"];
        $objectId = $objectInformations["object_id"];
        
        $queryBuilder = $this->createQueryBuilder("connection");
        $queryBuilder->where("connection.destinationObjectClass = :objectClass");
        $queryBuilder->andWhere("connection.destinationObjectId = :objectId");
        $queryBuilder->setParameter("objectClass", $objectClass);
        $queryBuilder->setParameter("objectId", $objectId);
        
        return $queryBuilder->getQuery()->getResult();
    }
    
    /**
     * @param \Kitano\ConnectionBundle\Proxy\Connection $connection
     * @return \Kitano\ConnectionBundle\Proxy\Connection
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
     * @param \Kitano\ConnectionBundle\Proxy\Connection $connection
     * @return \Kitano\ConnectionBundle\Proxy\Connection
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
    
    /**
     * @param \Kitano\ConnectionBundle\Proxy\Connection $connection
     * @return \Kitano\ConnectionBundle\Entity\ConnectionRepository
     */
    public function destroy(Connection $connection)
    {
        $this->_em->remove($connection);
        $this->_em->flush();
        
        return $this;
    }
    
    /**
     * @param \Kitano\ConnectionBundle\Entity\NodeInterface $node
     * @return array
     */
    protected function extractInformations(NodeInterface $node)
    {
        $classMetadata = $this->_em->getClassMetadata(get_class($node));
        
        return array(
            'object_class' => $classMetadata->getName(),
            'object_id' => $classMetadata->getIdentifierValues($node),
        );
    }
}
