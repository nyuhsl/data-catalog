<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * An entity describing the subject areas that are covered by our datasets
 *
 * @ORM\Entity
 * @ORM\Table(name="subject_domains")
 * @UniqueEntity("subject_domain")
 */
class SubjectDomain {
  /**
   * @ORM\Column(type="integer",name="subject_domain_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=255, unique=true)
   */
  protected $subject_domain;

  /**
   * @ORM\Column(type="string",length=255, nullable=true)
   */
  protected $mesh_code;

  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $slug;

  /**
   * @ORM\ManyToMany(targetEntity="Dataset", mappedBy="subject_domains")
   **/
  protected $datasets;

    public function __construct() {
      $this->datasets = new \Doctrine\Common\Collections\ArrayCollection();
    }

  /**
   * Get name for display
   *
   * @return string
   */
  public function getDisplayName() {
    return $this->subject_domain;
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
     * Set subject_domain
     *
     * @param string $subjectDomain
     * @return SubjectDomain
     */
    public function setSubjectDomain($subjectDomain)
    {
        $this->subject_domain = $subjectDomain;

        return $this;
    }

    /**
     * Get subject_domain
     *
     * @return string 
     */
    public function getSubjectDomain()
    {
        return $this->subject_domain;
    }

    /**
     * Set mesh_code
     *
     * @param string $meshCode
     * @return SubjectDomain
     */
    public function setMeshCode($meshCode)
    {
        $this->mesh_code = $meshCode;

        return $this;
    }

    /**
     * Get mesh_code
     *
     * @return string 
     */
    public function getMeshCode()
    {
        return $this->mesh_code;
    }


    /**
     * Set slug
     *
     * @param string $slug
     * @return SubjectDomain
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
     * Add datasets
     *
     * @param \AppBundle\Entity\Dataset $datasets
     * @return SubjectDomain
     */
    public function addDataset(\AppBundle\Entity\Dataset $datasets)
    {
        $this->datasets[] = $datasets;

        return $this;
    }

    /**
     * Remove datasets
     *
     * @param \AppBundle\Entity\Dataset $datasets
     */
    public function removeDataset(\AppBundle\Entity\Dataset $datasets)
    {
        $this->datasets->removeElement($datasets);
    }

    /**
     * Get datasets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDatasets()
    {
        return $this->datasets;
    }
}
