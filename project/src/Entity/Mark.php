<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MarkRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\EmptyResponse;

/**
 * @ORM\Entity(repositoryClass=MarkRepository::class)
 * @UniqueEntity(
 *     fields={"subject", "student"},
 *     errorPath="student",
 *     message="this student is already reading that subject",
 *     groups={"create:mark"}
 * )
 * @ApiResource(
 *    collectionOperations={
 *          "post"={
 *              "denormalization_context"={"groups"={"create:mark"}},
 *              "validation_groups"={"create:mark"}
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "controller"=EmptyResponse::class,
 *              "read"=false,
 *              "deserialize"=false
 *          }
 *     }
 * )
 */
class Mark
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read:student", "create:mark"})
     * @Assert\NotBlank(
     *     message="This value should not be empty.",
     *     groups={"create:mark"}
     * )
     */
    private ?string $subject = null;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read:student", "create:mark"})
     * @Assert\NotBlank(
     *     message="This value should not be empty.",
     *     groups={"create:mark"}
     * )
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}.",
     *     groups={"create:mark"}
     * )
     * @Assert\Range(
     *     min="0",
     *     max="20",
     *     notInRangeMessage="must be between 0 and 20",
     *     groups={"create:mark"}
     * )
     */
    private ?int $value = null;

    /**
     * @ORM\ManyToOne(targetEntity=Student::class, inversedBy="marks")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"create:mark"})
     * @Assert\NotBlank(
     *     message="This value should not be empty.",
     *     groups={"create:mark"}
     * )
     */
    private ?Student $student = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }
}
