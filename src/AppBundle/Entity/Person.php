<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * An entity describing a person
 *
 * @ORM\Entity
 * @ORM\Table(name="person")
 * @UniqueEntity("kid")
 * @UniqueEntity("slug")
 */
class Person {
  /**
   * @ORM\Column(type="integer",name="person_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=128)
   */
  protected $full_name;

  /**
   * @ORM\Column(type="string",length=128, unique=true)
   */
  protected $slug;


  /**
   * @ORM\Column(type="string",length=128,nullable=true)
   */
  protected $last_name;

  /**
   * @ORM\Column(type="string",length=128,nullable=true)
   */
  protected $first_name;


  /**
   * @ORM\Column(type="string",length=16, nullable=true, unique=true)
   */
  protected $kid;


  /**
   * @ORM\Column(type="string",length=128, nullable=true, unique=true)
   */
  protected $orcid_id;


  /**
   * @ORM\Column(type="string",length=1028, nullable=true)
   */
  protected $bio_url;




  /**
   * Get name for display
   *
   * @return string
   */
  public function getDisplayName() {
    return $this->full_name;
  }

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set full_name
     *
     * @param string $fullName
     * @return Person
     */
    public function setFullName($fullName)
    {
        $this->full_name = $fullName;

        return $this;
    }

    /**
     * Get full_name
     *
     * @return string 
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * Set last_name
     *
     * @param string $lastName
     * @return Person
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get last_name
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set first_name
     *
     * @param string $firstName
     * @return Person
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get first_name
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set kid
     *
     * @param string $kid
     * @return Person
     */
    public function setKid($kid)
    {
        $this->kid = $kid;

        return $this;
    }

    /**
     * Get kid
     *
     * @return string 
     */
    public function getKid()
    {
        return $this->kid;
    }

    /**
     * Set bio_url
     *
     * @param string $bioUrl
     * @return Person
     */
    public function setBioUrl($bioUrl)
    {
        $this->bio_url = $bioUrl;

        return $this;
    }

    /**
     * Get bio_url
     *
     * @return string 
     */
    public function getBioUrl()
    {
        return $this->bio_url;
    }


    /**
     * Set slug
     *
     * @param string $slug
     * @return Person
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set orcid_id
     *
     * @param string $orcidId
     * @return Person
     */
    public function setOrcidId($orcidId)
    {
        $this->orcid_id = $orcidId;

        return $this;
    }

    /**
     * Get orcid_id
     *
     * @return string 
     */
    public function getOrcidId()
    {
        return $this->orcid_id;
    }
}
