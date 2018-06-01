<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Form\DataTransformer\SubjectKeywordToStringTransformer;
use Doctrine\ORM\EntityManager;



/**
 * Form builder for dataset entry as an Admin user
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
 */
class DatasetViaApiType extends AbstractType {

  protected $years;
  protected $yearsIncludingPresent;
  protected $userIsAdmin;
  protected $datasetUid;

  public function __construct($userIsAdmin, $datasetUid) {
    $this->years = range(date('Y'),1790);
    $yearList = range(date('Y'),1790);
    array_unshift($yearList, "Present");
    $this->yearsIncludingPresent = array_combine($yearList, $yearList);
    $this->datasetUid = $datasetUid;
    $this->userIsAdmin = $userIsAdmin;
  }
  
  /**
   * set userIsAdmin
   *
   * @param boolean $userIsAdmin
   */
  public function setUserIsAdmin($userIsAdmin) {
    $this->userIsAdmin = $userIsAdmin;
  }

  /**
   * get userIsAdmin
   *
   * @return boolean
   */
  public function getUserIsAdmin() {
    return $this->userIsAdmin;
  }

  /**
   * set datasetUid
   *
   * @param integer $datasetUid
   */
  public function setDatasetUid($datasetUid) {
    $this->datasetUid = $datasetUid;
  }

  /**
   * get datasetUid
   *
   * @return integer
   */
  public function getDatasetUid() {
    return $this->datasetUid;
  }

