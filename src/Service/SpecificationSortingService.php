<?php

namespace App\Service;

use App\Repository\SpecificationRepository;

class SpecificationSortingService
{
    private specificationRepository $specificationRepository;

    public function __construct(SpecificationRepository $specificationRepository)
    {
        $this->specificationRepository = $specificationRepository;
    }

    /**
     * Sort specification by a given field and order.
     *
     * @param string $field Field to sort by (name, createdAt, updatedAt)
     * @param string $order Sorting order (ASC or DESC)
     * @return array
     */
    public function sortSpecification(string $field = 'name', string $order = 'ASC'): array
    {
        $validFields = ['name', 'createdAt', 'updatedAt'];
        $validOrders = ['ASC', 'DESC'];
        
        if (!in_array($field, $validFields, true) || !in_array($order, $validOrders, true)) {
            throw new \InvalidArgumentException('Invalid sort field or order');
        }
        
        return $this->specificationRepository->createQueryBuilder('u')
            ->orderBy("u.$field", $order)
            ->getQuery()
            ->getResult();
    }
}
