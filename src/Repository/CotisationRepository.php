<?php

namespace App\Repository;

use App\Entity\Cotisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cotisation>
 *
 * @method Cotisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cotisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cotisation[]    findAll()
 * @method Cotisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CotisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cotisation::class);
    }

    public function save(Cotisation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Cotisation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Dans App\Repository\CotisationRepository

public function findCotisationsForUser($utilisateur)
{
    return $this->createQueryBuilder('c')
        ->join('App\Entity\Participer', 'p', 'WITH', 'c.id = p.cotisation')
        ->where('p.utilisateur = :utilisateur')
        ->setParameter('utilisateur', $utilisateur)
        ->getQuery()
        ->getResult();
}

// Optionnel: méthode pour différencier entre propriétaire et membre
public function findCotisationsCreatedByUser($utilisateur)
{
    return $this->createQueryBuilder('c')
        ->join('App\Entity\Participer', 'p', 'WITH', 'c.id = p.cotisation')
        ->where('p.utilisateur = :utilisateur')
        ->andWhere('p.proprietaire = true')
        ->setParameter('utilisateur', $utilisateur)
        ->getQuery()
        ->getResult();
}

public function findCotisationsJoinedByUser($utilisateur)
{
    return $this->createQueryBuilder('c')
        ->join('App\Entity\Participer', 'p', 'WITH', 'c.id = p.cotisation')
        ->where('p.utilisateur = :utilisateur')
        ->andWhere('p.proprietaire = false')
        ->setParameter('utilisateur', $utilisateur)
        ->getQuery()
        ->getResult();
}
}