  /**
   * Build the form
   *
   * @param FormBuilderInterface
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    //identifying information
    $builder->add('dataset_uid', 'text', array(
      'disabled' => true,
      'data'     => $this->datasetUid,
      'label'    => 'Dataset ID',
    ));
    $builder->add('title', 'text', array(
      'required' => true,
      'label'    => 'Dataset Title'
    ));
    $builder->add('dataset_alternate_titles', 'collection', array(
      'type'      => new DatasetAlternateTitleType(),
      'required' => false,
      'label'     => 'Alternate Titles',
      'by_reference'=>false,
      'prototype' => true,
      'allow_delete' => true,
      'allow_add' => true
    ));
    $builder->add('origin','choice',array(
      'required'=> true,
      'label'   => 'Origin',
      'choices' => array('Internal'=>'Internal',
                         'External'=>'External'),
      'expanded'=>true,
    ));
    $builder->add('description', 'textarea', array(
      'required' => true,
      'attr'=>array('rows'=>'7','placeholder'=>'Please provide a brief description of the dataset'),
      'label'    => 'Description'
    ));
    $builder->add('publishers', 'entity', array(
      'class'   => 'AppBundle:Publisher',
      'property'=> 'publisher_name',
      'required' => false,
      'choice_value' => 'displayName',
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.publisher_name','ASC');
      },
      'attr'=>array('style'=>'width:100%'),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Publishers',
    ));
    $builder->add('access_restrictions', 'entity', array(
      'class'    => 'AppBundle:AccessRestriction',
      'property' => 'restriction',
      'choice_value' => 'displayName',
      'attr'=>array('style'=>'width:100%'),
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.restriction','ASC');
      },
      'required' => false,
      'by_reference'=>false,
      'multiple' => true,
      'label'     => 'Access Restrictions',
    ));
    $builder->add('access_instructions', 'textarea', array(
      'attr'=>array('rows'=>'7', 'placeholder'=>'Provide any information on restrictions or conditions for gaining access to data'),
      'label'    => 'Access Instructions'));
    //accession information
    $builder->add('data_locations', 'collection', array(
      'type'      => new DataLocationType(),
      'required' => false,
      'by_reference'=>false,
      'label'     => 'Data Location',
      'prototype' => true,
      'allow_delete' => true,
      'allow_add' => true
    ));
    $builder->add('pubmed_search', 'text', array(
      'required' => false,
      'label'    => 'PubMed Search URL'
    ));
    $builder->add('date_archived', 'date', array(
      'years'  => $this->years,
      'required' => false,
      'label'    => 'Date Archived'));
    $builder->add('other_resources', 'collection', array(
      'type'      => new OtherResourceType(),
      'required' => false,
      'by_reference'=>false,
      'label'     => 'Other Resources',
      'prototype' => true,
      'allow_delete' => true,
      'allow_add' => true
    ));
    //technical details
    $builder->add('related_equipment', 'entity', array(
      'class'   => 'AppBundle:RelatedEquipment',
      'property'=> 'related_equipment',
      'choice_value' => 'displayName',
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.related_equipment','ASC');
      },
      'required' => false,
      'attr'    => array('style'=>'width:100%'),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Equipment used to collect/create the dataset',
    ));
    $builder->add('related_software', 'entity', array(
      'class'   => 'AppBundle:RelatedSoftware',
      'property'=> 'software_name',  
      'choice_value' => 'displayName',
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.software_name','ASC');
      },
      'required' => false,
      'attr'    => array('style'=>'width:100%'),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Software used to create, collect or analyze the dataset',
    ));
    $builder->add('dataset_formats', 'entity', array(
      'class'   => 'AppBundle:DatasetFormat',
      'property'=> 'format',
      'choice_value' => 'displayName',
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.format','ASC');
      },
      'required' => false,
      'attr'    => array('id'=>'dataset_subject_population_ages','style'=>'width:100%'),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Dataset File Format',
    ));
    $builder->add('dataset_size', 'text', array(
      'required' => false,
      'label'    => 'Dataset Size'));
    $builder->add('data_collection_standards', 'entity', array(
      'class'   => 'AppBundle:DataCollectionStandard',
      'property'=> 'measurement_standard_name',
      'choice_value' => 'displayName',
      'required' => false,
      'attr'=>array('style'=>'width:100%', 'placeholder'=>''),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Data Collection Standards (e.g. CDISC, DICOM, MINI, CDE)',
    ));
    $builder->add('data_types', 'entity', array(
      'class'   => 'AppBundle:DataType',
      'property' => 'data_type',
    'choice_value' => 'displayName',
      'required' => false,
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.data_type','ASC');
      },
      'attr'=>array('style'=>'width:100%'),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Data Types',
    ));
  //people and relations
    $builder->add('publications', 'entity', array(
      'class' => 'AppBundle:Publication',
      'property'=>'citation',
      'choice_value' => 'displayName',
      'required' => false,
      'attr'=>array('style'=>'width:100%'),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Publications describing the collection or use of the dataset',
    ));
    $builder->add('awards', 'entity', array(
      'class'   => 'AppBundle:Award',
      'property'=> 'award',
      'choice_value'=>'displayName',
      'required' => false,
      'attr'    => array('id'=>'dataset_awards','style'=>'width:100%'),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Grants',
    ));
    $builder->add('related_datasets', 'collection', array(
      'type'      => new DatasetRelationshipType(),
      'required' => false,
      'by_reference'=>false,
      'prototype' => true,
      'label'     => 'Related Datasets',
      'allow_delete' => true,
      'allow_add' => true
    ));
    //content information
    $builder->add('authorships', 'collection', array(
      'type' => new PersonAssociationType(),
      'prototype' => true,
      'required'=>false,
      'by_reference'=>false,
      'label'=>'Authors',
      'allow_delete'=>true,
      'allow_add'=>true
    ));
    $builder->add('local_experts', 'entity', array(
      'class' => 'AppBundle:Person',
      'property'=>'full_name',
      'choice_value' => 'displayName',
      'required'=>false,
      'attr'=>array('style'=>'width:100%'),
      'multiple'=>true,
      'by_reference'=>false,
      'label'=>'Local Experts',
    ));
    $builder->add('subject_domains', 'entity', array(
      'class' => 'AppBundle:SubjectDomain',
      'property'=>'subject_domain',
      'required' => false,
      'choice_value' => 'displayName',
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.subject_domain','ASC');
      },
      'attr'=>array('style'=>'width:100%'),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Subject Domains',
    ));
    $builder->add('subject_start_date', 'choice', array(
      'choices'  => $this->yearsIncludingPresent,
      'required' => false,
      'label'    => 'Year Data Collection Started'));
    $builder->add('subject_end_date', 'choice', array(
      'choices'  => $this->yearsIncludingPresent,
      'required' => false,
      'label'    => 'Year Data Collection Ended'));
    $builder->add('subject_genders', 'entity', array(
      'class'      => 'AppBundle:SubjectGender',
      'property'   => 'subject_gender',
      'choice_value' => 'displayName',
      'multiple'   => true,
      'expanded'   => true,
      'required' => false,
      'by_reference'=>false,
      'label'     => 'Subject Genders',
    ));
    $builder->add('subject_sexes', 'entity', array(
      'class'      => 'AppBundle:SubjectSex',
      'property'   => 'subject_sex',
      'choice_value' => 'displayName',
      'multiple'   => true,
      'expanded'   => true,
      'required' => false,
      'by_reference'=>false,
      'label'     => 'Subject Sexes',
    ));
    $builder->add('subject_population_ages', 'entity', array(
      'class'   => 'AppBundle:SubjectPopulationAge',
      'property'=> 'age_group',
      'required' => false,
      'choice_value' => 'displayName',
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.age_group','ASC');
      },
      'attr'=>array('style'=>'width:100%'),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Subject Population Age',
    ));
    $builder->add('subject_geographic_areas', 'entity', array(
      'class'   => 'AppBundle:SubjectGeographicArea',
      'attr'=>array('style'=>'width:100%'),
      'choice_value' => 'displayName',
      'property'=> 'geographic_area_name',
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.geographic_area_name','ASC');
      },
      'required' => false,
      'multiple'=> true,
      'by_reference'=>false,
      'label'     => 'Subject Geographic Areas',
    ));
    $builder->add('subject_geographic_area_details', 'entity', array(
      'class'   => 'AppBundle:SubjectGeographicAreaDetail',
      'attr'=>array('style'=>'width:100%'),
      'choice_value' => 'displayName',
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.geographic_area_detail_name','ASC');
      },
      'property'=> 'geographic_area_detail_name',
      'required' => false,
      'multiple'=> true,
      'by_reference'=>false,
      'label'     => 'Subject Geographic Area Details',
    ));
    $builder->add('study_types', 'entity', array(
      'class'    => 'AppBundle:StudyType',
      'property' => 'study_type',
      'choice_value' => 'displayName',
      'required' => false,
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.study_type','ASC');
      },
      'expanded' => true,
      'multiple' => true,
      'attr'=>array('style'=>'width:100%'),
      'by_reference'=>false,
      'label'     => 'Study Type',
    ));
    $builder->add('subject_of_study', 'entity', array(
      'class'    => 'AppBundle:SubjectOfStudy',
      'property' => 'subject_of_study',
      'choice_value' => 'displayName',
      'required' => false,
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.subject_of_study','ASC');
      },
      'multiple' => true,
      'attr'=>array('style'=>'width:100%'),
      'by_reference'=>false,
      'label'     => 'Subject of Study',
    ));
    $builder->add('subject_keywords', 'entity', array(
      'class'   => 'AppBundle:SubjectKeyword',
      'choice_label'=> 'keyword',
      'choice_value' => 'displayName',
      'required' => false,
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.keyword','ASC');
      },
      'multiple' => true,
      'attr'=>array('style'=>'width:100%'),
      'by_reference'=>false,
      'label'     => 'Subject Keywords',
    ));
    $builder->add('erd_url', 'text', array(
      'required' => false,
      'label'    => 'ERD URL'
    ));
    $builder->add('library_catalog_url', 'text', array(
      'required' => false,
      'label'    => 'Library Catalog URL'
    ));
    $builder->add('licensing_details', 'textarea', array(
      'required' => false,
      'label'    => 'Licensing Details'
    ));
    $builder->add('license_expiration_date', 'date', array(
      'required' => false,
      'label'    => 'License Expiration Date'
    ));
    $builder->add('subscriber', 'text', array(
      'required' => false,
      'label'    => 'Subscriber'
    ));

    $builder->add('save','submit',array(
      "label"=>"Submit",
      'attr'=>array('class'=>'spacer')
    ));

  }

  public function getName() {
    return 'dataset';
  }


  /**
   * Set defaults
   *
   * @param OptionsResolverInterface
   */
  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'AppBundle\Entity\Dataset',
    ));
  }

}
