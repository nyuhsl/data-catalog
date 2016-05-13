<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * The location of the dataset on the web, or a person who can provide access
 *
 * @ORM\Entity
 * @ORM\Table(name="data_locations")
 */
class DataLocation {
  /**
   * @ORM\Column(type="integer",name="location_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $data_location;

  /**
   * @ORM\Column(type="string",length=1028,nullable=true)
   */
  protected $location_content;

  /**
   * @ORM\Column(type="string",length=1028)
   */
  protected $data_access_url;

  /**
   * @ORM\ManyToOne(targetEntity="Dataset",inversedBy="data_locations")
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

    /**
     * Set data_location
     *
     * @param string $dataLocation
     * @return DataLocation
     */
    public function setDataLocation($dataLocation)
    {
        $this->data_location = $dataLocation;

        return $this;
    }

    /**
     * Get data_location
     *
     * @return string 
     */
    public function getDataLocation()
    {
        return $this->data_location;
    }

    /**
     * Set location_content
     *
     * @param string $locationContent
     * @return DataLocation
     */
    public function setLocationContent($locationContent)
    {
        $this->location_content = $locationContent;

        return $this;
    }

    /**
     * Get location_content
     *
     * @return string 
     */
    public function getLocationContent()
    {
        return $this->location_content;
    }
}
