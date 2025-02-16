<?php

namespace App\Controller;

use App\Repository\FixtureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * Queries the database for fixtures to be displayed on the fixtures page
     *
     * @param FixtureRepository $fixtureRepository
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/rugby/getFixtures/', name: 'getFixtures')]
    public function getFixtures(FixtureRepository $fixtureRepository, Request $request): JsonResponse
    {
        if($request->query->get('currentFilter') === "team") {
            $teams = json_decode($request->query->get('filters'), true) ?: [];

            if(!empty($teams)) {
                return new JsonResponse($fixtureRepository->getFixturesByTeam($teams));
            } else {
                return new JsonResponse($fixtureRepository->getFixtures());
            }
        } elseif($request->query->get('currentFilter') === "league") {
            $leagues = json_decode($request->query->get('filters'), true) ?: [];

            if(!empty($leagues)) {
                return new JsonResponse($fixtureRepository->getFixturesByLeague($leagues));
            } else {
                return new JsonResponse($fixtureRepository->getFixtures());
            }
        } else {
            return new JsonResponse($fixtureRepository->getFixtures());
        }
    }

    /**
     * Queries the database for fixtures to be displayed on the archive page
     *
     * @param FixtureRepository $fixtureRepository
     * @param Request $request
     * @return JsonResponse
     * @throws DateMalformedStringException
     */
    #[Route('/rugby/getArchiveFixtures/', name: 'getArchiveFixtures')]
    public function getArchiveFixtures(FixtureRepository $fixtureRepository, Request $request): JsonResponse
    {
        $season = $request->query->get('currentSeason');

        if($request->query->get('currentFilter') === "team") {
            $teams = json_decode($request->query->get('filters'), true) ?: [];

            if(!empty($teams)) {
                return new JsonResponse($fixtureRepository->getArchiveFixturesByTeam($season, $teams));
            } else {
                return new JsonResponse($fixtureRepository->getArchiveFixtures($season));
            }
        } elseif($request->query->get('currentFilter') === "league") {
            $leagues = json_decode($request->query->get('filters'), true) ?: [];

            if(!empty($leagues)) {
                return new JsonResponse($fixtureRepository->getArchiveFixturesByLeague($season, $leagues));
            } else {
                return new JsonResponse($fixtureRepository->getArchiveFixtures($season));
            }
        } else {
            return new JsonResponse($fixtureRepository->getArchiveFixtures($season));
        }
    }

    /**
     * Queries the database for teams to be used in the filters
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
     * Queries the database for leagues to be used in the filters
     *
     * @param FixtureRepository $fixtureRepository
     * @return JsonResponse
     */
    #[Route('/rugby/getLeagues/', name: 'getLeagues')]
    public function getLeagues(FixtureRepository $fixtureRepository): JsonResponse
    {
        return new JsonResponse($fixtureRepository->getLeagues());
    }
}