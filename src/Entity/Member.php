<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MemberRepository")
 * @UniqueEntity(fields={"mail"}, message="Cet email est déjà lié à un compte")
 * @UniqueEntity(fields={"pseudo"}, message="Le pseudo existe déjà")
 */
class Member implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le prénom est obligatoire")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom est obligatoire")
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank
     */
    private $mail;

    //Le champ active est false tant que l'utilisateur ne s'est pas connecté une première fois
    ///après qu'un admin lui ait crée son compte
    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Site", inversedBy="members")
     * @ORM\JoinColumn(nullable=true)
     */
    private $site;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="organizer", orphanRemoval=true)
     */
    private $organizedEvents;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Event", mappedBy="registeredMembers")
     */
    private $registeredEvents;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $password;

//    /**
//     * @SecurityAssert\UserPassword(
//     *     message = "Le mot de passe n'est pas correct"
//     * )
//     */
//    protected $oldPassword;

    /**
     * @ORM\Column(type="string", length=64, nullable=true, unique=true)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbConnection;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAdmin;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastConnectionDateTime;


    public function __construct()
    {
        $this->organizedEvents = new ArrayCollection();
        $this->registeredEvents = new ArrayCollection();
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getSite(): ?site
    {
        return $this->site;
    }

    public function setSite(?site $site): self
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getOrganizedEvents(): Collection
    {
        return $this->organizedEvents;
    }

    public function addOrganizedEvent(Event $organizedEvent): self
    {
        if (!$this->organizedEvents->contains($organizedEvent)) {
            $this->organizedEvents[] = $organizedEvent;
            $organizedEvent->setOrganizer($this);
        }

        return $this;
    }

    public function removeOrganizedEvent(Event $organizedEvent): self
    {
        if ($this->organizedEvents->contains($organizedEvent)) {
            $this->organizedEvents->removeElement($organizedEvent);
            // set the owning side to null (unless already changed)
            if ($organizedEvent->getOrganizer() === $this) {
                $organizedEvent->setOrganizer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getRegisteredEvents(): Collection
    {
        return $this->registeredEvents;
    }

    public function addRegisteredEvent(Event $registeredEvent): self
    {
        if (!$this->registeredEvents->contains($registeredEvent)) {
            $this->registeredEvents[] = $registeredEvent;
            $registeredEvent->addRegistered($this);
        }

        return $this;
    }

    public function removeRegisteredEvent(Event $registeredEvent): self
    {
        if ($this->registeredEvents->contains($registeredEvent)) {
            $this->registeredEvents->removeElement($registeredEvent);
            $registeredEvent->removeRegistered($this);
        }

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
     * @return mixed
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @param mixed $oldPassword
     */
    public function setOldPassword($oldPassword): void
    {
        $this->oldPassword = $oldPassword;
    }


    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        // TODO: Implement getRoles() method.

        if(is_null($this->isAdmin) || $this->isAdmin === false){
            return ['ROLE_USER'];
        }
        else{
            return ['ROLE_ADMIN'];
        }

    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        // TODO: Implement getUsername() method
        return $this->getMail();
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }


    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getNbConnection(): ?int
    {
        return $this->nbConnection;
    }

    public function setNbConnection(?int $nbConnection): self
    {
        $this->nbConnection = $nbConnection;

        return $this;
    }

    public function getIsAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(?bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function getLastConnectionDateTime(): ?\DateTimeInterface
    {
        return $this->lastConnectionDateTime;
    }

    public function setLastConnectionDateTime(?\DateTimeInterface $lastConnectionDateTime): self
    {
        $this->lastConnectionDateTime = $lastConnectionDateTime;

        return $this;
    }
}
