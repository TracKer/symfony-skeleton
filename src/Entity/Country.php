<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CountryRepository")
 */
class Country {
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=2)
   */
  private $code;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $name;

  /**
   * @ORM\ManyToMany(targetEntity="App\Entity\Channel", mappedBy="country")
   */
  private $channels;

  public function __construct() {
    $this->channels = new ArrayCollection();
  }

  public function getId(): ?int {
    return $this->id;
  }

  public function getCode(): ?string {
    return $this->code;
  }

  public function setCode(string $code): self {
    $this->code = $code;

    return $this;
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
      $channel->addCountry($this);
    }

    return $this;
  }

  public function removeChannel(Channel $channel): self {
    if ($this->channels->contains($channel)) {
      $this->channels->removeElement($channel);
      $channel->removeCountry($this);
    }

    return $this;
  }
}
