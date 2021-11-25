<?php
namespace App\Repository;

use App\Entity\Draw;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DrawsRepository extends ServiceEntityRepository
{
    const DRAWS_LIMIT = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Draw::class);
    }
}