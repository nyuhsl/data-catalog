<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JsonSerializable;



/**
 * The dataset itself
 *
 *   This file is part of the Data Catalog project.
 *   Copyright (C) 2016 NYU Health Sciences Library
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @ORM\Entity(repositoryClass="AppBundle\Entity\DatasetRepository")
 * @ORM\Table(name="datasets")
 * @UniqueEntity("title")
 */
class Dataset implements JsonSerializable {
  /**
   * @Assert\NotBlank()
   * @ORM\Id
   * @ORM\Column(type="integer", unique=true)
   */
  protected $dataset_uid;

  /**
   * @ORM\Column(type="string",length=16, options={"default"="Internal"})
   */
  protected $origin = "Internal";

  /**
   * @Assert\NotBlank()
   * @ORM\Column(type="string", length=255, unique=true)
   */
  protected $title;

  /**
   * @ORM\Column(type="boolean", options={"default"=false})
   */
  protected $published;

  /**
   * @ORM\Column(type="string", length=512, nullable=true)
   */
  protected $slug;


  /**
   * @Assert\NotBlank()
   * @ORM\Column(type="string", length=3000)
   */
  protected $description;


  /**
   * @ORM\Column(type="string", length=16, nullable=true)
   */
  protected $subject_start_date;


  /**
   * @ORM\Column(type="string", length=16, nullable=true)
   */
  protected $subject_end_date;


  /**
   * @ORM\Column(type="string", length=128, nullable=true)
   */
  protected $dataset_size;


  /**
   * @ORM\Column(type="string", length=256, nullable=true)
   */
  protected $subscriber;


  /**
   * @ORM\Column(type="string", length=3000, nullable=true)
   */
  protected $access_instructions;


  /**
   * @ORM\Column(type="string", length=3000, nullable=true)
   */
  protected $licensing_details;


  /**
   * @ORM\Column(type="date", nullable=true)
   */
  protected $license_expiration_date;


  /**
   * @ORM\Column(type="string", length=1028, nullable=true)
   */
  protected $erd_url;


  /**
   * @ORM\Column(type="string", length=1028, nullable=true)
   */
  protected $library_catalog_url;


  /**
   * @ORM\Column(type="string", length=256, nullable=true)
   */
  protected $funder_category;


  /**
   * @ORM\Column(type="string", length=1028, nullable=true)
   */
  protected $pubmed_search;


  
  /**
   * @ORM\Column(type="date", nullable=true)
   */
  protected $date_added;


  /**
   * @ORM\Column(type="date", nullable=true)
   */
  protected $date_updated;


  /**
   * @ORM\Column(type="date", nullable=true)
   */
  protected $date_archived;


  /**
   * @ORM\Column(type="string", length=512, nullable=true)
   */
  protected $accession_number;


  /**
   * @ORM\Column(type="string", length=3000, nullable=true)
   */
  protected $data_location_description;



  //
  //
  // BEGIN ASSOCIATED ENTITIES
  //
  //


  /**
   * @ORM\ManyToMany(targetEntity="DatasetFormat", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_dataset_formats",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="data_format_id",referencedColumnName="data_format_id")}
   *                )
   */
  protected $dataset_formats;


  /**
   * @ORM\ManyToMany(targetEntity="Award", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_awards",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="award_id",referencedColumnName="award_id")}
   *                )
   */
  protected $awards;


  /**
   * @ORM\ManyToMany(targetEntity="AccessRestriction", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_access_restrictions",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="restriction_id",referencedColumnName="restriction_id")}
   *                )
   */
  protected $access_restrictions;


  /**
   * @ORM\ManyToMany(targetEntity="DataCollectionStandard", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_standards",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="standard_id",referencedColumnName="standard_id")}
   *                )
   */
  protected $data_collection_standards;


  /**
   * @ORM\ManyToMany(targetEntity="SubjectGender", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_genders",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="gender_id",referencedColumnName="gender_id")}
   *                )
   */
  protected $subject_genders;


  /**
   * @ORM\ManyToMany(targetEntity="SubjectPopulationAge", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_ages",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="pop_age_id",referencedColumnName="pop_age_id")}
   *                )
   */
  protected $subject_population_ages;


  /**
   * @ORM\ManyToMany(targetEntity="DataType", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_data_types",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="data_type_id",referencedColumnName="data_type_id")}
   *                )
   */
  protected $data_types;

  
  /**
   * @ORM\ManyToMany(targetEntity="SubjectGeographicArea", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_geographic_areas",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="area_id",referencedColumnName="area_id")}
   *                )
   * @ORM\OrderBy({"geographic_area_name"="ASC"})
   */
  protected $subject_geographic_areas;
  
  
  /**
   * @ORM\ManyToMany(targetEntity="SubjectGeographicAreaDetail", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_geographic_area_details",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="area_detail_id",referencedColumnName="area_detail_id")}
   *                )
   * @ORM\OrderBy({"geographic_area_detail_name"="ASC"})
   */
  protected $subject_geographic_area_details;
  
  
  /**
   * @ORM\ManyToMany(targetEntity="SubjectDomain", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_subject_domains",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="subject_domain_id",referencedColumnName="subject_domain_id")}
   *                )
   * @ORM\OrderBy({"subject_domain"="ASC"})
   */
  protected $subject_domains;
  
  
  /**
   * @ORM\ManyToMany(targetEntity="Publication", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_publications",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="publication_id",referencedColumnName="publication_id")}
   *                )
   */
  protected $publications;


