<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FileRepository")
 */
class File
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filename;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRid(): ?int
    {
        return $this->rid;
    }

    public function setRid(?int $rid): self
    {
        $this->rid = $rid;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }
}
