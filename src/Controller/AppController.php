<?php

namespace App\Controller;

use App\Repository\FixtureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateMalformedStringException;

class AppController extends AbstractController
{
    /**
     * Renders the homepage
     *
     * @return Response
     */
    #[Route('/', name: 'rugby')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    /**
     * Queries the database for recent fixtures to be displayed on the homepage
     *
     * @param FixtureRepository $fixtureRepository
     * @return JsonResponse
     * @throws DateMalformedStringException
     */
    #[Route('/rugby/getRecentFixtures/', name: 'getRecentFixtures')]
    public function getRecentFixtures(FixtureRepository $fixtureRepository): JsonResponse
    {
        return new JsonResponse($fixtureRepository->getRecentFixtures());
    }

    /**
     * Renders the archive
     *
     * @return Response
     */
    #[Route('/archive', name: 'archive')]
    public function archive(): Response
    {
        return $this->render('archive.html.twig');
    }

    /**
     * Queries the database for past fixtures by season to be displayed on the archive
     *
     * @param FixtureRepository $fixtureRepository
     * @param string $season
     * @return JsonResponse
     * @throws DateMalformedStringException
     */
    #[Route('/rugby/getPastFixtures/{season}', name: 'getPastFixturesBySeason')]
    public function getPastFixturesBySeason(FixtureRepository $fixtureRepository, string $season): JsonResponse
    {
        return new JsonResponse($fixtureRepository->getPastFixturesBySeason($season));
    }

    /**
     * Queries the database for past fixtures by season and league to be displayed on the archive
     *
     * @param FixtureRepository $fixtureRepository
     * @param string $season
     * @param string $league
     * @return JsonResponse
     * @throws DateMalformedStringException
     */
    #[Route('/rugby/getPastFixtures/{season}/{league}', name: 'getPastFixturesByLeague')]
    public function getPastFixturesByLeague(FixtureRepository $fixtureRepository, string $season, string $league): JsonResponse
    {
        return new JsonResponse($fixtureRepository->getPastFixturesByLeague($season, $league));
    }

    /**
     * Queries the database for leagues to be used in the league filter on the archive and fixtures page
     *
     * @param FixtureRepository $fixtureRepository
     * @return JsonResponse
     */
    #[Route('/rugby/getLeagues/', name: 'getLeagues')]
    public function getLeagues(FixtureRepository $fixtureRepository): JsonResponse
    {
        return new JsonResponse($fixtureRepository->getLeagues());
    }

    /**
     * Queries the database for past fixtures by season and team to be displayed on the archive
     *
     * @param FixtureRepository $fixtureRepository
     * @param string $season
     * @param string $team
     * @return JsonResponse
     * @throws DateMalformedStringException
     */
    #[Route('/rugby/getPastFixturesByTeam/{season}/{team}', name: 'getPastFixturesByTeam')]
    public function getPastFixturesByTeam(FixtureRepository $fixtureRepository, string $season, string $team): JsonResponse
    {
        return new JsonResponse($fixtureRepository->getPastFixturesByTeam($season, $team));
    }

    /**
     * Queries the database for teams to be used in the team filter on the archive and fixtures page
     *
     * @param FixtureRepository $fixtureRepository
     * @return JsonResponse
     */
    #[Route('/rugby/getTeams/', name: 'getTeams')]
    public function getTeams(FixtureRepository $fixtureRepository): JsonResponse
    {
        return new JsonResponse($fixtureRepository->getTeams());
    }

    /**
     * Renders the fixtures page
     *
     * @return Response
     */
    #[Route('/fixtures', name: 'fixtures')]
    public function fixtures(): Response
    {
        return $this->render('fixtures.html.twig');
    }

    /**
     * Queries the database for fixtures to be displayed on the fixtures page
     *
     * @param FixtureRepository $fixtureRepository
     * @return JsonResponse
     */
    #[Route('/rugby/getFixtures/', name: 'getFixtures')]
    public function getFixtures(FixtureRepository $fixtureRepository): JsonResponse
    {
        return new JsonResponse($fixtureRepository->getFixtures());
    }

    /**
     * Queries the database for fixtures by league to be displayed on the fixtures page
     *
     * @param FixtureRepository $fixtureRepository
     * @param string $league
     * @return JsonResponse
     */
    #[Route('/rugby/getFixtures/{league}', name: 'getFixturesByLeague')]
    public function getFixturesByLeague(FixtureRepository $fixtureRepository, string $league): JsonResponse
    {
        return new JsonResponse($fixtureRepository->getFixturesByLeague($league));
    }

    /**
     * Queries the database for fixtures by team to be displayed on the fixtures page
     *
     * @param FixtureRepository $fixtureRepository
     * @param string $team
     * @return JsonResponse
     */
    #[Route('/rugby/getFixturesByTeam/{team}', name: 'getFixturesByTeam')]
    public function getFixturesByTeam(FixtureRepository $fixtureRepository, string $team): JsonResponse
    {
        return new JsonResponse($fixtureRepository->getFixturesByTeam($team));
    }
}
