<?php

namespace App\Entity;

use App\Repository\ExpeditionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExpeditionRepository::class)
 */
class Expedition
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file_exp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileExp(): ?string
    {
        return $this->file_exp;
    }

    public function setFileExp(?string $file_exp): self
    {
        $this->file_exp = $file_exp;

        return $this;
    }
}
