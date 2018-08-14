<?php

namespace App\Repository;

use App\Entity\Slide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Slide|null find($id, $lockMode = null, $lockVersion = null)
 * @method Slide|null findOneBy(array $criteria, array $orderBy = null)
 * @method Slide[]    findAll()
 * @method Slide[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SlideRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Slide::class);
    }

}
