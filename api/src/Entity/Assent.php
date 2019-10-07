<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\AssentRepository")
 */
class Assent
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ApiProperty(
     * 	   identifier=true,
     *     attributes={
     *         "swagger_context"={
     *         	   "description" = "The UUID identifier of this object",
     *             "type"="string",
     *             "format"="uuid",
     *             "example"="e2984465-190a-4562-829e-a8cca81aa35d"
     *         }
     *     }
     * )
     *
     * @Groups({"read"})
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;
    
    /**
     * @var string $token a secret token used to validate the assent
     *
     * @Groups({"read"})
     * @ORM\Column(type="string", length=255)
     */
    private $token;
    
    /**
     * @var string $name The name of this assend is displayed as a title to end users and should make clear what they arre assending to
     * 
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    
    /**
     * @var string $description The description of this assend is displayed to end users as aditional information and should make clear what they arre assending to
     * 
     * @Groups({"read","write"})
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var string $request The request that this assent applies to
     * 
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
	 * @ApiFilter(SearchFilter::class, strategy="exact")
     */
    private $request;
    
    /**
     * @var string $property The property of a request that this assent applies to e.g. parner in meldingvoorgenomenhuwelijk
     * 
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
	 * @ApiFilter(SearchFilter::class, strategy="exact")
     */
    private $property;

    /**
     * @var string $process The procces that this assent originated from
     * 
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
	 * @ApiFilter(SearchFilter::class, strategy="exact")
     */
    private $process;
    
    /**
     * @var string $contact The contact that this assent applies to 
     * 
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
	 * @ApiFilter(SearchFilter::class, strategy="exact")
     */
    private $contact;
    
    /**
     * @var string $person The person that this assent applies to 
     * 
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
	 * @ApiFilter(SearchFilter::class, strategy="exact")
     */
    private $person;

    /**
     * @var string $status The status of this assent e.g. requested, accepted, denied
     * 
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
     */
    private $requester;

    public function getId(): ?int
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
