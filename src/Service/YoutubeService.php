<?php

namespace App\Service;

use App\Repository\FixtureRepository;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Google\Client;
use Google\Service\Exception;
use Google\Service\YouTube;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class YoutubeService
{
    protected EntityManager $entityManager;
    protected FixtureRepository $fixtureRepository;
    private string $apiKey;
    private ParameterBagInterface $parameters;

    public function __construct(
        EntityManager $entityManager,
        FixtureRepository $fixtureRepository,
        string $apiKey,
        ParameterBagInterface $parameters
    )
    {
        $this->entityManager = $entityManager;
        $this->fixtureRepository = $fixtureRepository;
        $this->apiKey = $apiKey;
        $this->parameters = $parameters;
    }

    public function connect(): YouTube
    {
        $apiKey = $this->apiKey;

        $client = new Client();
        $client->setDeveloperKey($apiKey);
        return new YouTube($client);
    }

    /**
     * @throws Exception
     */
    public function getHighlights(): void
    {
        $service = $this->connect();

        $highlights = $this->parameters->get('highlights');

        foreach ($highlights as $highlight) {
            if($highlight['active']) {

                $response = $service->playlistItems->listPlaylistItems('snippet',
                    ['playlistId' => $highlight['channelId'], 'maxResults' => 10],
                );

                $this->getEmbedUrl(
                    $this->getYoutubeTitleAndId($response, $highlight['searchTerm']),
                    $this->fixtureRepository->getFixturesNoHighlights($highlight['leagueName'])
                );
            }
        }
    }

    public function getYoutubeTitleAndId($response, $search): array
    {
        $videos = [];

        foreach ($response as $video) {
            if (str_contains($video->snippet->title, $search)) {
                $videos[] = [$video->snippet->title, $video->snippet->resourceId->videoId];
            }
        }

        return $videos;
    }

    public function getEmbedUrl($videos, $fixtures): void
    {
        $videoString = 'https://www.youtube.com/embed/';
        $altVideoString = 'https://www.youtube.com/watch?v=';

        foreach($videos as $video) {

            $videoTitle = $video[0];

            foreach ($fixtures as $fixture) {

                $homeTeams = [
                    $fixture['homeTeam'],
                    $fixture['alternativeHomeTeam'],
                    strtoupper($fixture['homeTeam']),
                    strtoupper($fixture['alternativeHomeTeam'])
                ];

                $awayTeams = [
                    $fixture['awayTeam'],
                    $fixture['alternativeAwayTeam'],
                    strtoupper($fixture['awayTeam']),
                    strtoupper($fixture['alternativeAwayTeam'])
                ];

                $found = false;

                foreach ($homeTeams as $homeTeam) {
                    foreach ($awayTeams as $awayTeam) {
                        if (str_contains($videoTitle, $homeTeam) && str_contains($videoTitle, $awayTeam)) {
                            $found = true;
                            break 2;
                        }
                    }
                }

                if ($found) {
                    $videoStringToUpdate = ($fixture['league'] == 'Top 14' || $fixture['league'] == 'Pro D2')
                        ? $altVideoString . $video[1]
                        : $videoString . $video[1];

                    $this->fixtureRepository->update($fixture['id'], $videoStringToUpdate);

                    echo "Video Title: " . $video[0] . PHP_EOL;
                    echo "Fixture Updated: " . $fixture['id'] . PHP_EOL;
                }
            }
        }
    }
}