<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *     fields={"email"},
 *     message="l'email indiquer est déjà utiliser",
 *     fields={"name"},
 *     message="nom indiquer est déjà utiliser",
 * )
 */
class User implements UserInterface,\Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="votre mot de passe doit faire
     minimum 8 caractères")
     */
    private $password;

    /**
     * @var string
     * @Assert\EqualTo(propertyPath="password", message="veuillez saisir le
     même mot de passe")
     */
    private string $passwordConfirm;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\ManyToOne(targetEntity=Role::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="user")
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity=Answer::class, mappedBy="user")
     */
    private $answers;

    /**
     * @ORM\OneToMany(targetEntity=Link::class, mappedBy="receiver")
     */
    private $link_receiver;

    /**
     * @ORM\OneToMany(targetEntity=Link::class, mappedBy="sender")
     */
    private $link_sender;

    /**
     * @ORM\OneToMany(targetEntity=Concern::class, mappedBy="user")
     */
    private $concerns;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="user")
     */
    private $likes;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->answers = new ArrayCollection();
        $this->links = new ArrayCollection();
        $this->concerns = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPasswordConfirm(): string
    {
        return $this->passwordConfirm;
    }

    /**
     * @param string $passwordConfirm
     */
    public function setPasswordConfirm(string $passwordConfirm): void
    {
        $this->passwordConfirm = $passwordConfirm;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setUser($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getUser() === $this) {
                $question->setUser(null);
            }
        }

        return $this;
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
            $answer->setUser($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getUser() === $this) {
                $answer->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Link[]
     */
    public function getLinkReceiver(): Collection
    {
        return $this->link_receiver;
    }

    public function addLinkReceiver(Link $link): self
    {
        if (!$this->link_receiver->contains($link)) {
            $this->link_receiver[] = $link;
            $link->setReceiver($this);
        }

        return $this;
    }

    public function removeLinkReceiver(Link $link): self
    {
        if ($this->link_receiver->removeElement($link)) {
            // set the owning side to null (unless already changed)
            if ($link->getReceiver() === $this) {
                $link->setReceiver(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection|Link[]
     */
    public function getLinkSender(): Collection
    {
        return $this->link_sender;
    }

    public function addLinkSender(Link $link): self
    {
        if (!$this->link_sender->contains($link)) {
            $this->link_sender[] = $link;
            $link->setSender($this);
        }

        return $this;
    }

    public function removeLinkSender(Link $link): self
    {
        if ($this->link_sender->removeElement($link)) {
            // set the owning side to null (unless already changed)
            if ($link->getSender() === $this) {
                $link->setSender(null);
            }
        }

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
            $concern->setUser($this);
        }

        return $this;
    }

    public function removeConcern(Concern $concern): self
    {
        if ($this->concerns->removeElement($concern)) {
            // set the owning side to null (unless already changed)
            if ($concern->getUser() === $this) {
                $concern->setUser(null);
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
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getRoles()
    {
        return [$this->getRole()->getLibelle()];
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->name;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return string
     */
    public function serialize()
    {
       return serialize([
          $this->id,
          $this->name,
          $this->password
       ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
       list(
           $this->id,
           $this->name,
           $this->password
           ) = unserialize($serialized,['allowed_classes' => false]);
    }
}
