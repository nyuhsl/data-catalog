<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Entity describing grants and awards associated with a dataset
 *
 * @ORM\Entity
 * @ORM\Table(name="awards")
 * @UniqueEntity("award")
 */
class Award {
  /**
   * @ORM\Column(type="integer",name="award_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;


  /**
   * @ORM\Column(type="string",length=255, unique=true)
   */
  protected $award;

  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $slug;

  /**
   * @ORM\Column(type="string",length=512, nullable=true)
   */
  protected $award_funder;


  /**
   * @ORM\Column(type="string",length=1028, nullable=true)
   */
  protected $award_url;
    
  /**
   * @ORM\Column(type="string",length=64, nullable=true)
   */
  protected $funder_type;

  /**
   * @ORM\ManyToMany(targetEntity="Dataset", mappedBy="awards")
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
     return $this->award;
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
     * Set award
     *
     * @param string $award
     * @return Award
     */
    public function setAward($award)
    {
        $this->award = $award;

        return $this;
    }

    /**
     * Get award
     *
     * @return string 
     */
    public function getAward()
    {
        return $this->award;
    }

    /**
     * Set award_funder
     *
     * @param string $awardFunder
     * @return Award
     */
    public function setAwardFunder($awardFunder)
    {
        $this->award_funder = $awardFunder;

        return $this;
    }

    /**
     * Get award_funder
     *
     * @return string 
     */
    public function getAwardFunder()
    {
        return $this->award_funder;
    }

    /**
     * Set award_url
     *
     * @param string $awardUrl
     * @return Award
     */
    public function setAwardUrl($awardUrl)
    {
        $this->award_url = $awardUrl;

        return $this;
    }

    /**
     * Get award_url
     *
     * @return string 
     */
    public function getAwardUrl()
    {
        return $this->award_url;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Award
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
     * Set funder_type
     *
     * @param string $funderType
     * @return Award
     */
    public function setFunderType($funderType)
    {
        $this->funder_type = $funderType;

        return $this;
    }

    /**
     * Get funder_type
     *
     * @return string 
     */
    public function getFunderType()
    {
        return $this->funder_type;
    }

    /**
     * Add datasets
     *
     * @param \AppBundle\Entity\Dataset $datasets
     * @return Award
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
