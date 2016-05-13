<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * The equipment used to produce the data
 *
 * @ORM\Entity
 * @ORM\Table(name="related_equipment")
 * @UniqueEntity("related_equipment")
 */
class RelatedEquipment {
  /**
   * @ORM\Column(type="integer",name="related_equipment_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=255, unique=true)
   */
  protected $related_equipment;

  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $slug;


  /**
   * @ORM\ManyToMany(targetEntity="Dataset", mappedBy="related_equipment")
   **/
  protected $datasets;

  /**
   * get name for display
   *
   * @return string
   */
  public function getDisplayName() {
    return $this->related_equipment;
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
     * Set related_equipment
     *
     * @param string $related_equipment
     * @return RelatedEquipment
     */
    public function setRelatedEquipment($related_equipment)
    {
        $this->related_equipment = $related_equipment;

        return $this;
    }

    /**
     * Get related_equipment
     *
     * @return string 
     */
    public function getRelatedEquipment()
    {
        return $this->related_equipment;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return RelatedEquipment
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
 
    public function __construct() {
      $this->datasets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add datasets
     *
     * @param \AppBundle\Entity\Dataset $datasets
     * @return RelatedEquipment
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
