<?php
// src/AppBundle/Entity/DataType.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * Describe the type of data used in a dataset
 *
 * @ORM\Entity
 * @ORM\Table(name="data_types")
 * @UniqueEntity("data_type")
 */
class DataType {
  /**
   * @ORM\Column(type="integer",name="data_type_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=255, unique=true)
   */
  protected $data_type;

  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $slug;

  /**
   * @ORM\Column(type="string",length=256, nullable=true)
   */
  protected $authority;


  /**
   * @ORM\ManyToMany(targetEntity="Dataset", mappedBy="data_types")
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
    return $this->data_type;
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
     * Set data_type
     *
     * @param string $dataType
     * @return DataType
     */
    public function setDataType($dataType)
    {
        $this->data_type = $dataType;

        return $this;
    }

    /**
     * Get data_type
     *
     * @return string 
     */
    public function getDataType()
    {
        return $this->data_type;
    }

    /**
     * Set authority
     *
     * @param string $authority
     * @return DataType
     */
    public function setAuthority($authority)
    {
        $this->authority = $authority;

        return $this;
    }

    /**
     * Get authority
     *
     * @return string 
     */
    public function getAuthority()
    {
        return $this->authority;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return DataType
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
     * @return DataType
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
