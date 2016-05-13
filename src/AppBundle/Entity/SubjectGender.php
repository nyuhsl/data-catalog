<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * An entity describing gender
 *
 * @ORM\Entity
 * @ORM\Table(name="subject_genders")
 */
class SubjectGender {
  /**
   * @ORM\Column(type="integer",name="gender_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=128)
   */
  protected $subject_gender;

  /**
   * @ORM\Column(type="string",length=128)
   */
  protected $slug;
    
  /**
   * @ORM\ManyToMany(targetEntity="Dataset", mappedBy="subject_genders")
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
    return $this->subject_gender;
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
     * Set subject_gender
     *
     * @param string $subjectGender
     * @return SubjectGender
     */
    public function setSubjectGender($subjectGender)
    {
        $this->subject_gender = $subjectGender;

        return $this;
    }

    /**
     * Get subject_gender
     *
     * @return string 
     */
    public function getSubjectGender()
    {
        return $this->subject_gender;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return SubjectGender
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
     * @return SubjectGender
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
