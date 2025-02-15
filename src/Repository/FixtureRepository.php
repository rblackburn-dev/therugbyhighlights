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
     * Get recent fixtures for the homepage
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
     * Get past fixtures by season for the archive
     *
     * @param string $season
     * @return mixed
     * @throws DateMalformedStringException
     */
    public function getPastFixturesBySeason(string $season): mixed
    {
        $date = new DateTime();
        $date->modify('-' . 4 . ' days');

        return $this->createQueryBuilder('f')
            ->select('f.id, f.league, f.homeTeam, f.awayTeam, f.kickOff, f.highlights, f.season')
            ->andWhere('f.kickOff < :date')
            ->setParameter('date', $date)
            ->andWhere('f.season = :season')
            ->setParameter('season', $season)
            ->orderBy('f.kickOff', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get past fixtures by season and league for the archive
     *
     * @param string $season
     * @param string $league
     * @return mixed
     * @throws DateMalformedStringException
     */
    public function getPastFixturesByLeague(string $season, string $league): mixed
    {
        $date = new DateTime();
        $date->modify('-' . 4 . ' days');

        return $this->createQueryBuilder('f')
            ->select('f.id, f.league, f.homeTeam, f.awayTeam, f.kickOff, f.highlights, f.season')
            ->andWhere('f.kickOff < :date')
            ->setParameter('date', $date)
            ->andWhere('f.season = :season')
            ->setParameter('season', $season)
            ->andWhere('f.league = :league')
            ->setParameter('league', $league)
            ->orderBy('f.kickOff', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get leagues to be used in the league filter on the archive and fixtures page
     *
     * @return mixed
     */
    public function getLeagues(): mixed
    {
        return $this->createQueryBuilder('f')
            ->select('DISTINCT f.league')
            ->orderBy('f.league', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get past fixtures by season and team for the archive
     *
     * @param string $season
     * @param string $team
     * @return mixed
     * @throws DateMalformedStringException
     */
    public function getPastFixturesByTeam(string $season, string $team): mixed
    {
        $date = new DateTime();
        $date->modify('-' . 4 . ' days');

        return $this->createQueryBuilder('f')
            ->select('f.id, f.league, f.homeTeam, f.awayTeam, f.kickOff, f.highlights, f.season')
            ->andWhere('f.homeTeam = :team')
            ->orWhere('f.awayTeam = :team')
            ->setParameter('team', $team)
            ->andWhere('f.kickOff < :date')
            ->setParameter('date', $date)
            ->andWhere('f.season = :season')
            ->setParameter('season', $season)
            ->orderBy('f.kickOff', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get teams to be used in the team filter on the archive and fixtures page
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
     * Get fixtures for the fixtures page
     *
     * @return mixed
     */
    public function getFixtures(): mixed
    {
        $today = new DateTime();

        return $this->createQueryBuilder('f')
            ->select('f.id, f.league, f.homeTeam, f.awayTeam, f.kickOff, f.highlights, f.season')
            ->andWhere('f.kickOff > :today')
            ->setParameter('today', $today)
            ->orderBy('f.kickOff', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get fixtures by league
     *
     * @param string $league
     * @return mixed
     */
    public function getFixturesByLeague(string $league): mixed
    {
        $today = new DateTime();

        return $this->createQueryBuilder('f')
            ->select('f.id, f.league, f.homeTeam, f.awayTeam, f.kickOff, f.highlights, f.season')
            ->andWhere('f.league = :league')
            ->setParameter('league', $league)
            ->andWhere('f.kickOff > :today')
            ->setParameter('today', $today)
            ->orderBy('f.kickOff', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get fixtures by team
     *
     * @param string $team
     * @return mixed
     */
    public function getFixturesByTeam(string $team): mixed
    {
        $today = new DateTime();

        return $this->createQueryBuilder('f')
            ->select('f.id, f.league, f.homeTeam, f.awayTeam, f.kickOff, f.highlights, f.season')
            ->andWhere('f.homeTeam = :team')
            ->orWhere('f.awayTeam = :team')
            ->setParameter('team', $team)
            ->andWhere('f.kickOff > :today')
            ->setParameter('today', $today)
            ->orderBy('f.kickOff', 'ASC')
            ->getQuery()
            ->getResult();
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