<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Describe a person's relationship to a dataset
 *
 * @ORM\Entity
 * @ORM\Table(name="person_associations")
 */
class PersonAssociation {
  /** 
   * @ORM\Column(type="integer",name="person_association_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=128)
   */
  protected $role;

  /**
   * @ORM\ManyToOne(targetEntity="Person", inversedBy="dataset_associations")
   * @ORM\JoinColumn(name="person_id",referencedColumnName="person_id")
   */
  protected $person;
  
  /**
   * @ORM\ManyToOne(targetEntity="Dataset", inversedBy="person_associations")
   * @ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")
   */
  protected $dataset_uid;


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
     * Set role
     *
     * @param string $role
     * @return PersonAssociation
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }


    /**
     * Set person
     *
     * @param \AppBundle\Entity\Person $person
     * @return PersonAssociation
     */
    public function setPerson(\AppBundle\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \AppBundle\Entity\Person 
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set dataset_uid
     *
     * @param \AppBundle\Entity\Dataset $datasetUid
     * @return PersonAssociation
     */
    public function setDatasetUid(\AppBundle\Entity\Dataset $datasetUid = null)
    {
        $this->dataset_uid = $datasetUid;

        return $this;
    }

    /**
     * Get dataset_uid
     *
     * @return \AppBundle\Entity\Dataset 
     */
    public function getDatasetUid()
    {
        return $this->dataset_uid;
    }
}
