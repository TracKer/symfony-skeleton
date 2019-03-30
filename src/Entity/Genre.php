<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GenreRepository")
 */
class Genre {
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=255, unique=true)
   */
  private $name;

  /**
   * @ORM\ManyToMany(targetEntity="App\Entity\Channel", mappedBy="genres")
   */
  private $channels;

  public function __construct() {
    $this->channels = new ArrayCollection();
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

  /**
   * @return Collection|Channel[]
   */
  public function getChannels(): Collection {
    return $this->channels;
  }

  public function addChannel(Channel $channel): self {
    if (!$this->channels->contains($channel)) {
      $this->channels[] = $channel;
    }

    return $this;
  }

  public function removeChannel(Channel $channel): self {
    if ($this->channels->contains($channel)) {
      $this->channels->removeElement($channel);
    }

    return $this;
  }

  public function __toString(): string {
    return $this->getName();
  }
}
