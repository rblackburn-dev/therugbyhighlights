<?php

namespace App\Repository;

use App\Entity\Fixture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;
use DateMalformedStringException;

/**
 * @extends ServiceEntityRepository<Fixture>
 *
 * @method Fixture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fixture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fixture[]    findAll()
 * @method Fixture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FixtureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fixture::class);
    }

    /**
     * Get recent fixtures
     *
     * @return mixed
     * @throws DateMalformedStringException
     */
    public function getRecentFixtures(): mixed
    {
        $date = new DateTime();
        $date->modify('-' . 5 . ' days');

        return $this->createQueryBuilder('f')
            ->select('f.id, f.league, f.homeTeam, f.awayTeam, f.kickOff, f.highlights, f.season')
            ->andWhere('f.kickOff > :date')
            ->setParameter('date', $date)
            ->orderBy('f.kickOff', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get filtered fixtures
     *
     * @param array $criteria
     * @return mixed
     */
    private function getFilteredFixtures(array $criteria = []): mixed
    {
        $today = new DateTime();

        $qb = $this->createQueryBuilder('f')
            ->select('f.id, f.league, f.homeTeam, f.awayTeam, f.kickOff, f.highlights, f.season')
            ->andWhere('f.kickOff > :today')
            ->setParameter('today', $today)
            ->orderBy('f.kickOff', 'ASC');

        if (!empty($criteria['leagues'])) {
            $qb->andWhere('f.league IN (:leagues)')
                ->setParameter('leagues', $criteria['leagues']);
        }

        if (!empty($criteria['teams'])) {
            $qb->andWhere('f.homeTeam IN (:teams) OR f.awayTeam IN (:teams)')
                ->setParameter('teams', $criteria['teams']);
        }

        return $qb->getQuery()->getResult();
    }

    public function getFixtures(): mixed
    {
        return $this->getFilteredFixtures();
    }

    public function getFixturesByLeague(array $leagues): mixed
    {
        return $this->getFilteredFixtures(['leagues' => $leagues]);
    }

    public function getFixturesByTeam(array $teams): mixed
    {
        return $this->getFilteredFixtures(['teams' => $teams]);
    }

    /**
     * Get filtered archive fixtures
     *
     * @param string $season
     * @param array $criteria
     * @return mixed
     * @throws DateMalformedStringException
     */
    private function getArchiveFilteredFixtures(string $season, array $criteria = []): mixed
    {
        $date = new DateTime();
        $date->modify('-' . 4 . ' days');

        $qb = $this->createQueryBuilder('f')
            ->select('f.id, f.league, f.homeTeam, f.awayTeam, f.kickOff, f.highlights, f.season')
            ->andWhere('f.kickOff < :date')
            ->setParameter('date', $date)
            ->andWhere('f.season = :season')
            ->setParameter('season', $season)
            ->orderBy('f.kickOff', 'DESC');

        if (!empty($criteria['leagues'])) {
            $qb->andWhere('f.league IN (:leagues)')
                ->setParameter('leagues', $criteria['leagues']);
        }

        if (!empty($criteria['teams'])) {
            $qb->andWhere('f.homeTeam IN (:teams) OR f.awayTeam IN (:teams)')
                ->setParameter('teams', $criteria['teams']);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @throws DateMalformedStringException
     */
    public function getArchiveFixtures(string $season): mixed
    {
        return $this->getArchiveFilteredFixtures($season);
    }

    /**
     * @throws DateMalformedStringException
     */
    public function getArchiveFixturesByLeague(string $season, array $leagues): mixed
    {
        return $this->getArchiveFilteredFixtures($season, ['leagues' => $leagues]);
    }

    /**
     * @throws DateMalformedStringException
     */
    public function getArchiveFixturesByTeam(string $season, array $teams): mixed
    {
        return $this->getArchiveFilteredFixtures($season, ['teams' => $teams]);
    }

    /**
     * Get teams to be used in the filters
     *
     * @return array
     */
    public function getTeams(): array
    {
        $homeTeamsQuery = $this->createQueryBuilder('f')
            ->select('DISTINCT f.homeTeam')
            ->orderBy('f.homeTeam', 'ASC')
            ->getQuery()
            ->getResult();

        $awayTeamsQuery = $this->createQueryBuilder('f')
            ->select('DISTINCT f.awayTeam')
            ->orderBy('f.awayTeam', 'ASC')
            ->getQuery()
            ->getResult();

        $homeTeamNames = array_column($homeTeamsQuery, 'homeTeam');
        $awayTeamNames = array_column($awayTeamsQuery, 'awayTeam');

        $allTeams = array_unique(array_merge($homeTeamNames, $awayTeamNames));

        sort($allTeams);

        return $allTeams;
    }

    /**
     * Get leagues to be used in the filters
     *
     * @return array
     */
    public function getLeagues(): array
    {
        $result = $this->createQueryBuilder('f')
            ->select('DISTINCT f.league')
            ->orderBy('f.league', 'ASC')
            ->getQuery()
            ->getArrayResult();

        return array_map(function ($item) {
            return $item['league'];
        }, $result);
    }

    /**
     * Get finished fixtures that do not have highlights
     *
     * @param string $league
     * @return mixed
     */
    public function getFixturesNoHighlights(string $league): mixed
    {
        $today = new DateTime();

        return $this->createQueryBuilder('f')
            ->select('f.id, f.league, f.homeTeam, f.awayTeam, f.alternativeHomeTeam, f.alternativeAwayTeam, f.kickOff, f.highlights')
            ->andWhere('f.highlights is NULL')
            ->andWhere('f.kickOff < :today')
            ->setParameter('today', $today)
            ->andWhere('f.league = :league')
            ->setParameter('league', $league)
            ->orderBy('f.kickOff', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Update fixture with highlights link
     *
     * @param int $id
     * @param string $highlights
     * @return Fixture
     */
    public function update(int $id, string $highlights): Fixture
    {
        $fixture = $this->find($id);

        $fixture->setHighlights($highlights);

        $this->getEntityManager()->flush();

        return $fixture;
    }
}