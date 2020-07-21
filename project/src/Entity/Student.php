<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\StudentMarkAverage;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 * @UniqueEntity(
 *     fields={"birthDate", "firstName", "lastName"},
 *     errorPath="firstName",
 *     message="this name is already in use on that birthDate",
 *     groups={"create:student"}
 * )
 * @ApiResource(
 *     attributes={
 *          "order"={"firstName": "ASC"}
 *     },
 *     paginationItemsPerPage=3,
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"read:student"}}
 *          },
 *          "post"={
 *              "denormalization_context"={"groups"={"create:student"}},
 *              "validation_groups"={"create:student"}
 *          }
 *     },
 *     itemOperations={
 *          "put"={
 *              "denormalization_context"={"groups"={"update:student"}},
 *          },
 *          "delete",
 *          "get_mark_avg"={
 *              "method"="GET",
 *              "path"="/students/{id}/avg",
 *              "controller"=StudentMarkAverage::class,
 *              "normalization_context"={"groups"={"read:student"}}
 *          }
 *     }
 * )
 */
class Student
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read:student"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:student", "create:student", "update:student"})
     * @Assert\NotBlank(message="This value should not be blank.", groups={"create:student"})
     */
    private ?string $firstName = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:student", "create:student", "update:student"})
     * @Assert\NotBlank(message="This value should not be blank.", groups={"create:student"})
     */
    private ?string $lastName = null;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read:student", "create:student", "update:student"})
     * @Assert\NotNull(message="birthDate is required.", groups={"create:student"})
     * @Assert\Type(type="\DateTimeInterface", message="incorrect format", groups={"create:student"})
     */
    private ?\DateTimeInterface $birthDate = null;

    /**
     * @ORM\OneToMany(targetEntity=Mark::class, mappedBy="student", orphanRemoval=true)
     * @Groups({"read:student"})
     */
    private Collection $marks;

    /**
     * @ApiProperty()
     */
    public int $avg;

    public function __construct()
    {
        $this->marks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getMarks(): Collection
    {
        return $this->marks;
    }

    public function addMark(Mark $mark): self
    {
        if (!$this->marks->contains($mark)) {
            $this->marks[] = $mark;
            $mark->setStudent($this);
        }

        return $this;
    }

    public function removeMark(Mark $mark): self
    {
        if ($this->marks->contains($mark)) {
            $this->marks->removeElement($mark);
            // set the owning side to null (unless already changed)
            if ($mark->getStudent() === $this) {
                $mark->setStudent(null);
            }
        }

        return $this;
    }
}
