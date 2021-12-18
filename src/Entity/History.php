<?php

namespace App\Entity;

use App\Repository\HistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistoryRepository::class)
 */
class History
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $request_check;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $received;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $repair;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $repair_finished;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $send;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $finished;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequestCheck(): ?bool
    {
        return $this->request_check;
    }

    public function setRequestCheck(?bool $request_check): self
    {
        $this->request_check = $request_check;

        return $this;
    }

    public function getReceived(): ?bool
    {
        return $this->received;
    }

    public function setReceived(?bool $received): self
    {
        $this->received = $received;

        return $this;
    }

    public function getRepair(): ?bool
    {
        return $this->repair;
    }

    public function setRepair(?bool $repair): self
    {
        $this->repair = $repair;

        return $this;
    }

    public function getRepairFinished(): ?bool
    {
        return $this->repair_finished;
    }

    public function setRepairFinished(?bool $repair_finished): self
    {
        $this->repair_finished = $repair_finished;

        return $this;
    }

    public function getSend(): ?bool
    {
        return $this->send;
    }

    public function setSend(?bool $send): self
    {
        $this->send = $send;

        return $this;
    }

    public function getFinished(): ?bool
    {
        return $this->finished;
    }

    public function setFinished(?bool $finished): self
    {
        $this->finished = $finished;

        return $this;
    }
}
