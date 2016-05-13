<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Related resources such as code on Github 
 *
 * @ORM\Entity
 * @ORM\Table(name="other_resources")
 */
class OtherResource {
  /**
   * @ORM\Column(type="integer",name="other_resource_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=256)
   */
  protected $resource_name;

  /**
   * @ORM\Column(type="string",length=1028,nullable=true)
   */
  protected $resource_description;

  /**
   * @ORM\Column(type="string",length=1028)
   */
  protected $resource_url;

  /**
   * @ORM\ManyToOne(targetEntity="Dataset",inversedBy="other_resources")
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
    return $this->resource_name;
  }

    /**
     * Set datasets_dataset_uid
     *
     * @param \AppBundle\Entity\Dataset $datasetsDatasetUid
     * @return OtherResource
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
     * Set resource_name
     *
     * @param string $resourceName
     * @return OtherResource
     */
    public function setResourceName($resourceName)
    {
        $this->resource_name = $resourceName;

        return $this;
    }

    /**
     * Get resource_name
     *
     * @return string 
     */
    public function getResourceName()
    {
        return $this->resource_name;
    }

    /**
     * Set resource_description
     *
     * @param string $resourceDescription
     * @return OtherResource
     */
    public function setResourceDescription($resourceDescription)
    {
        $this->resource_description = $resourceDescription;

        return $this;
    }

    /**
     * Get resource_description
     *
     * @return string 
     */
    public function getResourceDescription()
    {
        return $this->resource_description;
    }

    /**
     * Set resource_url
     *
     * @param string $resourceUrl
     * @return OtherResource
     */
    public function setResourceUrl($resourceUrl)
    {
        $this->resource_url = $resourceUrl;

        return $this;
    }

    /**
     * Get resource_url
     *
     * @return string 
     */
    public function getResourceUrl()
    {
        return $this->resource_url;
    }
}