  /**
   * @ORM\ManyToMany(targetEntity="SubjectKeyword", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_keywords",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="keyword_id",referencedColumnName="keyword_id")}
   *                )
   * @ORM\OrderBy({"keyword"="ASC"})
   */
  protected $subject_keywords;


  /**
   * @ORM\ManyToMany(targetEntity="StudyType", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_study_types",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="study_type_id",referencedColumnName="study_type_id")}
   *                )
   * @ORM\OrderBy({"study_type"="ASC"})
   */
  protected $study_types;


  /**
   * @ORM\ManyToMany(targetEntity="Publisher", cascade={"all"})
   * @ORM\JoinTable(name="datasets_publishers",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="publisher_id",referencedColumnName="publisher_id")}
   *                )
   * @ORM\OrderBy({"publisher_name"="ASC"})
   */
  protected $publishers;


  /**
   * @ORM\ManyToMany(targetEntity="Person", cascade={"persist"})
   * @ORM\JoinTable(name="datasets_authors",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="person_id",referencedColumnName="person_id")}
   *                )
   * @ORM\OrderBy({"full_name"="ASC"})
   */
  protected $authors;


  /**
   * @ORM\ManyToMany(targetEntity="Person", cascade={"persist"})
   * @ORM\JoinTable(name="datasets_corresponding_authors",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="person_id",referencedColumnName="person_id")}
   *                )
   * @ORM\OrderBy({"full_name"="ASC"})
   */
  protected $corresponding_authors;


  /**
   * @ORM\ManyToMany(targetEntity="Person", cascade={"persist"})
   * @ORM\JoinTable(name="datasets_experts",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="person_id",referencedColumnName="person_id")}
   *                )
   * @ORM\OrderBy({"full_name"="ASC"})
   */
  protected $local_experts;



  /**
   * @ORM\ManyToMany(targetEntity="RelatedSoftware", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_related_software",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="related_software_id",referencedColumnName="related_software_id")}
   *                )
   * @ORM\OrderBy({"related_software"="ASC"})
   */
  protected $related_software;


  /**
   * @ORM\ManyToMany(targetEntity="RelatedEquipment", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_related_equipment",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="related_equipment_id",referencedColumnName="related_equipment_id")}
   *                )
   * @ORM\OrderBy({"related_equipment"="ASC"})
   */
  protected $related_equipment;



  /**
   * @ORM\ManyToMany(targetEntity="SubjectOfStudy", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_subject_of_study",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="subject_of_study_id",referencedColumnName="subject_of_study_id")}
   *                )
   * @ORM\OrderBy({"subject_of_study"="ASC"})
   */
  protected $subject_of_study;




  //
  //
  // BEGIN OneToMany RELATIONSHIPS
  //
  //

  /**
   * @ORM\OneToMany(targetEntity="DataLocation", mappedBy="datasets_dataset_uid", cascade={"all"})
   **/
  protected $data_locations;


  /**
   * @ORM\OneToMany(targetEntity="OtherResource", mappedBy="datasets_dataset_uid", cascade={"all"})
   **/
  protected $other_resources;


  /**
   * @ORM\OneToMany(targetEntity="DatasetAlternateTitle", mappedBy="datasets_dataset_uid", cascade={"all"})
   **/
  protected $dataset_alternate_titles;


  /**
   * @ORM\OneToMany(targetEntity="DatasetRelationship", mappedBy="parent_dataset_uid", cascade={"all"})
   **/
  protected $related_datasets;


    /**
     * Constructor
     */
    public function __construct()
    {
      $this->date_added = new \DateTime("now");
        $this->dataset_formats = new \Doctrine\Common\Collections\ArrayCollection();
        $this->awards = new \Doctrine\Common\Collections\ArrayCollection();
        $this->access_restrictions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->data_collection_standards = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subject_genders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subject_population_ages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->data_types = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subject_geographic_areas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subject_geographic_area_details = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subject_domains = new \Doctrine\Common\Collections\ArrayCollection();
        $this->publications = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subject_keywords = new \Doctrine\Common\Collections\ArrayCollection();
        $this->publishers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->data_locations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->other_resources = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dataset_alternate_titles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->related_datasets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->related_software = new \Doctrine\Common\Collections\ArrayCollection();
        $this->related_equipment = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subject_of_study = new \Doctrine\Common\Collections\ArrayCollection();

        $this->published = false;
    }

