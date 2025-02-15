<?php

namespace App\Entity;

use App\Repository\FixtureRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FixtureRepository::class)]
class Fixture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $league = null;

    #[ORM\Column(length: 255)]
    private ?string $homeTeam = null;

    #[ORM\Column(length: 255)]
    private ?string $awayTeam = null;

    #[ORM\Column(length: 255)]
    private ?string $alternativeHomeTeam = null;

    #[ORM\Column(length: 255)]
    private ?string $alternativeAwayTeam = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $kickOff = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $highlights = null;

    #[ORM\Column(length: 255)]
    private ?string $season = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLeague(): ?string
    {
        return $this->league;
    }

    public function setLeague(string $league): static
    {
        $this->league = $league;

        return $this;
    }

    public function getHomeTeam(): ?string
    {
        return $this->homeTeam;
    }

    public function setHomeTeam(string $homeTeam): static
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }

    public function getAwayTeam(): ?string
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(string $awayTeam): static
    {
        $this->awayTeam = $awayTeam;

        return $this;
    }

    public function getAlternativeHomeTeam(): ?string
    {
        return $this->alternativeHomeTeam;
    }

    public function setAlternativeHomeTeam(string $alternativeHomeTeam): static
    {
        $this->alternativeHomeTeam = $alternativeHomeTeam;

        return $this;
    }

    public function getAlternativeAwayTeam(): ?string
    {
        return $this->alternativeAwayTeam;
    }

    public function setAlternativeAwayTeam(string $alternativeAwayTeam): static
    {
        $this->alternativeAwayTeam = $alternativeAwayTeam;

        return $this;
    }

    public function getKickOff(): ?DateTimeInterface
    {
        return $this->kickOff;
    }

    public function setKickOff(DateTimeInterface $kickOff): static
    {
        $this->kickOff = $kickOff;

        return $this;
    }

    public function getHighlights(): ?string
    {
        return $this->highlights;
    }

    public function setHighlights(?string $highlights): static
    {
        $this->highlights = $highlights;

        return $this;
    }

    public function getSeason(): ?string
    {
        return $this->season;
    }

    public function setSeason(string $season): static
    {
        $this->season = $season;

        return $this;
    }
}