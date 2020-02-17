<?php

namespace App\Entity;

use App\Service\DurationUnit;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Votre nom est indispensable")
     * @ORM\Column(type="string", length=300)
     */
    private $name;

    /**
     * @Assert\Type(type="integer", message="Vous devez indiquer un nombre")
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startingDateTime;

    /**
     * @Assert\Type(type="string")
     */
    private $startingDate;

    /**
     * @Assert\Type(type="string")
     */
    private $startingTime;

    /**
     * @ORM\Column(type="datetime")
     */
    private $inscriptionDeadLine;


    /**
     * @Assert\Type(type="string")
     */
    private $deadLineDate;

    /**
     * @Assert\Type(type="string")
     */
    private $deadLineTime;


    /**
     * @Assert\Type(type="integer", message="Vous devez indiquer un nombre")
     * @ORM\Column(type="integer")
     */
    private $nbMaxRegistration;

    /**
     * @Assert\Length(max=500, maxMessage = "le champs ne doit pas contenir plus de {{ limit }} caractères")
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $infos;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Member", inversedBy="organizedEvents", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organizer;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Member", inversedBy="registeredEvents")
     */
    private $registeredMembers;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Site", inversedBy="events", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $site;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", inversedBy="events", cascade={"persist"}, fetch="EAGER")
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Status", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    //Format of datetime
    private const FORMAT_DATETIME = 'Y-m-d H:i:s';

    /**
     * @Assert\Type(type="string")
     */
    private $durationUnit;

    /**
     * @Assert\Callback
     */
    public function validateStartingDate(ExecutionContextInterface $context, $payload)
    {
        $startingDateTime = $this->buildDateTimeFromStringDateStringTime($this->getStartingDate(), $this->getStartingTime());
        if( $startingDateTime < new \DateTime('now')){
            $context->buildViolation('L\'évènement doit débuter à une date ultérieure au présent')
                ->atPath('startingDate')
                ->addViolation();
        }
    }


    /**
     * @Assert\Callback
     */
    public function validateDeadlineDate(ExecutionContextInterface $context, $payload)
    {

        //TODO : distinguer entre la creation et la mise a jour de l'event. Si $inscriptionDeadLine et $startingDateTime sont null alors
        //TODO : on est en creation, sinon en update

        $inscriptionDeadLine = $this->buildDateTimeFromStringDateStringTime($this->getDeadLineDate(), $this->getDeadLineTime());
        $durationInHours = DurationUnit::convertDurationIntoHours($this->getDuration(), $this->getDurationUnit());

        if( is_null( $startingDateTime ) ){


        }

        $startingDateTime = $this->buildDateTimeFromStringDateStringTime($this->getStartingDate(), $this->getStartingTime());
        $endingDateTime = clone $startingDateTime;
        $endingDateTime->add( new\DateInterval('PT'.$durationInHours.'H') );

        if ($inscriptionDeadLine > $endingDateTime) {
            $context->buildViolation(
                'La date limite d\'inscription doit arriver avant la clôture de l\'événement '
            )
                ->atPath('deadLineDate')
                ->addViolation();
        }
    }

    public function buildDateTimeFromStringDateStringTime($date, $time){
        return \DateTime::createFromFormat(Event::FORMAT_DATETIME, "$date $time");
    }

    /**
     * @return mixed
     */
    public function getDeadLineDate()
    {
        return $this->deadLineDate;
    }

    /**
     * @param mixed $deadLineDate
     */
    public function setDeadLineDate($deadLineDate): void
    {
        $this->deadLineDate = $deadLineDate;
    }

    /**
     * @return mixed
     */
    public function getDeadLineTime()
    {
        return $this->deadLineTime;
    }

    /**
     * @param mixed $deadLineTime
     */
    public function setDeadLineTime($deadLineTime): void
    {
        $this->deadLineTime = $deadLineTime;
    }

    /**
     * @return mixed
     */
    public function getDurationUnit()
    {
        return $this->durationUnit;
    }

    /**
     * @param mixed $durationUnit
     */
    public function setDurationUnit($durationUnit): void
    {
        $this->durationUnit = $durationUnit;
    }

    public function __construct()
    {
        $this->registeredMembers = new ArrayCollection();
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

    public function getStartingDateTime(): ?\DateTimeInterface
    {
        return $this->startingDateTime;
    }

    public function setStartingDateTime(\DateTimeInterface $startingDateTime): self
    {
        $this->startingDateTime = $startingDateTime;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getInscriptionDeadLine(): ?\DateTimeInterface
    {
        return $this->inscriptionDeadLine;
    }

    public function setInscriptionDeadLine(\DateTimeInterface $inscriptionDeadLine): self
    {
        $this->inscriptionDeadLine = $inscriptionDeadLine;

        return $this;
    }

    public function getNbMaxRegistration(): ?int
    {
        return $this->nbMaxRegistration;
    }

    public function setNbMaxRegistration(int $nbMaxRegistration): self
    {
        $this->nbMaxRegistration = $nbMaxRegistration;

        return $this;
    }

    public function getInfos(): ?string
    {
        return $this->infos;
    }

    public function setInfos(?string $infos): self
    {
        $this->infos = $infos;

        return $this;
    }

    public function getOrganizer(): ?member
    {
        return $this->organizer;
    }

    public function setOrganizer(?member $organizer): self
    {
        $this->organizer = $organizer;

        return $this;
    }

    /**
     * @return Collection|member[]
     */
    public function getRegistered(): Collection
    {
        return $this->registeredMembers;
    }

    public function addRegistered(member $registeredMember): self
    {
        if (!$this->registeredMembers->contains($registeredMember)) {
            $this->registeredMembers[] = $registeredMember;
        }

        return $this;
    }

    public function removeRegistered(member $registeredMember): self
    {
        if ($this->registeredMembers->contains($registeredMember)) {
            $this->registeredMembers->removeElement($registeredMember);
        }

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

    public function getLocation(): ?location
    {
        return $this->location;
    }

    public function setLocation(?location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getStatus(): ?status
    {
        return $this->status;
    }

    public function setStatus(?status $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getRegisteredMembers(): Collection
    {
        return $this->registeredMembers;
    }

    /**
     * @return mixed
     */
    public function getStartingDate()
    {
        return $this->startingDate;
    }

    /**
     * @param mixed $startingDate
     */
    public function setStartingDate($startingDate): void
    {
        $this->startingDate = $startingDate;
    }

    /**
     * @return mixed
     */
    public function getStartingTime()
    {
        return $this->startingTime;
    }

    /**
     * @param mixed $startingTime
     */
    public function setStartingTime($startingTime): void
    {
        $this->startingTime = $startingTime;
    }


}
