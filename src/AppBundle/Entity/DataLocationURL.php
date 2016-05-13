<?php
// src/AppBundle/Entity/DataLocationURL.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="data_location_urls")
 */
class DataLocationURL {
  /**
   * @ORM\Column(type="integer",name="location_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=1028)
   */
  protected $data_access_url;

  /**
   * @ORM\ManyToOne(targetEntity="Dataset",inversedBy="data_location_urls")
   * @ORM\JoinColumn(name="datasets_dataset_uid",referencedColumnName="dataset_uid")
   */
  protected $datasets_dataset_uid;


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
   * get name for display
   *
   * @return string
   */
  public function getDisplayName() {
    return $this->data_access_url;
  }


    /**
     * Set data_access_url
     *
     * @param string $dataAccessUrl
     * @return DataLocationURL
     */
    public function setDataAccessUrl($dataAccessUrl)
    {
        $this->data_access_url = $dataAccessUrl;

        return $this;
    }

    /**
     * Get data_access_url
     *
     * @return string 
     */
    public function getDataAccessUrl()
    {
        return $this->data_access_url;
    }

    /**
     * Set datasets_dataset_uid
     *
     * @param \AppBundle\Entity\Dataset $datasetsDatasetUid
     * @return DataLocationURL
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
