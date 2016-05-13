<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * Entity describing the measurement standards used in a dataset
 *
 * @ORM\Entity
 * @ORM\Table(name="measurement_standards")
 * @UniqueEntity("measurement_standard_name")
 */
class MeasurementStandard {
  /**
   * @ORM\Column(type="integer",name="standard_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=255, unique=true)
   */
  protected $measurement_standard_name;

  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $slug;

  /**
   * @ORM\Column(type="string", length=256)
   */
  protected $measurement_standard_authority;

  /**
   * @ORM\ManyToMany(targetEntity="Dataset", mappedBy="measurement_standards")
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
    return $this->measurement_standard_name;
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
     * Set measurement_standard_name
     *
     * @param string $measurementStandardName
     * @return MeasurementStandard
     */
    public function setMeasurementStandardName($measurementStandardName)
    {
        $this->measurement_standard_name = $measurementStandardName;

        return $this;
    }

    /**
     * Get measurement_standard_name
     *
     * @return string 
     */
    public function getMeasurementStandardName()
    {
        return $this->measurement_standard_name;
    }

    /**
     * Set measurement_standard_authority
     *
     * @param string $measurementStandardAuthority
     * @return MeasurementStandard
     */
    public function setMeasurementStandardAuthority($measurementStandardAuthority)
    {
        $this->measurement_standard_authority = $measurementStandardAuthority;

        return $this;
    }

    /**
     * Get measurement_standard_authority
     *
     * @return string 
     */
    public function getMeasurementStandardAuthority()
    {
        return $this->measurement_standard_authority;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return MeasurementStandard
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
     * @return MeasurementStandard
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
