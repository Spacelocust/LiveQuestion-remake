<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 * @UniqueEntity(
 *     fields={"title"},
 *     message="Question déjà existante !",
 * )
 */
class Question
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="15", minMessage="votre question doit faire
     minimum 15 caractères")
     * @Assert\Length(max="255", minMessage="votre question doit faire
    maximum 255 caractères")
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $visibility;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Answer::class, mappedBy="question")
     */
    private $answers;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Concern::class, mappedBy="question")
     */
    private $concerns;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="question")
     */
    private $likes;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->concerns = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    public function setVisibility(string $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSlug():string
    {
        return (new AsciiSlugger())->slug($this->title);
    }

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Concern[]
     */
    public function getConcerns(): Collection
    {
        return $this->concerns;
    }

    public function addConcern(Concern $concern): self
    {
        if (!$this->concerns->contains($concern)) {
            $this->concerns[] = $concern;
            $concern->setQuestion($this);
        }

        return $this;
    }

    public function removeConcern(Concern $concern): self
    {
        if ($this->concerns->removeElement($concern)) {
            // set the owning side to null (unless already changed)
            if ($concern->getQuestion() === $this) {
                $concern->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Like[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setQuestion($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getQuestion() === $this) {
                $like->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * Permet de savoir si cet question à été aimé par l'utilisateur
     *
     * @param User $user
     * @return bool
     */
    public function isLikedByUser(User $user):bool
    {
        foreach ($this->likes as $like){
            if($like->getUser() === $user) return true;
        }
        return false;
    }
}
