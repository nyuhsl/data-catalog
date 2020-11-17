<?php
namespace App\Entity;

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
 *
 * @ORM\Entity(repositoryClass="App\Entity\DatasetRepository")
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
  protected $origin;

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
   * @ORM\Column(type="text", length=3000)
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
   * @ORM\Column(type="text", length=3000, nullable=true)
   */
  protected $access_instructions;


  /**
   * @ORM\Column(type="text", length=3000, nullable=true)
   */
  protected $licensing_details;


  /**
   * @ORM\Column(type="date", nullable=true)
   */
  protected $license_expiration_date;


  /**
   * @ORM\Column(type="text", length=1028, nullable=true)
   */
  protected $erd_url;


  /**
   * @ORM\Column(type="text", length=1028, nullable=true)
   */
  protected $library_catalog_url;


  /**
   * @ORM\Column(type="string", length=256, nullable=true)
   */
  protected $funder_category;


  /**
   * @ORM\Column(type="text", length=1028, nullable=true)
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
   * @ORM\Column(type="boolean", options={"default"=false}, nullable=true)
   */
  protected $archived;


  /**
   * @ORM\Column(type="string", length=500, nullable=true)
   */
  protected $archival_notes;


  /**
   * @ORM\Column(type="text", length=3000, nullable=true)
   */
  protected $data_location_description;


  /**
   * Dummy field to capture edit notes. The full history of edit notes is stored in the
   * DatasetEdit entity, but since we want to also capture archival_notes in that entity, we're
   * using the onFlush handler, which only works if the field is managed by Doctrine. So the easiest
   * way to do that is to make an additional field here which can be captured in the onFlush handler.
   *
   * @ORM\Column(type="text", length=500, nullable=true)
   */
  protected $last_edit_notes;

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
   * @ORM\ManyToMany(targetEntity="Project", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_projects",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="project_id",referencedColumnName="project_id")}
   *                )
   * @ORM\OrderBy({"project_name"="ASC"})
   */
  protected $projects;


  /**
   * @ORM\ManyToMany(targetEntity="AccessRestriction", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_access_restrictions",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="restriction_id",referencedColumnName="restriction_id")}
   *                )
   */
  protected $access_restrictions;


  /**
   * @ORM\ManyToMany(targetEntity="DataCollectionInstrument", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_standards",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="standard_id",referencedColumnName="standard_id")}
   *                )
   */
  protected $data_collection_instruments;


  /**
   * @ORM\ManyToMany(targetEntity="SubjectGender", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_genders",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="gender_id",referencedColumnName="gender_id")}
   *                )
   */
  protected $subject_genders;


  /**
   * @ORM\ManyToMany(targetEntity="SubjectSex", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_sexes",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="sex_id",referencedColumnName="sex_id")}
   *                )
   */
  protected $subject_sexes;


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
   * @ORM\ManyToMany(targetEntity="Publisher", cascade={"persist"}, inversedBy="datasets")
   * @ORM\JoinTable(name="datasets_publishers",
   *                joinColumns={@ORM\JoinColumn(name="dataset_uid",referencedColumnName="dataset_uid")},
   *                inverseJoinColumns={@ORM\JoinColumn(name="publisher_id",referencedColumnName="publisher_id")}
   *                )
   * @ORM\OrderBy({"publisher_name"="ASC"})
   */
  protected $publishers;


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
   * @ORM\OrderBy({"software_name"="ASC"})
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
   * @ORM\OneToMany(targetEntity="PersonAssociation", mappedBy="dataset", orphanRemoval=TRUE)
   * @ORM\OrderBy({"display_order" = "ASC"})
   */
  protected $authorships;

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
   * @ORM\OneToMany(targetEntity="DatasetEdit", mappedBy="parent_dataset_uid", cascade={"all"})
   **/
  protected $dataset_edits;

  /** 
   * @ORM\OneToMany(targetEntity="TempAccessKey", mappedBy="dataset_association", cascade={"all"})
   **/
  protected $temp_access_keys;




  /**
   * Constructor
   */
  public function __construct()
  {
    $this->date_added = new \DateTime("now");
    $this->dataset_formats = new \Doctrine\Common\Collections\ArrayCollection();
    $this->awards = new \Doctrine\Common\Collections\ArrayCollection();
    $this->projects = new \Doctrine\Common\Collections\ArrayCollection();
    $this->access_restrictions = new \Doctrine\Common\Collections\ArrayCollection();
    $this->data_collection_instruments = new \Doctrine\Common\Collections\ArrayCollection();
    $this->subject_genders = new \Doctrine\Common\Collections\ArrayCollection();
    $this->subject_sexes = new \Doctrine\Common\Collections\ArrayCollection();
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
    $this->dataset_edits = new \Doctrine\Common\Collections\ArrayCollection();
    $this->related_software = new \Doctrine\Common\Collections\ArrayCollection();
    $this->related_equipment = new \Doctrine\Common\Collections\ArrayCollection();
    $this->subject_of_study = new \Doctrine\Common\Collections\ArrayCollection();
    $this->authorships = new \Doctrine\Common\Collections\ArrayCollection();
    $this->temp_access_keys = new \Doctrine\Common\Collections\ArrayCollection();

    // set field defaults
    $this->published = false;
    $this->archived  = false;
    $this->origin    = "Internal";
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
     * Set archived
     *
     * @param  boolean
     * @return Dataset
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * Get archived
     *
     * @return boolean
     */
    public function getArchived()
    {
        return $this->archived;
    }


    /**
     * Set archival_notes
     *
     * @param string $archivalNotes
     * @return Dataset
     */
    public function setArchivalNotes($archivalNotes)
    {
        $this->archival_notes = $archivalNotes;

        return $this;
    }

    /**
     * Get archival_notes
     *
     * @return string
     */
    public function getArchivalNotes()
    {
        return $this->archival_notes;
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
     * Set last_edit_notes
     *
     * @param string $lastEditNotes
     * @return Dataset
     */
    public function setLastEditNotes($lastEditNotes)
    {
        $this->last_edit_notes = $lastEditNotes;

        return $this;
    }

    /**
     * Get last_edit_notes
     *
     * @return string 
     */
    public function getLastEditNotes()
    {
        return $this->last_edit_notes;
    }


    /**
     * Add dataset_formats
     *
     * @param \App\Entity\DatasetFormat $datasetFormats
     * @return Dataset
     */
    public function addDatasetFormat(\App\Entity\DatasetFormat $datasetFormats)
    {
        $this->dataset_formats[] = $datasetFormats;

        return $this;
    }

    /**
     * Remove dataset_formats
     *
     * @param \App\Entity\DatasetFormat $datasetFormats
     */
    public function removeDatasetFormat(\App\Entity\DatasetFormat $datasetFormats)
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
     * @param \App\Entity\Award $awards
     * @return Dataset
     */
    public function addAward(\App\Entity\Award $awards)
    {
        $this->awards[] = $awards;

        return $this;
    }

    /**
     * Remove awards
     *
     * @param \App\Entity\Award $awards
     */
    public function removeAward(\App\Entity\Award $awards)
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
     * Add projects
     *
     * @param \App\Entity\Project $projects
     * @return Dataset
     */
    public function addProject(\App\Entity\Project $projects)
    {
        $this->projects[] = $projects;

        return $this;
    }

    /**
     * Remove projects
     *
     * @param \App\Entity\Project $projects
     */
    public function removeProject(\App\Entity\Project $projects)
    {
        $this->projects->removeElement($projects);
    }

    /**
     * Get projects
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProjects()
    {
        return $this->projects;
    }


    /**
     * Add access_restrictions
     *
     * @param \App\Entity\AccessRestriction $accessRestrictions
     * @return Dataset
     */
    public function addAccessRestriction(\App\Entity\AccessRestriction $accessRestrictions)
    {
        $this->access_restrictions[] = $accessRestrictions;

        return $this;
    }

    /**
     * Remove access_restrictions
     *
     * @param \App\Entity\AccessRestriction $accessRestrictions
     */
    public function removeAccessRestriction(\App\Entity\AccessRestriction $accessRestrictions)
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
     * Add data_collection_instruments
     *
     * @param \App\Entity\DataCollectionInstrument $dataCollectionInstruments
     * @return Dataset
     */
    public function addDataCollectionInstrument(\App\Entity\DataCollectionInstrument $dataCollectionInstrument)
    {
        $this->data_collection_instruments[] = $dataCollectionInstrument;

        return $this;
    }

    /**
     * Remove data_collection_instruments
     *
     * @param \App\Entity\DataCollectionInstrument $dataCollectionInstruments
     */
    public function removeDataCollectionInstrument(\App\Entity\DataCollectionInstrument $dataCollectionInstrument)
    {
        $this->data_collection_instruments->removeElement($dataCollectionInstrument);
    }

    /**
     * Get data_collection_instruments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDataCollectionInstruments()
    {
        return $this->data_collection_instruments;
    }

    /**
     * Add subject_genders
     *
     * @param \App\Entity\SubjectGender $subjectGenders
     * @return Dataset
     */
    public function addSubjectGender(\App\Entity\SubjectGender $subjectGenders)
    {
        $this->subject_genders[] = $subjectGenders;

        return $this;
    }

    /**
     * Remove subject_genders
     *
     * @param \App\Entity\SubjectGender $subjectGenders
     */
    public function removeSubjectGender(\App\Entity\SubjectGender $subjectGenders)
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
     * Add subject_sexes
     *
     * @param \App\Entity\SubjectGender $subjectSexes
     * @return Dataset
     */
    public function addSubjectSex(\App\Entity\SubjectSex $subjectSexes)
    {
        $this->subject_sexes[] = $subjectSexes;

        return $this;
    }

    /**
     * Remove subject_sexes
     *
     * @param \App\Entity\SubjectGender $subjectSexes
     */
    public function removeSubjectSex(\App\Entity\SubjectSex $subjectSexes)
    {
        $this->subject_sexes->removeElement($subjectSexes);
    }

    /**
     * Get subject_sexes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubjectSexes()
    {
        return $this->subject_sexes;
    }


    /**
     * Add subject_population_ages
     *
     * @param \App\Entity\SubjectPopulationAge $subjectPopulationAges
     * @return Dataset
     */
    public function addSubjectPopulationAge(\App\Entity\SubjectPopulationAge $subjectPopulationAges)
    {
        $this->subject_population_ages[] = $subjectPopulationAges;

        return $this;
    }

    /**
     * Remove subject_population_ages
     *
     * @param \App\Entity\SubjectPopulationAge $subjectPopulationAges
     */
    public function removeSubjectPopulationAge(\App\Entity\SubjectPopulationAge $subjectPopulationAges)
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
     * @param \App\Entity\DataType $dataTypes
     * @return Dataset
     */
    public function addDataType(\App\Entity\DataType $dataTypes)
    {
        $this->data_types[] = $dataTypes;

        return $this;
    }

    /**
     * Remove data_types
     *
     * @param \App\Entity\DataType $dataTypes
     */
    public function removeDataType(\App\Entity\DataType $dataTypes)
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
     * @param \App\Entity\SubjectGeographicArea $subjectGeographicAreas
     * @return Dataset
     */
    public function addSubjectGeographicArea(\App\Entity\SubjectGeographicArea $subjectGeographicAreas)
    {
        $this->subject_geographic_areas[] = $subjectGeographicAreas;

        return $this;
    }

    /**
     * Remove subject_geographic_areas
     *
     * @param \App\Entity\SubjectGeographicArea $subjectGeographicAreas
     */
    public function removeSubjectGeographicArea(\App\Entity\SubjectGeographicArea $subjectGeographicAreas)
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
     * @param \App\Entity\SubjectGeographicAreaDetail $subjectGeographicAreaDetail
     * @return Dataset
     */
    public function addSubjectGeographicAreaDetail(\App\Entity\SubjectGeographicAreaDetail $subjectGeographicAreaDetails)
    {
        $this->subject_geographic_area_details[] = $subjectGeographicAreaDetails;

        return $this;
    }

    /**
     * Remove subject_geographic_area_details
     *
     * @param \App\Entity\SubjectGeographicAreaDetail $subjectGeographicAreaDetails
     */
    public function removeSubjectGeographicAreaDetail(\App\Entity\SubjectGeographicAreaDetail $subjectGeographicAreaDetails)
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
     * @param \App\Entity\SubjectDomain $subjectDomains
     * @return Dataset
     */
    public function addSubjectDomain(\App\Entity\SubjectDomain $subjectDomains)
    {
        $this->subject_domains[] = $subjectDomains;

        return $this;
    }

    /**
     * Remove subject_domains
     *
     * @param \App\Entity\SubjectDomain $subjectDomains
     */
    public function removeSubjectDomain(\App\Entity\SubjectDomain $subjectDomains)
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
     * @param \App\Entity\Publication $publications
     * @return Dataset
     */
    public function addPublication(\App\Entity\Publication $publications)
    {
        $this->publications[] = $publications;

        return $this;
    }

    /**
     * Remove publications
     *
     * @param \App\Entity\Publication $publications
     */
    public function removePublication(\App\Entity\Publication $publications)
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
     * @param \App\Entity\SubjectKeyword $subjectKeywords
     * @return Dataset
     */
    public function addSubjectKeyword(\App\Entity\SubjectKeyword $subjectKeywords)
    {
        $this->subject_keywords[] = $subjectKeywords;

        return $this;
    }

    /**
     * Remove subject_keywords
     *
     * @param \App\Entity\SubjectKeyword $subjectKeywords
     */
    public function removeSubjectKeyword(\App\Entity\SubjectKeyword $subjectKeywords)
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
     * @param \App\Entity\StudyType $studyType
     * @return Dataset
     */
    public function addStudyType(\App\Entity\StudyType $studyType)
    {
        $this->study_types[] = $studyType;

        return $this;
    }

    /**
     * Remove study_types
     *
     * @param \App\Entity\StudyType $studyType
     */
    public function removeStudyType(\App\Entity\StudyType $studyType)
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
     * @param \App\Entity\Publisher $publishers
     * @return Dataset
     */
    public function addPublisher(\App\Entity\Publisher $publishers)
    {
        $this->publishers[] = $publishers;

        return $this;
    }

    /**
     * Remove publishers
     *
     * @param \App\Entity\Publisher $publishers
     */
    public function removePublisher(\App\Entity\Publisher $publishers)
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
     * @param \App\Entity\DataLocation $dataLocation
     * @return Dataset
     */
    public function addDataLocation(\App\Entity\DataLocation $dataLocation)
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
     * @param \App\Entity\DataLocation $dataLocation
     */
    public function removeDataLocation(\App\Entity\DataLocation $dataLocation)
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
     * @param \App\Entity\OtherResource $otherResource
     * @return Dataset
     */
    public function addOtherResource(\App\Entity\OtherResource $otherResource)
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
     * @param \App\Entity\OtherResource $otherResource
     */
    public function removeOtherResource(\App\Entity\OtherResource $otherResource)
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
     * @param \App\Entity\DatasetAlternateTitle $datasetAlternateTitle
     * @return Dataset
     */
    public function addDatasetAlternateTitle(\App\Entity\DatasetAlternateTitle $datasetAlternateTitle)
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
     * @param \App\Entity\DatasetAlternateTitle $datasetAlternateTitle
     */
    public function removeDatasetAlternateTitle(\App\Entity\DatasetAlternateTitle $datasetAlternateTitle)
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
     * @param \App\Entity\DatasetRelationship $relatedDataset
     * @return Dataset
     */
    public function addRelatedDataset(\App\Entity\DatasetRelationship $relatedDataset)
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
     * @param \App\Entity\DatasetRelationship $relatedDataset
     */
    public function removeRelatedDataset(\App\Entity\DatasetRelationship $relatedDataset)
    {
      if ($this->related_datasets->contains($relatedDataset)) {
        $this->related_datasets->removeElement($relatedDataset);
        $relatedDataset->setParentDatasetUid(null);
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
     * Get serialized dataset for ingest by other data catalogs
     *
     * @return array
     */
    public function jsonSerialize() {
      $formats = $awards = $projects = $restrictions = $stds = $genders = $sexes = $ages = [];
      $equipment = $software = $subject_of_study = $others = [];
      $locs = $rel = $areas = $area_details = $domains = $publications = $keywords = $publishers = [];
      $authors = $data_type_array = $types_of_study = $corresponding_authors = $experts = [];
      $data_locations = $akas = $related_datasets = [];

      // these related entities can be added on the fly so we use getAllProperties
      foreach ($this->data_locations as $loc) { $locs[]=$loc->getAllProperties(); }
      foreach ($this->dataset_alternate_titles as $alt) { $akas[]=$alt->getDisplayName(); }
      foreach ($this->other_resources as $other) { $others[]=$other->getAllProperties(); }
      foreach ($this->related_datasets as $rel) { $rels[]=$rel->getAllProperties(); }
      foreach ($this->authorships as $authorship) { $authors[]=$authorship->getAllProperties(); }

      // these related entities will already exist in the catalog so we reference them w/ their displayName
      foreach ($this->subject_keywords as $kwd) { $keywords[]=$kwd->getDisplayName(); }
      foreach ($this->publishers as $pubber) { $publishers[]=$pubber->getDisplayName(); }
      foreach ($this->publications as $pub) { $publications[]=$pub->getDisplayName(); }
      foreach ($this->access_restrictions as $restriction) { $restrictions[]=$restriction->getDisplayName(); }
      foreach ($this->related_equipment as $equip) { $equipment[]=$equip->getDisplayName(); }
      foreach ($this->related_software as $sw) { $software[]=$sw->getDisplayName(); }
      foreach ($this->dataset_formats as $format) { $formats[]=$format->getDisplayName(); }
      foreach ($this->data_types as $data_type) { $data_type_array[]=$data_type->getDisplayName(); }
      foreach ($this->data_collection_instruments as $std) { $stds[]=$std->getDisplayName(); }
      foreach ($this->awards as $award) { $awards[]=$award->getDisplayName(); }
      foreach ($this->projects as $project) { $projects[]=$project->getDisplayName(); }
      foreach ($this->local_experts as $expert) { $experts[]=$expert->getDisplayName(); }
      foreach ($this->subject_domains as $domain) { $domains[]=$domain->getDisplayName(); }
      foreach ($this->subject_genders as $gender) { $genders[]=$gender->getDisplayName(); }
      foreach ($this->subject_sexes as $sex) { $sexes[]=$sex->getDisplayName(); }
      foreach ($this->subject_population_ages as $age) { $ages[]=$age->getDisplayName(); }
      foreach ($this->subject_geographic_areas as $area) { $areas[]=$area->getDisplayName(); }
      foreach ($this->subject_geographic_area_details as $detail) { $area_details[]=$detail->getDisplayName(); }
      foreach ($this->study_types as $study_type) { $types_of_study[]=$study_type->getDisplayName(); }
      foreach ($this->subject_of_study as $subject) { $subject_of_study[]=$subject->getDisplayName(); }

      return array(
        'title'                     => $this->title,
        'origin'                    => $this->origin,
        'description'               => $this->description,
        'access_instructions'       => $this->access_instructions,
        'pubmed_search'             => $this->pubmed_search,
        'dataset_size'              => $this->dataset_size,
        'subject_start_date'        => $this->subject_start_date,
        'subject_end_date'          => $this->subject_end_date,
        'library_catalog_url'       => $this->library_catalog_url,
        'licensing_details'         => $this->licensing_details,
        'license_expiration_date'   => $this->license_expiration_date, //THIS NEEDS TO BE IN SPECIFIC FORMAT
        'subscriber'                => $this->subscriber,
        'data_locations'            => $locs,
        'dataset_alternate_titles'  => $akas,
        'other_resources'           => $others,
        'related_datasets'          => $related_datasets,
        'authorships'               => $authors,
        'subject_keywords'          => $keywords,
        'publishers'                => $publishers,
        'publications'              => $publications,
        'access_restrictions'       => $restrictions,
        'related_equipment'         => $equipment,
        'related_software'          => $software,
        'dataset_formats'           => $formats,
        'data_types'                => $data_type_array,
        'data_collection_standards' => $stds,
        'awards'                    => $awards,
        'projects'                  => $projects,
        'local_experts'             => $experts,
        'subject_domains'           => $domains,
        'subject_genders'           => $genders,
        'subject_sexes'             => $sexes,
        'subject_population_ages'   => $ages,
        'subject_geographic_areas'   => $areas,
        'subject_geographic_area_details'=>$area_details,
        'study_types'               => $types_of_study,
        'subject_of_study'          => $subject_of_study,
      );
    }


    /** 
     * Get serialized dataset for ingest by Solr
     *
     * @return array
     */
     public function serializeForSolr() {
        
       $formats = $awards = $restrictions = $stds = $genders = $sexes = $ages = $equipment = $software = $subject_of_study = [];
       $areas = $projects = $area_details = $domains = $publications = $keywords = $publishers = [];
       $authors = $data_type_array = $types_of_study = $corresponding_authors = $experts = $data_locations = $akas = $related_datasets = [];
       $other_resource_names = $other_resource_descriptions = $related_pubs = $data_location_contents = [];
       $accession_numbers = $access_instructions = [];
       foreach ($this->dataset_formats as $format) { $formats[]=$format->getDisplayName(); }
       foreach ($this->awards as $award) { $awards[]=$award->getDisplayName(); }
       foreach ($this->projects as $project) { $projects[]=$project->getDisplayName(); }
       foreach ($this->access_restrictions as $restriction) { $restrictions[]=$restriction->getDisplayName(); }
       foreach ($this->data_collection_instruments as $std) { $stds[]=$std->getDisplayName(); }
       foreach ($this->subject_genders as $gender) { $genders[]=$gender->getDisplayName(); }
       foreach ($this->subject_sexes as $sex) { $sexes[]=$sex->getDisplayName(); }
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
       foreach ($this->authorships as $authorship) { $authors[]=$authorship->getPerson()->getDisplayName(); }
       foreach ($this->corresponding_authors as $corresponding_author) { $corresponding_authors[]=$corresponding_author->getDisplayName(); }
       foreach ($this->local_experts as $expert) { $experts[]=$expert->getDisplayName(); }
       foreach ($this->subject_of_study as $subject) { $subject_of_study[]=$subject->getDisplayName(); }
       foreach ($this->related_software as $sw) { $software[]=$sw->getDisplayName(); }
       foreach ($this->related_equipment as $equip) { $equipment[]=$equip->getDisplayName(); }

       foreach ($this->other_resources as $resource) { 
         $other_resource_names[]=$resource->getDisplayName(); 
         $other_resource_descriptions[]=$resource->getResourceDescription(); 
       }
       foreach ($this->data_locations as $loc) { 
         $data_locations[]=$loc->getDataAccessUrl(); 
         $data_location_contents[]=$loc->getLocationContent(); 
         $accession_numbers[]=$loc->getAccessionNumber(); 
       }
       foreach ($this->publications as $pub) { $publications[]=$pub->getDisplayName(); }
       return array(
         'id'                    => $this->dataset_uid,
         'dataset_title'         => $this->title,
         'dataset_alt_title'     => $akas,
         'origin'                => $this->origin,
         'description'           => $this->description,
         'dataset_end_date'      => $this->subject_end_date,
         'dataset_start_date'    => $this->subject_start_date,
         'local_experts'         => $experts,
         'authors'               => $authors,
         'corresponding_authors' => $corresponding_authors,
         'date_added'            => $this->date_added,
         'dataset_formats'       => $formats,
         'data_types'            => $data_type_array,
         'study_types'           => $types_of_study,
         'collection_standards'  => $stds,
         'awards'                => $awards,
         'projects'              => $projects,
         'access_restrictions'   => $restrictions,
         'subject_population_ages'=>$ages,
         'subject_geographic_area'=>$areas,
         'subject_geographic_area_details'=>$area_details,
         'subject_domain'        => $domains,
         'subject_keywords'      => $keywords,
         'publishers'            => $publishers,
         'subject_of_study'      => $subject_of_study,
         'related_software'      => $software,
         'related_equipment'     => $equipment,
         'other_resource_names'       => $other_resource_names,
         'other_resource_descriptions'=> $other_resource_descriptions,
         'data_locations'             => $data_locations,
         'data_location_contents'     => $data_location_contents,
         'accession_numbers'          => $accession_numbers,
         'publications'               => $publications,
         'access_instructions'        => $this->access_instructions,
       );
     }


    /**
     * Serialize a complete representation of a Dataset including complete records of all related 
     * entities. Similar to the above except we just use getAllProperties() on every entity that has 
     * more than one property because we are not ingesting this data.
     *
     * @return array
     */
    public function serializeComplete() {
      $formats = $projects = $awards = $restrictions = $stds = $genders = $sexes = $ages = [];
      $equipment = $software = $subject_of_study = $others = [];
      $locs = $rel = $areas = $area_details = $domains = $publications = $keywords = $publishers = [];
      $authors = $data_type_array = $types_of_study = $corresponding_authors = $experts = [];
      $data_locations = $akas = $related_datasets = [];

      foreach ($this->data_locations as $loc) { $locs[]=$loc->getAllProperties(); }
      foreach ($this->dataset_alternate_titles as $alt) { $akas[]=$alt->getDisplayName(); }
      foreach ($this->other_resources as $other) { $others[]=$other->getAllProperties(); }
      foreach ($this->related_datasets as $rel) { $rels[]=$rel->getAllProperties(); }
      foreach ($this->authorships as $authorship) { $authors[]=$authorship->getAllProperties(); }
      foreach ($this->subject_keywords as $kwd) { $keywords[]=$kwd->getAllProperties(); }
      foreach ($this->publishers as $pubber) { $publishers[]=$pubber->getAllProperties(); }
      foreach ($this->publications as $pub) { $publications[]=$pub->getAllProperties(); }
      foreach ($this->access_restrictions as $restriction) { $restrictions[]=$restriction->getDisplayName(); }
      foreach ($this->related_equipment as $equip) { $equipment[]=$equip->getAllProperties(); }
      foreach ($this->related_software as $sw) { $software[]=$sw->getAllProperties(); }
      foreach ($this->dataset_formats as $format) { $formats[]=$format->getDisplayName(); }
      foreach ($this->data_types as $data_type) { $data_type_array[]=$data_type->getDisplayName(); }
      foreach ($this->data_collection_instruments as $std) { $stds[]=$std->getAllProperties(); }
      foreach ($this->awards as $award) { $awards[]=$award->getAllProperties(); }
      foreach ($this->projects as $project) { $projects[]=$project->getAllProperties(); }
      foreach ($this->local_experts as $expert) { $experts[]=$expert->getAllProperties(); }
      foreach ($this->subject_domains as $domain) { $domains[]=$domain->getAllProperties(); }
      foreach ($this->subject_genders as $gender) { $genders[]=$gender->getDisplayName(); }
      foreach ($this->subject_sexes as $sex) { $sexes[]=$sex->getDisplayName(); }
      foreach ($this->subject_population_ages as $age) { $ages[]=$age->getDisplayName(); }
      foreach ($this->subject_geographic_areas as $area) { $areas[]=$area->getAllProperties(); }
      foreach ($this->subject_geographic_area_details as $detail) { $area_details[]=$detail->getAllProperties(); }
      foreach ($this->study_types as $study_type) { $types_of_study[]=$study_type->getDisplayName(); }
      foreach ($this->subject_of_study as $subject) { $subject_of_study[]=$subject->getAllProperties(); }

      return array(
        'title'                     => $this->title,
        'origin'                    => $this->origin,
        'description'               => $this->description,
        'access_instructions'       => $this->access_instructions,
        'pubmed_search'             => $this->pubmed_search,
        'dataset_size'              => $this->dataset_size,
        'subject_start_date'        => $this->subject_start_date,
        'subject_end_date'          => $this->subject_end_date,
        'library_catalog_url'       => $this->library_catalog_url,
        'licensing_details'         => $this->licensing_details,
        'license_expiration_date'   => $this->license_expiration_date, //THIS NEEDS TO BE IN SPECIFIC FORMAT
        'subscriber'                => $this->subscriber,
        'data_locations'            => $locs,
        'dataset_alternate_titles'  => $akas,
        'other_resources'           => $others,
        'related_datasets'          => $related_datasets,
        'authorships'               => $authors,
        'subject_keywords'          => $keywords,
        'publishers'                => $publishers,
        'publications'              => $publications,
        'access_restrictions'       => $restrictions,
        'related_equipment'         => $equipment,
        'related_software'          => $software,
        'dataset_formats'           => $formats,
        'data_types'                => $data_type_array,
        'data_collection_standards' => $stds,
        'awards'                    => $awards,
        'projects'                  => $projects,
        'local_experts'             => $experts,
        'subject_domains'           => $domains,
        'subject_genders'           => $genders,
        'subject_sexes'             => $sexes,
        'subject_population_ages'   => $ages,
        'subject_geographic_areas'   => $areas,
        'subject_geographic_area_details'=>$area_details,
        'study_types'               => $types_of_study,
        'subject_of_study'          => $subject_of_study,
      );
    }


    /**
     * Add author
     *
     * @param \App\Entity\PersonAssociation $authorship
     * @return Dataset
     */
    public function addAuthorship(\App\Entity\PersonAssociation $authorship)
    {
      if (!$this->authorships->contains($authorship)) {
        $this->authorships->add($authorship);
      }

      return $this;
    }

    /**
     * Remove authorship
     *
     * @param \App\Entity\PersonAssociation $authorship
     */
    public function removeAuthorship(\App\Entity\PersonAssociation $authorship)
    {
      if ($this->authorships->contains($authorship)) {
        $this->authorships->removeElement($authorship);
      }
      return $this;
    }

    /**
     * Remove ALL authorships
     *
     */
    public function removeAllAuthorships() 
    {
      $this->getAuthorships()->clear();
    }
    /**
     * 
     * Get authorships
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAuthorships()
    {
        return $this->authorships;
    }

    /**
     * Get authors
     *
     * @return \App\Entity\Person
     */
    public function getAuthors()
    {
      return array_map(
        function($authorship) {
          return $authorship->getPerson();
        },
        $this->authorships->toArray()
      );
    }


    /**
     * Add corresponding authors
     *
     * @param \App\Entity\Person $corresponding_authors
     * @return Dataset
     */
    public function addCorrespondingAuthor(\App\Entity\Person $corresponding_authors)
    {
        $this->corresponding_authors[] = $corresponding_authors;

        return $this;
    }

    /**
     * Remove corresponding authors
     *
     * @param \App\Entity\Person $corresponding_authors
     */
    public function removeCorrespondingAuthor(\App\Entity\Person $corresponding_authors)
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
     * @param \App\Entity\Person $localExperts
     * @return Dataset
     */
    public function addLocalExpert(\App\Entity\Person $localExperts)
    {
        $this->local_experts[] = $localExperts;

        return $this;
    }

    /**
     * Remove local_experts
     *
     * @param \App\Entity\Person $localExperts
     */
    public function removeLocalExpert(\App\Entity\Person $localExperts)
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
     * @param \App\Entity\RelatedSoftware $relatedSoftware
     * @return Dataset
     */
    public function addRelatedSoftware(\App\Entity\RelatedSoftware $relatedSoftware)
    {
        $this->related_software[] = $relatedSoftware;

        return $this;
    }

    /**
     * Remove related_software
     *
     * @param \App\Entity\RelatedSoftware $relatedSoftware
     */
    public function removeRelatedSoftware(\App\Entity\RelatedSoftware $relatedSoftware)
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
     * Add dataset_edits
     *
     * @param \App\Entity\DatasetEdit $datasetEdits
     * @return Dataset
     */
    public function addDatasetEdits(\App\Entity\DatasetEdit $datasetEdits)
    {
        $this->dataset_edits[] = $datasetEdits;

        return $this;
    }

    /**
     * Remove dataset_edit
     *
     * @param \App\Entity\DatasetEdit $datasetEdits
     */
    public function removeDatasetEdits(\App\Entity\DatasetEdit $datasetEdits)
    {
        $this->dataset_edits->removeElement($datasetEdits);
    }

    /**
     * Get dataset_edits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDatasetEdits()
    {
        return $this->dataset_edits;
    }


    /**
     * Add temp_access_keys
     *
     * @param \App\Entity\TempAccessKey $tempAccessKeys
     * @return Dataset
     */
    public function addTempAccessKeys(\App\Entity\TempAccessKey $tempAccessKeys)
    {
        $this->temp_access_keys[] = $tempAccessKeys;

        return $this;
    }

    /**
     * Remove temp_access_keys
     *
     * @param \App\Entity\TempAccessKey $tempAccessKeys
     */
    public function removeTempAccessKeys(\App\Entity\TempAccessKey $tempAccessKeys)
    {
        $this->temp_access_keys->removeElement($tempAccessKeys);
    }

    /**
     * Get temp_access_keys
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTempAccessKeys()
    {
        return $this->temp_access_keys;
    }






    /**
     * Add related_equipment
     *
     * @param \App\Entity\RelatedEquipment $relatedEquipment
     * @return Dataset
     */
    public function addRelatedEquipment(\App\Entity\RelatedEquipment $relatedEquipment)
    {
        $this->related_equipment[] = $relatedEquipment;

        return $this;
    }

    /**
     * Remove related_equipment
     *
     * @param \App\Entity\RelatedEquipment $relatedEquipment
     */
    public function removeRelatedEquipment(\App\Entity\RelatedEquipment $relatedEquipment)
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
     * @param \App\Entity\SubjectOfStudy $subjectOfStudy
     * @return Dataset
     */
    public function addSubjectOfStudy(\App\Entity\SubjectOfStudy $subjectOfStudy)
    {
        $this->subject_of_study[] = $subjectOfStudy;

        return $this;
    }

    /**
     * Remove subject_of_study
     *
     * @param \App\Entity\SubjectOfStudy $subjectOfStudy
     */
    public function removeSubjectOfStudy(\App\Entity\SubjectOfStudy $subjectOfStudy)
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
