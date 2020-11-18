<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Form\DataTransformer\SubjectKeywordToStringTransformer;
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
class DatasetAsAdminType extends AbstractType {

  protected $years;
  protected $yearsIncludingPresent;
  protected $options;

  public function __construct(array $options = []) {
    $this->years = range(date('Y'),1790);
    $yearList = range(date('Y'),1790);
    array_unshift($yearList, "Present");
    $this->yearsIncludingPresent = array_combine($yearList, $yearList);

    $resolver = new OptionsResolver();
    $this->configureOptions($resolver);

    $this->options = $resolver->resolve($options);
  }
  
  /**
   * Build the form
   *
   * @param FormBuilderInterface
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    //identifying information
    $builder->add('dataset_uid', TextType::class, array(
      'disabled' => true,
      'data'     => $options['datasetUid'],
      'label'    => 'Dataset ID',
    ));
    $builder->add('title', TextType::class, array(
      'required' => true,
      'label'    => 'Dataset Title'
    ));
    $builder->add('dataset_alternate_titles', CollectionType::class, array(
      'entry_type'      => DatasetAlternateTitleType::class,
      'required' => false,
      'label'     => 'Alternate Titles',
      'by_reference'=>false,
      'prototype' => true,
      'allow_delete' => true,
      'allow_add' => true
    ));
    $builder->add('origin',ChoiceType::class,array(
      'required'=> true,
      'label'   => 'Origin',
      'choices' => array('Internal'=>'Internal',
                         'External'=>'External'),
      'expanded'=>true,
    ));
    $builder->add('description',  TextareaType::class, array(
      'required' => true,
      'attr'=>array('rows'=>'7','placeholder'=>'Please provide a brief description of the dataset'),
      'label'    => 'Description'
    ));
    $builder->add('published', ChoiceType::class, array(
      'required' => false,
      'expanded' => true,
      'empty_data' => false,
      'placeholder'=>false,
      'label'    => 'Published to Data Catalog?',
      'choices'=> array('Yes' => true, 'Not yet'=>false),
    ));
    $builder->add('publishers', EntityType::class, array(
      'class'   => 'App:Publisher',
      'choice_label'=> 'publisher_name',
      'required' => false,
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.publisher_name','ASC');
      },
      'attr'=>array('style'=>'width:100%'),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Publishers',
    ));
    $builder->add('access_restrictions', EntityType::class, array(
      'class'    => 'App:AccessRestriction',
      'choice_label' => 'restriction',
      'attr'=>array('style'=>'width:100%'),
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.restriction','ASC');
      },
      'required' => false,
      'by_reference'=>false,
      'multiple' => true,
      'label'     => 'Access Restrictions',
    ));
    $builder->add('access_instructions',  TextareaType::class, array(
      'attr'=>array('rows'=>'7', 'placeholder'=>'Provide any information on restrictions or conditions for gaining access to data'),
      'label'    => 'Access Instructions'));
    //accession information
    $builder->add('data_locations', CollectionType::class, array(
      'entry_type'      => DataLocationType::class,
      'required' => true,
      'by_reference'=>false,
      'label'     => 'Data Location',
      'prototype' => true,
      'allow_delete' => true,
      'allow_add' => true
    ));
    $builder->add('pubmed_search', TextType::class, array(
      'required' => false,
      'label'    => 'PubMed Search URL'
    ));
    $builder->add('date_archived', DateType::class, array(
      'years'  => $this->years,
      'required' => false,
      'label'    => 'Date Archived'
    ));
    $builder->add('other_resources', CollectionType::class, array(
      'entry_type' => OtherResourceType::class,
      'required' => true,
      'by_reference'=>false,
      'label'     => 'Other Resources',
      'prototype' => true,
      'allow_delete' => true,
      'allow_add' => true
    ));
    //technical details
    $builder->add('related_equipment', EntityType::class, array(
      'class'   => 'App:RelatedEquipment',
      'choice_label'=> 'related_equipment',
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.related_equipment','ASC');
      },
      'required' => false,
      'attr'    => array('style'=>'width:100%'),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Equipment used to collect/create the dataset',
    ));
    $builder->add('related_software', EntityType::class, array(
      'class'   => 'App:RelatedSoftware',
      'choice_label'=> 'software_name',
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.software_name','ASC');
      },
      'required' => false,
      'attr'    => array('style'=>'width:100%'),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Software used to create, collect or analyze the dataset',
    ));
    $builder->add('dataset_formats', EntityType::class, array(
      'class'   => 'App:DatasetFormat',
      'choice_label'=> 'format',
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.format','ASC');
      },
      'required' => false,
      'attr'    => array('id'=>'dataset_subject_population_ages','style'=>'width:100%'),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Dataset File Format',
    ));
    $builder->add('dataset_size', TextType::class, array(
      'required' => false,
      'label'    => 'Dataset Size'
    ));
    $builder->add('data_collection_instruments', EntityType::class, array(
      'class'   => 'App:DataCollectionInstrument',
      'choice_label'=> 'data_collection_instrument_name',
      'required' => false,
      'attr'=>array('style'=>'width:100%', 'placeholder'=>''),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Data Collection Instruments',
    ));
    $builder->add('data_types', EntityType::class, array(
      'class'   => 'App:DataType',
      'choice_label' => 'data_type',
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
    $builder->add('publications', EntityType::class, array(
      'class' => 'App:Publication',
      'choice_label'=>'citation',
      'required' => false,
      'attr'=>array('style'=>'width:100%'),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Publications describing the collection or use of the dataset',
    ));
    $builder->add('awards', EntityType::class, array(
      'class'   => 'App:Award',
      'choice_label'=> 'award',
      'required' => false,
      'attr'    => array('id'=>'dataset_awards','style'=>'width:100%'),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Grants',
    ));
    $builder->add('projects', EntityType::class, array(
      'class'   => 'App:Project',
      'choice_label'=> 'project_name',
      'required' => false,
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.project_name','ASC');
      },
      'attr'=>array('style'=>'width:100%'),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Related Projects',
    ));
    $builder->add('related_datasets', CollectionType::class, array(
      'entry_type' => DatasetRelationshipType::class,
      'required' => true,
      'by_reference'=>false,
      'prototype' => true,
      'label'     => 'Related Datasets',
      'allow_delete' => true,
      'allow_add' => true
    ));
    //content information
    $builder->add('authorships', CollectionType::class, array(
      'entry_type' => PersonAssociationType::class,
      'prototype' => true,
      'required'=>true,
      'by_reference'=>false,
      'label'=>'Authors',
      'allow_delete'=>true,
      'allow_add'=>true
    ));
    $builder->add('local_experts', EntityType::class, array(
      'class' => 'App:Person',
      'choice_label'=>'full_name',
      'required'=>false,
      'attr'=>array('style'=>'width:100%'),
      'multiple'=>true,
      'by_reference'=>false,
      'label'=>'Local Experts',
    ));
    $builder->add('subject_domains', EntityType::class, array(
      'class' => 'App:SubjectDomain',
      'choice_label'=>'subject_domain',
      'required' => false,
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.subject_domain','ASC');
      },
      'attr'=>array('style'=>'width:100%'),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Subject Domains',
    ));
    $builder->add('subject_start_date', ChoiceType::class, array(
      'choices'  => $this->yearsIncludingPresent,
      'required' => false,
      'label'    => 'Year Data Collection Started'
    ));
    $builder->add('subject_end_date', ChoiceType::class, array(
      'choices'  => $this->yearsIncludingPresent,
      'required' => false,
      'label'    => 'Year Data Collection Ended'
    ));
    $builder->add('subject_genders', EntityType::class, array(
      'class'      => 'App:SubjectGender',
      'choice_label'   => 'subject_gender',
      'multiple'   => true,
      'expanded'   => true,
      'required' => false,
      'by_reference'=>false,
      'label'     => 'Subject Genders',
    ));
    $builder->add('subject_sexes', EntityType::class, array(
      'class'      => 'App:SubjectSex',
      'choice_label'   => 'subject_sex',
      'multiple'   => true,
      'expanded'   => true,
      'required' => false,
      'by_reference'=>false,
      'label'     => 'Subject Sexes',
    ));
    $builder->add('subject_population_ages', EntityType::class, array(
      'class'   => 'App:SubjectPopulationAge',
      'choice_label'=> 'age_group',
      'required' => false,
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.age_group','ASC');
      },
      'attr'=>array('style'=>'width:100%'),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Subject Population Age',
    ));
    $builder->add('subject_geographic_areas', EntityType::class, array(
      'class'   => 'App:SubjectGeographicArea',
      'attr'=>array('style'=>'width:100%'),
      'choice_label'=> 'geographic_area_name',
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.geographic_area_name','ASC');
      },
      'required' => false,
      'multiple'=> true,
      'by_reference'=>false,
      'label'     => 'Subject Geographic Areas',
    ));
    $builder->add('subject_geographic_area_details', EntityType::class, array(
      'class'   => 'App:SubjectGeographicAreaDetail',
      'attr'=>array('style'=>'width:100%'),
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.geographic_area_detail_name','ASC');
      },
      'choice_label'=> 'geographic_area_detail_name',
      'required' => false,
      'multiple'=> true,
      'by_reference'=>false,
      'label'     => 'Subject Geographic Area Details',
    ));
    $builder->add('study_types', EntityType::class, array(
      'class'    => 'App:StudyType',
      'choice_label' => 'study_type',
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
    $builder->add('subject_of_study', EntityType::class, array(
      'class'    => 'App:SubjectOfStudy',
      'choice_label' => 'subject_of_study',
      'required' => false,
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.subject_of_study','ASC');
      },
      'multiple' => true,
      'attr'=>array('style'=>'width:100%'),
      'by_reference'=>false,
      'label'     => 'Subject of Study',
    ));
    
    $builder->add('subject_keywords', EntityType::class, array(
      'class'   => 'App:SubjectKeyword',
      'choice_label'=> 'keyword',
      'required' => false,
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.keyword','ASC');
      },
      'multiple' => true,
      'attr'=>array('style'=>'width:100%'),
      'by_reference'=>false,
      'label'     => 'Subject Keywords',
    ));
     
    $builder->add('erd_url', TextType::class, array(
      'required' => false,
      'label'    => 'ERD URL'
    ));
    $builder->add('library_catalog_url', TextType::class, array(
      'required' => false,
      'label'    => 'Library Catalog URL'
    ));
    $builder->add('licensing_details',  TextareaType::class, array(
      'required' => false,
      'label'    => 'Licensing Details'
    ));
    $builder->add('license_expiration_date', DateType::class, array(
      'required' => false,
      'label'    => 'License Expiration Date'
    ));
    $builder->add('subscriber', TextType::class, array(
      'required' => false,
      'label'    => 'Subscriber'
    ));
    $builder->add('archived', ChoiceType::class, array(
      'required' => false,
      'expanded' => true,
      'placeholder'=>false,
      'label'    => 'Archive this Dataset?',
      'choices'=> array('Yes' => true, 'No' => false),
    ));
    $builder->add('archival_notes',  TextareaType::class, array(
      'required' => false,
      'label'    => 'Archival Notes'
    ));
    $builder->add('last_edit_notes',  TextareaType::class, array(
      'required' => false,
      'data'     => '',
      'label'    => 'Notes about this edit',
    ));
    $builder->add('save',SubmitType::class, array(
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
   * @param array 
   */
  public function configureOptions(OptionsResolver $resolver) {

    $resolver->setDefaults(array(
      'data_class' => 'App\Entity\Dataset',
      'datasetUid' => null,
    ));

  }

}