  /**
   * get name for display
   *
   * @return string
   */
  public function getDisplayName() {
    return $this->title;
  }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->dataset_uid;
    }

    /**
     * Set origin
     *
     * @param string $origin
     * @return Dataset
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get origin
     *
     * @return string 
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Dataset
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished() {
      return $this->published;
    }

    /**
     * Set published
     *
     * @return boolean
     */
    public function setPublished($published) {
      $this->published = $published;

      return $this;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Dataset
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
     * Set description
     *
     * @param string $description
     * @return Dataset
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }



    /**
     * Set subject_start_date
     *
     * @param \DateTime $subjectStartDate
     * @return Dataset
     */
    public function setSubjectStartDate($subjectStartDate)
    {
        $this->subject_start_date = $subjectStartDate;

        return $this;
    }

    /**
     * Get subject_start_date
     *
     * @return \DateTime 
     */
    public function getSubjectStartDate()
    {
        return $this->subject_start_date;
    }

    /**
     * Set subject_end_date
     *
     * @param \DateTime $subjectEndDate
     * @return Dataset
     */
    public function setSubjectEndDate($subjectEndDate)
    {
        $this->subject_end_date = $subjectEndDate;

        return $this;
    }

    /**
     * Get subject_end_date
     *
     * @return \DateTime 
     */
    public function getSubjectEndDate()
    {
        return $this->subject_end_date;
    }

    /**
     * Set dataset_size
     *
     * @param string $datasetSize
     * @return Dataset
     */
    public function setDatasetSize($datasetSize)
    {
        $this->dataset_size = $datasetSize;

        return $this;
    }

    /**
     * Get dataset_size
     *
     * @return string 
     */
    public function getDatasetSize()
    {
        return $this->dataset_size;
    }

    /**
     * Set subscriber
     *
     * @param string $subscriber
     * @return Dataset
     */
    public function setSubscriber($subscriber)
    {
        $this->subscriber = $subscriber;

        return $this;
    }

    /**
     * Get subscriber
     *
     * @return string 
     */
    public function getSubscriber()
    {
        return $this->subscriber;
    }

    /**
     * Set access_instructions
     *
     * @param string $accessInstructions
     * @return Dataset
     */
    public function setAccessInstructions($accessInstructions)
    {
        $this->access_instructions = $accessInstructions;

        return $this;
    }

    /**
     * Get access_instructions
     *
     * @return string 
     */
    public function getAccessInstructions()
    {
        return $this->access_instructions;
    }

    /**
     * Set licensing_details
     *
     * @param string $licensingDetails
     * @return Dataset
     */
    public function setLicensingDetails($licensingDetails)
    {
        $this->licensing_details = $licensingDetails;

        return $this;
    }

    /**
     * Get licensing_details
     *
     * @return string 
     */
    public function getLicensingDetails()
    {
        return $this->licensing_details;
    }

    /**
     * Set license_expiration_date
     *
     * @param \DateTime $licenseExpirationDate
     * @return Dataset
     */
    public function setLicenseExpirationDate($licenseExpirationDate)
    {
        $this->license_expiration_date = $licenseExpirationDate;

        return $this;
    }

    /**
     * Get license_expiration_date
     *
     * @return \DateTime 
     */
    public function getLicenseExpirationDate()
    {
        return $this->license_expiration_date;
    }

    /**
     * Set erd_url
     *
     * @param string $erdUrl
     * @return Dataset
     */
    public function setErdUrl($erdUrl)
    {
        $this->erd_url = $erdUrl;

        return $this;
    }

    /**
     * Get erd_url
     *
     * @return string 
     */
    public function getErdUrl()
    {
        return $this->erd_url;
    }

    /**
     * Set library_catalog_url
     *
     * @param string $libraryCatalogUrl
     * @return Dataset
     */
    public function setLibraryCatalogUrl($libraryCatalogUrl)
    {
        $this->library_catalog_url = $libraryCatalogUrl;

        return $this;
    }

    /**
     * Get library_catalog_url
     *
     * @return string 
     */
    public function getLibraryCatalogUrl()
    {
        return $this->library_catalog_url;
    }

    /**
     * Set funder_category
     *
     * @param string $funderCategory
     * @return Dataset
     */
    public function setFunderCategory($funderCategory)
    {
        $this->funder_category = $funderCategory;

        return $this;
    }

    /**
     * Get funder_category
     *
     * @return string 
     */
    public function getFunderCategory()
    {
        return $this->funder_category;
    }

    /**
     * Set pubmed_search
     *
     * @param string $pubmedSearch
     * @return Dataset
     */
    public function setPubmedSearch($pubmedSearch)
    {
        $this->pubmed_search = $pubmedSearch;

        return $this;
    }

    /**
     * Get pubmed_search
     *
     * @return string 
     */
    public function getPubmedSearch()
    {
        return $this->pubmed_search;
    }

    /**
     * Set date_added
     *
     * @param \DateTime $dateAdded
     * @return Dataset
     */
    public function setDateAdded($dateAdded)
    {
        $this->date_added = $dateAdded;

        return $this;
    }

    /**
     * Get date_added
     *
     * @return \DateTime 
     */
    public function getDateAdded()
    {
        return $this->date_added;
    }

    /**
     * Set date_updated
     *
     * @param \DateTime $dateUpdated
     * @return Dataset
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->date_updated = $dateUpdated;

        return $this;
    }

    /**
     * Get date_updated
     *
     * @return \DateTime 
     */
    public function getDateUpdated()
    {
        return $this->date_updated;
    }

    /**
     * Set date_archived
     *
     * @param \DateTime $dateArchived
     * @return Dataset
     */
    public function setDateArchived($dateArchived)
    {
        $this->date_archived = $dateArchived;

        return $this;
    }

    /**
     * Get date_archived
     *
     * @return \DateTime 
     */
    public function getDateArchived()
    {
        return $this->date_archived;
    }

    /**
     * Set accession_number
     *
     * @param string $accessionNumber
     * @return Dataset
     */
    public function setAccessionNumber($accessionNumber)
    {
        $this->accession_number = $accessionNumber;

        return $this;
    }

    /**
     * Get accession_number
     *
     * @return string 
     */
    public function getAccessionNumber()
    {
        return $this->accession_number;
    }

    /**
     * Set data_location_description
     *
     * @param string $dataLocationDescription
     * @return Dataset
     */
    public function setDataLocationDescription($dataLocationDescription)
    {
        $this->data_location_description = $dataLocationDescription;

        return $this;
    }

    /**
     * Get data_location_description
     *
     * @return string 
     */
    public function getDataLocationDescription()
    {
        return $this->data_location_description;
    }

    /**
     * Add dataset_formats
     *
     * @param \AppBundle\Entity\DatasetFormat $datasetFormats
     * @return Dataset
     */
    public function addDatasetFormat(\AppBundle\Entity\DatasetFormat $datasetFormats)
    {
        $this->dataset_formats[] = $datasetFormats;

        return $this;
    }

    /**
     * Remove dataset_formats
     *
     * @param \AppBundle\Entity\DatasetFormat $datasetFormats
     */
    public function removeDatasetFormat(\AppBundle\Entity\DatasetFormat $datasetFormats)
    {
        $this->dataset_formats->removeElement($datasetFormats);
    }

    /**
     * Get dataset_formats
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDatasetFormats()
    {
        return $this->dataset_formats;
    }

    /**
     * Add awards
     *
     * @param \AppBundle\Entity\Award $awards
     * @return Dataset
     */
    public function addAward(\AppBundle\Entity\Award $awards)
    {
        $this->awards[] = $awards;

        return $this;
    }

    /**
     * Remove awards
     *
     * @param \AppBundle\Entity\Award $awards
     */
    public function removeAward(\AppBundle\Entity\Award $awards)
    {
        $this->awards->removeElement($awards);
    }

    /**
     * Get awards
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAwards()
    {
        return $this->awards;
    }

    /**
     * Add access_restrictions
     *
     * @param \AppBundle\Entity\AccessRestriction $accessRestrictions
     * @return Dataset
     */
    public function addAccessRestriction(\AppBundle\Entity\AccessRestriction $accessRestrictions)
    {
        $this->access_restrictions[] = $accessRestrictions;

        return $this;
    }

    /**
     * Remove access_restrictions
     *
     * @param \AppBundle\Entity\AccessRestriction $accessRestrictions
     */
    public function removeAccessRestriction(\AppBundle\Entity\AccessRestriction $accessRestrictions)
    {
        $this->access_restrictions->removeElement($accessRestrictions);
    }

    /**
     * Get access_restrictions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAccessRestrictions()
    {
        return $this->access_restrictions;
    }

    /**
     * Add data_collection_standards
     *
     * @param \AppBundle\Entity\DataCollectionStandard $dataCollectionStandards
     * @return Dataset
     */
    public function addDataCollectionStandard(\AppBundle\Entity\DataCollectionStandard $dataCollectionStandard)
    {
        $this->data_collection_standards[] = $dataCollectionStandard;

        return $this;
    }

    /**
     * Remove data_collection_standards
     *
     * @param \AppBundle\Entity\DataCollectionStandard $dataCollectionStandards
     */
    public function removeDataCollectionStandard(\AppBundle\Entity\DataCollectionStandard $dataCollectionStandard)
    {
        $this->data_collection_standards->removeElement($dataCollectionStandard);
    }

    /**
     * Get data_collection_standards
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDataCollectionStandards()
    {
        return $this->data_collection_standards;
    }

    /**
     * Add subject_genders
     *
     * @param \AppBundle\Entity\SubjectGender $subjectGenders
     * @return Dataset
     */
    public function addSubjectGender(\AppBundle\Entity\SubjectGender $subjectGenders)
    {
        $this->subject_genders[] = $subjectGenders;

        return $this;
    }

    /**
     * Remove subject_genders
     *
     * @param \AppBundle\Entity\SubjectGender $subjectGenders
     */
    public function removeSubjectGender(\AppBundle\Entity\SubjectGender $subjectGenders)
    {
        $this->subject_genders->removeElement($subjectGenders);
    }

    /**
     * Get subject_genders
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubjectGenders()
    {
        return $this->subject_genders;
    }

    /**
     * Add subject_population_ages
     *
     * @param \AppBundle\Entity\SubjectPopulationAge $subjectPopulationAges
     * @return Dataset
     */
    public function addSubjectPopulationAge(\AppBundle\Entity\SubjectPopulationAge $subjectPopulationAges)
    {
        $this->subject_population_ages[] = $subjectPopulationAges;

        return $this;
    }

    /**
     * Remove subject_population_ages
     *
     * @param \AppBundle\Entity\SubjectPopulationAge $subjectPopulationAges
     */
    public function removeSubjectPopulationAge(\AppBundle\Entity\SubjectPopulationAge $subjectPopulationAges)
    {
        $this->subject_population_ages->removeElement($subjectPopulationAges);
    }

    /**
     * Get subject_population_ages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubjectPopulationAges()
    {
        return $this->subject_population_ages;
    }

    /**
     * Add data_types
     *
     * @param \AppBundle\Entity\DataType $dataTypes
     * @return Dataset
     */
    public function addDataType(\AppBundle\Entity\DataType $dataTypes)
    {
        $this->data_types[] = $dataTypes;

        return $this;
    }

    /**
     * Remove data_types
     *
     * @param \AppBundle\Entity\DataType $dataTypes
     */
    public function removeDataType(\AppBundle\Entity\DataType $dataTypes)
    {
        $this->data_types->removeElement($dataTypes);
    }

    /**
     * Get data_types
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDataTypes()
    {
        return $this->data_types;
    }

    /**
     * Add subject_geographic_areas
     *
     * @param \AppBundle\Entity\SubjectGeographicArea $subjectGeographicAreas
     * @return Dataset
     */
    public function addSubjectGeographicArea(\AppBundle\Entity\SubjectGeographicArea $subjectGeographicAreas)
    {
        $this->subject_geographic_areas[] = $subjectGeographicAreas;

        return $this;
    }

    /**
     * Remove subject_geographic_areas
     *
     * @param \AppBundle\Entity\SubjectGeographicArea $subjectGeographicAreas
     */
    public function removeSubjectGeographicArea(\AppBundle\Entity\SubjectGeographicArea $subjectGeographicAreas)
    {
        $this->subject_geographic_areas->removeElement($subjectGeographicAreas);
    }

    /**
     * Get subject_geographic_areas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubjectGeographicAreas()
    {
        return $this->subject_geographic_areas;
    }
    /**
     * Add subject_geographic_area_details
     *
     * @param \AppBundle\Entity\SubjectGeographicAreaDetail $subjectGeographicAreaDetail
     * @return Dataset
     */
    public function addSubjectGeographicAreaDetail(\AppBundle\Entity\SubjectGeographicAreaDetail $subjectGeographicAreaDetails)
    {
        $this->subject_geographic_area_details[] = $subjectGeographicAreaDetails;

        return $this;
    }

    /**
     * Remove subject_geographic_area_details
     *
     * @param \AppBundle\Entity\SubjectGeographicAreaDetail $subjectGeographicAreaDetails
     */
    public function removeSubjectGeographicAreaDetail(\AppBundle\Entity\SubjectGeographicAreaDetail $subjectGeographicAreaDetails)
    {
        $this->subject_geographic_area_details->removeElement($subjectGeographicAreaDetails);
    }

    /**
     * Get subject_geographic_area_details
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubjectGeographicAreaDetails()
    {
        return $this->subject_geographic_area_details;
    }

    /**
     * Add subject_domains
     *
     * @param \AppBundle\Entity\SubjectDomain $subjectDomains
     * @return Dataset
     */
    public function addSubjectDomain(\AppBundle\Entity\SubjectDomain $subjectDomains)
    {
        $this->subject_domains[] = $subjectDomains;

        return $this;
    }

    /**
     * Remove subject_domains
     *
     * @param \AppBundle\Entity\SubjectDomain $subjectDomains
     */
    public function removeSubjectDomain(\AppBundle\Entity\SubjectDomain $subjectDomains)
    {
        $this->subject_domains->removeElement($subjectDomains);
    }

    /**
     * Get subject_domains
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubjectDomains()
    {
        return $this->subject_domains;
    }

    /**
     * Add publications
     *
     * @param \AppBundle\Entity\Publication $publications
     * @return Dataset
     */
    public function addPublication(\AppBundle\Entity\Publication $publications)
    {
        $this->publications[] = $publications;

        return $this;
    }

    /**
     * Remove publications
     *
     * @param \AppBundle\Entity\Publication $publications
     */
    public function removePublication(\AppBundle\Entity\Publication $publications)
    {
        $this->publications->removeElement($publications);
    }

    /**
     * Get publications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPublications()
    {
        return $this->publications;
    }

    /**
     * Add subject_keywords
     *
     * @param \AppBundle\Entity\SubjectKeyword $subjectKeywords
     * @return Dataset
     */
    public function addSubjectKeyword(\AppBundle\Entity\SubjectKeyword $subjectKeywords)
    {
        $this->subject_keywords[] = $subjectKeywords;

        return $this;
    }

    /**
     * Remove subject_keywords
     *
     * @param \AppBundle\Entity\SubjectKeyword $subjectKeywords
     */
    public function removeSubjectKeyword(\AppBundle\Entity\SubjectKeyword $subjectKeywords)
    {
        $this->subject_keywords->removeElement($subjectKeywords);
    }

    /**
     * Get subject_keywords
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubjectKeywords()
    {
        return $this->subject_keywords;
    }




    /**
     * Add study_types
     *
     * @param \AppBundle\Entity\StudyType $studyType
     * @return Dataset
     */
    public function addStudyType(\AppBundle\Entity\StudyType $studyType)
    {
        $this->study_types[] = $studyType;

        return $this;
    }

    /**
     * Remove study_types
     *
     * @param \AppBundle\Entity\StudyType $studyType
     */
    public function removeStudyType(\AppBundle\Entity\StudyType $studyType)
    {
        $this->study_types->removeElement($studyType);
    }

    /**
     * Get study_types
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStudyTypes()
    {
        return $this->study_types;
    }




    /**
     * Add publishers
     *
     * @param \AppBundle\Entity\Publisher $publishers
     * @return Dataset
     */
    public function addPublisher(\AppBundle\Entity\Publisher $publishers)
    {
        $this->publishers[] = $publishers;

        return $this;
    }

    /**
     * Remove publishers
     *
     * @param \AppBundle\Entity\Publisher $publishers
     */
    public function removePublisher(\AppBundle\Entity\Publisher $publishers)
    {
        $this->publishers->removeElement($publishers);
    }

    /**
     * Get publishers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPublishers()
    {
        return $this->publishers;
    }

    /**
     * Add data_location
     *
     * @param \AppBundle\Entity\DataLocation $dataLocation
     * @return Dataset
     */
    public function addDataLocation(\AppBundle\Entity\DataLocation $dataLocation)
    {
      if (!$this->data_locations->contains($dataLocation)) {
        $this->data_locations[] = $dataLocation;
        $dataLocation->setDatasetsDatasetUid($this);
      }

        return $this;
    }

    /**
     * Remove data_location
     *
     * @param \AppBundle\Entity\DataLocation $dataLocation
     */
    public function removeDataLocation(\AppBundle\Entity\DataLocation $dataLocation)
    {
      if ($this->data_locations->contains($dataLocation)) {
        $this->data_locations->removeElement($dataLocation);
        $dataLocation->setDatasetsDatasetUid(null);
      }
    }

    /**
     * Get data_location
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDataLocations()
    {
        return $this->data_locations;
    }

    /**
     * Add other_resource
     *
     * @param \AppBundle\Entity\OtherResource $otherResource
     * @return Dataset
     */
    public function addOtherResource(\AppBundle\Entity\OtherResource $otherResource)
    {
      if (!$this->other_resources->contains($otherResource)) {
        $this->other_resources[] = $otherResource;
        $otherResource->setDatasetsDatasetUid($this);
      }

        return $this;
    }

    /**
     * Remove other_resource
     *
     * @param \AppBundle\Entity\OtherResource $otherResource
     */
    public function removeOtherResource(\AppBundle\Entity\OtherResource $otherResource)
    {
      if ($this->other_resources->contains($otherResource)) {
        $this->other_resources->removeElement($otherResource);
        $otherResource->setDatasetsDatasetUid(null);
      }
    }

    /**
     * Get other_resource
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOtherResources()
    {
        return $this->other_resources;
    }


    /**
     * Add dataset_alternate_titles
     *
     * @param \AppBundle\Entity\DatasetAlternateTitle $datasetAlternateTitle
     * @return Dataset
     */
    public function addDatasetAlternateTitle(\AppBundle\Entity\DatasetAlternateTitle $datasetAlternateTitle)
    {
      if (!$this->dataset_alternate_titles->contains($datasetAlternateTitle)) {
        $this->dataset_alternate_titles[] = $datasetAlternateTitle;
        $datasetAlternateTitle->setDatasetsDatasetUid($this);
      }

        return $this;
    }

    /**
     * Remove dataset_alternate_titles
     *
     * @param \AppBundle\Entity\DatasetAlternateTitle $datasetAlternateTitle
     */
    public function removeDatasetAlternateTitle(\AppBundle\Entity\DatasetAlternateTitle $datasetAlternateTitle)
    {
      if ($this->dataset_alternate_titles->contains($datasetAlternateTitle)) {
        $this->dataset_alternate_titles->removeElement($datasetAlternateTitle);
        $datasetAlternateTitle->setDatasetsDatasetUid(null);
      }
    }

    /**
     * Get dataset_alternate_titles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDatasetAlternateTitles()
    {
        return $this->dataset_alternate_titles;
    }

    /**
     * Add related_datasets
     *
     * @param \AppBundle\Entity\DatasetRelationship $relatedDataset
     * @return Dataset
     */
    public function addRelatedDataset(\AppBundle\Entity\DatasetRelationship $relatedDataset)
    {
      if (!$this->related_datasets->contains($relatedDataset)) {
        $this->related_datasets[] = $relatedDataset;
        $relatedDataset->setParentDatasetUid($this);
      }

        return $this;
    }

    /**
     * Remove related_datasets
     *
     * @param \AppBundle\Entity\DatasetRelationship $relatedDataset
     */
    public function removeRelatedDataset(\AppBundle\Entity\DatasetRelationship $relatedDataset)
    {
      if ($this->related_datasets->contains($relatedDataset)) {
        $this->related_datasets->removeElement($relatedDataset);
        $relatedDatset->setParentDatasetUid(null);
      }
    }

    /**
     * Get related_datasets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRelatedDatasets()
    {
        return $this->related_datasets;
    }

    /**
     * Set dataset_uid
     *
     * @param integer $datasetUid
     * @return Dataset
     */
    public function setDatasetUid($datasetUid)
    {
        $this->dataset_uid = $datasetUid;

        return $this;
    }

    /**
     * Get dataset_uid
     *
     * @return integer 
     */
    public function getDatasetUid()
    {
        return $this->dataset_uid;
    }


    /**
     * Produce custom serialized dataset for JSON output using PHP's
     * JsonSerializable interface. Not the most elegant solution, but
     * faster and more customizable than Symfony's Serializer component,
     * and doesn't cause recursion like jms/serializer-bundle did.
     *
     * @return array
     */
     public function jsonSerialize() {
        
        $formats = $awards = $restrictions = $stds = $genders = $ages = $equipment = $software = $subject_of_study = [];
        $areas = $area_details = $domains = $publications = $keywords = $publishers = [];
        $authors = $data_type_array = $types_of_study = $corresponding_authors = $experts = $data_locations = $akas = $related_datasets = [];
        foreach ($this->dataset_formats as $format) { $formats[]=$format->getDisplayName(); }
        foreach ($this->awards as $award) { $awards[]=$award->getDisplayName(); }
        foreach ($this->access_restrictions as $restriction) { $restrictions[]=$restriction->getDisplayName(); }
        foreach ($this->data_collection_standards as $std) { $stds[]=$std->getDisplayName(); }
        foreach ($this->subject_genders as $gender) { $genders[]=$gender->getDisplayName(); }
        foreach ($this->subject_population_ages as $age) { $ages[]=$age->getDisplayName(); }
        foreach ($this->subject_geographic_areas as $area) { $areas[]=$area->getDisplayName(); }
        foreach ($this->subject_geographic_area_details as $detail) { $area_details[]=$detail->getDisplayName(); }
        foreach ($this->subject_domains as $domain) { $domains[]=$domain->getDisplayName(); }
        foreach ($this->publications as $pub) { $publications[]=$pub->getDisplayName(); }
        foreach ($this->subject_keywords as $kwd) { $keywords[]=$kwd->getDisplayName(); }
        foreach ($this->publishers as $pubber) { $publishers[]=$pubber->getDisplayName(); }
        foreach ($this->data_types as $data_type) { $data_type_array[]=$data_type->getDisplayName(); }
        foreach ($this->dataset_alternate_titles as $alt) { $akas[]=$alt->getDisplayName(); }
        foreach ($this->study_types as $study_type) { $types_of_study[]=$study_type->getDisplayName(); }
        foreach ($this->authors as $author) { $authors[]=$author->getDisplayName(); }
        foreach ($this->corresponding_authors as $corresponding_author) { $corresponding_authors[]=$corresponding_author->getDisplayName(); }
        foreach ($this->local_experts as $expert) { $experts[]=$expert->getDisplayName(); }
        foreach ($this->subject_of_study as $subject) { $subject_of_study[]=$subject->getDisplayName(); }
        foreach ($this->related_software as $sw) { $software[]=$sw->getDisplayName(); }
        foreach ($this->related_equipment as $equip) { $equipment[]=$equip->getDisplayName(); }
	      return array(
  	      'id'             	=> $this->dataset_uid,
	        'dataset_title'    	=> $this->title,
          'dataset_alt_title'   =>$akas,
          'description'        	=> $this->description,
      	  'dataset_end_date' 	=> $this->subject_end_date,
	        'dataset_start_date' 	=> $this->subject_start_date,
      	  'local_experts'	=> $experts,
          'authors'       	=> $authors,
          'corresponding_authors' => $corresponding_authors,
          'date_added'		=> $this->date_added,
          'dataset_formats'	=>$formats,
          'data_types'       	=>$data_type_array,
          'study_types'		=>$types_of_study,
          'collection_standards'=>$stds,
          'awards'		=>$awards,
          'access_restrictions'	=>$restrictions,
          'subject_population_ages'=>$ages,
          'subject_geographic_area'=>$areas,
          'subject_geographic_area_details'=>$area_details,
          'subject_domain'	=>$domains,
          'subject_keywords'	=>$keywords,
          'publishers'		=>$publishers,
          'subject_of_study'	=>$subject_of_study,
          'related_software'	=>$software,
          'related_equipment'	=>$equipment,
        );

      }



    /**
     * Add authors
     *
     * @param \AppBundle\Entity\Person $authors
     * @return Dataset
     */
    public function addAuthor(\AppBundle\Entity\Person $authors)
    {
        $this->authors[] = $authors;

        return $this;
    }

    /**
     * Remove authors
     *
     * @param \AppBundle\Entity\Person $authors
     */
    public function removeAuthor(\AppBundle\Entity\Person $authors)
    {
        $this->authors->removeElement($authors);
    }

    /**
     * Get authors
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAuthors()
    {
        return $this->authors;
    }


    /**
     * Add corresponding authors
     *
     * @param \AppBundle\Entity\Person $corresponding_authors
     * @return Dataset
     */
    public function addCorrespondingAuthor(\AppBundle\Entity\Person $corresponding_authors)
    {
        $this->corresponding_authors[] = $corresponding_authors;

        return $this;
    }

    /**
     * Remove corresponding authors
     *
     * @param \AppBundle\Entity\Person $corresponding_authors
     */
    public function removeCorrespondingAuthor(\AppBundle\Entity\Person $corresponding_authors)
    {
        $this->corresponding_authors->removeElement($corresponding_authors);
    }

    /**
     * Get corresponding_authors
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCorrespondingAuthors()
    {
        return $this->corresponding_authors;
    }


    /**
     * Add local_experts
     *
     * @param \AppBundle\Entity\Person $localExperts
     * @return Dataset
     */
    public function addLocalExpert(\AppBundle\Entity\Person $localExperts)
    {
        $this->local_experts[] = $localExperts;

        return $this;
    }

    /**
     * Remove local_experts
     *
     * @param \AppBundle\Entity\Person $localExperts
     */
    public function removeLocalExpert(\AppBundle\Entity\Person $localExperts)
    {
        $this->local_experts->removeElement($localExperts);
    }

    /**
     * Get local_experts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLocalExperts()
    {
        return $this->local_experts;
    }


    /**
     * Add related_software
     *
     * @param \AppBundle\Entity\RelatedSoftware $relatedSoftware
     * @return Dataset
     */
    public function addRelatedSoftware(\AppBundle\Entity\RelatedSoftware $relatedSoftware)
    {
        $this->related_software[] = $relatedSoftware;

        return $this;
    }

    /**
     * Remove related_software
     *
     * @param \AppBundle\Entity\RelatedSoftware $relatedSoftware
     */
    public function removeRelatedSoftware(\AppBundle\Entity\RelatedSoftware $relatedSoftware)
    {
        $this->related_software->removeElement($relatedSoftware);
    }

    /**
     * Get related_software
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRelatedSoftware()
    {
        return $this->related_software;
    }

    /**
     * Add related_equipment
     *
     * @param \AppBundle\Entity\RelatedEquipment $relatedEquipment
     * @return Dataset
     */
    public function addRelatedEquipment(\AppBundle\Entity\RelatedEquipment $relatedEquipment)
    {
        $this->related_equipment[] = $relatedEquipment;

        return $this;
    }

    /**
     * Remove related_equipment
     *
     * @param \AppBundle\Entity\RelatedEquipment $relatedEquipment
     */
    public function removeRelatedEquipment(\AppBundle\Entity\RelatedEquipment $relatedEquipment)
    {
        $this->related_equipment->removeElement($relatedEquipment);
    }

    /**
     * Get related_equipment
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRelatedEquipment()
    {
        return $this->related_equipment;
    }

    /**
     * Add subject_of_study
     *
     * @param \AppBundle\Entity\SubjectOfStudy $subjectOfStudy
     * @return Dataset
     */
    public function addSubjectOfStudy(\AppBundle\Entity\SubjectOfStudy $subjectOfStudy)
    {
        $this->subject_of_study[] = $subjectOfStudy;

        return $this;
    }

    /**
     * Remove subject_of_study
     *
     * @param \AppBundle\Entity\SubjectOfStudy $subjectOfStudy
     */
    public function removeSubjectOfStudy(\AppBundle\Entity\SubjectOfStudy $subjectOfStudy)
    {
        $this->subject_of_study->removeElement($subjectOfStudy);
    }

    /**
     * Get subject_of_study
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubjectOfStudy()
    {
        return $this->subject_of_study;
    }
}
