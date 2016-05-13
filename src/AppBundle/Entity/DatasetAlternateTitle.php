<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Alternate titles of a dataset
 *
 * @ORM\Entity
 * @ORM\Table(name="dataset_alternate_titles")
 */
class DatasetAlternateTitle {
  /**
   * @ORM\Column(type="integer",name="alternate_title_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $alt_title;

  /**
   * @ORM\ManyToOne(targetEntity="Dataset",inversedBy="dataset_alternate_titles")
   * @ORM\JoinColumn(name="datasets_dataset_uid",referencedColumnName="dataset_uid")
   */
  protected $datasets_dataset_uid;


  /**
   * get name for display
   *
   * @return string
   */
  public function getDisplayName() {
    return $this->alt_title;
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
     * Set alt_title
     *
     * @param string $altTitle
     * @return DatasetAlternateTitle
     */
    public function setAltTitle($altTitle)
    {
        $this->alt_title = $altTitle;

        return $this;
    }

    /**
     * Get alt_title
     *
     * @return string 
     */
    public function getAltTitle()
    {
        return $this->alt_title;
    }

    /**
     * Set datasets_dataset_uid
     *
     * @param \AppBundle\Entity\Dataset $datasetsDatasetUid
     * @return DatasetAlternateTitle
     */
    public function setDatasetsDatasetUid(\AppBundle\Entity\Dataset $datasetsDatasetUid = null)
    {
        $this->datasets_dataset_uid = $datasetsDatasetUid;

        return $this;
    }

    /**
     * Get datasets_dataset_uid
     *
     * @return \AppBundle\Entity\Dataset 
     */
    public function getDatasetsDatasetUid()
    {
        return $this->datasets_dataset_uid;
    }
}
