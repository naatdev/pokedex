<?php

namespace App\Entity;

use App\Repository\PokemonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PokemonRepository::class)]
class Pokemon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $happiness;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $capture_rate;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $color;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $legendary;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $mythical;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHappiness(): ?int
    {
        return $this->happiness;
    }

    public function setHappiness(?int $happiness): self
    {
        $this->happiness = $happiness;

        return $this;
    }

    public function getCaptureRate(): ?int
    {
        return $this->capture_rate;
    }

    public function setCaptureRate(?int $capture_rate): self
    {
        $this->capture_rate = $capture_rate;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function isLegendary(): ?bool
    {
        return $this->legendary;
    }

    public function setLegendary(?bool $legendary): self
    {
        $this->legendary = $legendary;

        return $this;
    }

    public function isMythical(): ?bool
    {
        return $this->mythical;
    }

    public function setMythical(?bool $mythical): self
    {
        $this->mythical = $mythical;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function toArrayForCSV(): array
    {
        $return = [
            'name'          => $this->getName(),
            'hapiness'      => $this->getHappiness(),
            'capture_rate'  => $this->getCaptureRate(),
            'legendary'     => $this->isLegendary() ? 1 : 0,
            'mythical'      => $this->isMythical() ? 1 : 0,
            'color'         => $this->getColor(),
        ];

        return $return;
    }
}
