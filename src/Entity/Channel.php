<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChannelRepository")
 */
class Channel {
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $name;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $identifier;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $provider;

  /**
   * @ORM\ManyToMany(targetEntity="App\Entity\Country", inversedBy="channels")
   */
  private $countries;

  /**
   * @ORM\ManyToMany(targetEntity="App\Entity\Genre", mappedBy="channels")
   */
  private $genres;

  public function __construct() {
    $this->countries = new ArrayCollection();
    $this->genres = new ArrayCollection();
  }

  public function getId(): ?int {
    return $this->id;
  }

  public function getName(): ?string {
    return $this->name;
  }

  public function setName(string $name): self {
    $this->name = $name;

    return $this;
  }

  public function getIdentifier(): ?string {
    return $this->identifier;
  }

  public function setIdentifier(string $identifier): self {
    $this->identifier = $identifier;

    return $this;
  }

  public function getProvider(): ?string {
    return $this->provider;
  }

  public function setProvider(string $provider): self {
    $this->provider = $provider;

    return $this;
  }

  /**
   * @return Collection|Country[]
   */
  public function getCountries(): Collection {
    return $this->countries;
  }

  public function addCountry(Country $country): self {
    if (!$this->countries->contains($country)) {
      $this->countries[] = $country;
    }

    return $this;
  }

  public function removeCountry(Country $country): self {
    if ($this->countries->contains($country)) {
      $this->countries->removeElement($country);
    }

    return $this;
  }

  /**
   * @return Collection|Genre[]
   */
  public function getGenres(): Collection {
    return $this->genres;
  }

  public function addGenre(Genre $genre): self {
    if (!$this->genres->contains($genre)) {
      $this->genres[] = $genre;
      $genre->addChannel($this);
    }

    return $this;
  }

  public function removeGenre(Genre $genre): self {
    if ($this->genres->contains($genre)) {
      $this->genres->removeElement($genre);
      $genre->removeChannel($this);
    }

    return $this;
  }

  public function __toString(): string {
    return $this->getName();
  }
}
