<?php

namespace App\Service;

use App\Repository\ActivityCodeRepository;

class ActivityCodeSortingService
{
    private ActivityCodeRepository $activityCodeRepository;

    public function __construct(ActivityCodeRepository $activityCodeRepository)
    {
        $this->activityCodeRepository = $activityCodeRepository;
    }

    /**
     * Sort activity codes by a given field and order.
     *
     * @param string $field Field to sort by (activityCode, createdAt, updatedAt)
     * @param string $order Sorting order (ASC or DESC)
     * @return array
     */
    public function sortActivityCodes(string $field = 'activityCode', string $order = 'ASC'): array
    {
        $validFields = ['activityCode', 'createdAt', 'updatedAt'];
        $validOrders = ['ASC', 'DESC'];
        
        if (!in_array($field, $validFields, true) || !in_array($order, $validOrders, true)) {
            throw new \InvalidArgumentException('Invalid sort field or order');
        }
        
        return $this->activityCodeRepository->createQueryBuilder('ac')
            ->orderBy("ac.$field", $order)
            ->getQuery()
            ->getResult();
    }
}
