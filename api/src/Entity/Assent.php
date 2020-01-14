<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * An assent registers if assents are given for mutations that require one.
 *
 * @author Ruben van der Linde <ruben@conduction.nl>
 * @license EUPL <https://github.com/ConductionNL/contactcatalogus/blob/master/LICENSE.md>
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\AssentRepository")
 */
class Assent
{
    /**
     * @var UuidInterface
     *
     * @Groups({"read"})
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var string a secret token used to validate the assent
     *
     * @Groups({"read"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *     max = 255
     * )
     */
    private $token;

    /**
     * @var string The name of this assend is displayed as a title to end users and should make clear what they arre assending to
     *
     * @example My Assent
     *
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *     max = 255
     * )
     */
    private $name;

    /**
     * @var string The description of this assend is displayed to end users as aditional information and should make clear what they arre assending to
     *
     * @example This is the best assent ever
     *
     * @Groups({"read","write"})
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var string The request that this assent applies to
     *
     * @example https://www.example.org/requests/1
     *
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ApiFilter(SearchFilter::class, strategy="exact")
     * @Assert\Length(
     *     max = 255
     * )
     */
    private $request;

    /**
     * @var string The property of a request that this assent applies to e.g. parner in meldingvoorgenomenhuwelijk
     *
     * @example https://www.example.org/people/1
     *
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ApiFilter(SearchFilter::class, strategy="exact")
     * @Assert\Length(
     *     max = 255
     * )
     */
    private $property;

    /**
     * @var string The process that this assent originated from
     *
     * @example https://www.example.org/processes/1
     *
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ApiFilter(SearchFilter::class, strategy="exact")
     * @Assert\Length(
     *     max =255
     * )
     */
    private $process;

    /**
     * @var string The contact that this assent applies to
     *
     * @example https://www.example.org/contacts/1
     *
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ApiFilter(SearchFilter::class, strategy="exact")
     * @Assert\Length(
     *     max = 255
     * )
     */
    private $contact;

    /**
     * @var string The person that this assent applies to
     *
     * @example https://www.example.org/people/2
     *
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ApiFilter(SearchFilter::class, strategy="exact")
     * @Assert\Length(
     *     max = 255
     * )
     */
    private $person;

    /**
     * @var string The status of this assent e.g. requested, granted, declined
     *
     * @example requested
     *
     * @Assert\Choice({"requested", "granted", "submitted", "declined"})
     * @Assert\Length(
     *      max = 255
     * )
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @var string requester The organisation (RSIN) or person (BSN) that is responsible for making this assent
     *
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255)
     * @ApiFilter(SearchFilter::class, strategy="exact")
     * @Assert\NotBlank
     * @Assert\Length (
     *     max = 255
     * )
     */
    private $requester;

    public function getId()
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRequest(): ?string
    {
        return $this->request;
    }

    public function setRequest(?string $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function getProcess(): ?string
    {
        return $this->process;
    }

    public function setProcess(?string $process): self
    {
        $this->process = $process;

        return $this;
    }

    public function getProperty(): ?string
    {
        return $this->property;
    }

    public function setProperty(?string $property): self
    {
        $this->property = $property;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getPerson(): ?string
    {
        return $this->person;
    }

    public function setPerson(?string $person): self
    {
        $this->person = $person;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getRequester(): ?string
    {
        return $this->requester;
    }

    public function setRequester(string $requester): self
    {
        $this->requester = $requester;

        return $this;
    }
}
