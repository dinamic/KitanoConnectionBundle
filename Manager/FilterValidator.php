<?php

namespace Kitano\ConnectionBundle\Manager;

use Kitano\ConnectionBundle\Exception\InvalidFilterException;
use Kitano\ConnectionBundle\Model\ConnectionInterface;

use Symfony\Component\Validator\Validator;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Collection;

class FilterValidator
{
    protected $validator;
    
    /**
     * Validate and normalize input filters for connections retrieval
     *
     * @param array &$filters
     * @throws InvalidFilterException
     */
    public function validateFilters(array &$filters)
    {
        $filterConstraint = new Collection(array(
            'type' => array(
                new NotBlank(),
                new NotNull(),
            ),
            'status' => new Choice(array(
                'choices' => array(
                    ConnectionInterface::STATUS_CONNECTED,
                    ConnectionInterface::STATUS_DISCONNECTED,
                ),
                'min' => 0,
                'max' => 1,
                'strict' => true,
            )),
            'depth' => new Type('integer'),
        ));
        
        $filtersDefault = array(
            'depth' => 1,
            'status' => ConnectionInterface::STATUS_CONNECTED,
        );
        
        $filters = array_merge($filtersDefault, $filters);
        
        $errorList = $this->getValidator()->validateValue($filters, $filterConstraint);
        
        if (count($errorList) == 0) {
            return true;
        } else {
            throw new InvalidFilterException($errorList);
        }
    }
    
    public function setValidator(Validator $validator)
    {
        $this->validator = $validator;
    }
    
    public function getValidator()
    {
        return $this->validator;
    }
}