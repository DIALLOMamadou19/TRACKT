<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResearchRepository")
 */
class Research
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $keywords;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $abstract;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $userid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $upload;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $view;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $download;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userimage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userlocation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $addtime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $recommendname;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getAbstract(): ?string
    {
        return $this->abstract;
    }

    public function setAbstract(?string $abstract): self
    {
        $this->abstract = $abstract;

        return $this;
    }

    public function getUserid(): ?int
    {
        return $this->userid;
    }

    public function setUserid(?int $userid): self
    {
        $this->userid = $userid;

        return $this;
    }

    public function getUpload(): ?string
    {
        return $this->upload;
    }

    public function setUpload(?string $upload): self
    {
        $this->upload = $upload;

        return $this;
    }

    public function getView(): ?int
    {
        return $this->view;
    }

    public function setView(?int $view): self
    {
        $this->view = $view;

        return $this;
    }

    public function getDownload(): ?int
    {
        return $this->download;
    }

    public function setDownload(?int $download): self
    {
        $this->download = $download;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUserimage(): ?string
    {
        return $this->userimage;
    }

    public function setUserimage(?string $userimage): self
    {
        $this->userimage = $userimage;

        return $this;
    }

    public function getUserlocation(): ?string
    {
        return $this->userlocation;
    }

    public function setUserlocation(?string $userlocation): self
    {
        $this->userlocation = $userlocation;

        return $this;
    }

    public function getAddtime(): ?\DateTimeInterface
    {
        return $this->addtime;
    }

    public function setAddtime(?\DateTimeInterface $addtime): self
    {
        $this->addtime = $addtime;

        return $this;
    }

    public function getRecommendname(): ?string
    {
        return $this->recommendname;
    }

    public function setRecommendname(?string $recommendname): self
    {
        $this->recommendname = $recommendname;

        return $this;
    }

}
