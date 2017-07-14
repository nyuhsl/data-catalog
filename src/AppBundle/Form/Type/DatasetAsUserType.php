<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;

/**
 * Form builder for dataset entry as a non-privileged user
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
class DatasetAsUserType extends AbstractType {

  protected $years;
  protected $yearsIncludingPresent;
  protected $userIsAdmin;
  protected $datasetUid;
  
  /**
   * Build the form
   *
   * @param FormBuilderInterface
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    //identifying information
    $builder->add('title', 'text', array(
      'required' => true,
      'label'    => 'Dataset Title'));
    $builder->add('description', 'textarea', array(
      'required' => true,
      'attr'=>array('rows'=>'7','placeholder'=>'Please provide a brief description of the dataset'),
      'label'    => 'Description'));

    $builder->add('access_instructions', 'textarea', array(
      'attr'=>array('rows'=>'7', 'placeholder'=>'Provide any information on restrictions or conditions for gaining access to data'),
      'label'    => 'Access Instructions'));
    //technical details
    $builder->add('related_equipment', 'entity', array(
      'class'   => 'AppBundle:RelatedEquipment',
      'property'=> 'related_equipment',
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
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.format','ASC');
      },
      'required' => false,
      'attr'    => array('id'=>'dataset_subject_population_ages','style'=>'width:100%'),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Dataset File Format',
    ));
    $builder->add('data_collection_standards', 'entity', array(
      'class'   => 'AppBundle:DataCollectionStandard',
      'property'=> 'measurement_standard_name',
      'required' => false,
      'attr'=>array('style'=>'width:100%', 'placeholder'=>''),
      'multiple' => true,
      'by_reference'=>false,
      'label'     => 'Data Collection Standards (e.g. CDISC, DICOM, MINI, CDE)',
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
    $builder->add('subject_start_date', 'choice', array(
      'choices'  => $this->yearsIncludingPresent,
      'required' => false,
      'label'    => 'Year Data Collection Started'));
    $builder->add('subject_end_date', 'choice', array(
      'choices'  => $this->yearsIncludingPresent,
      'required' => false,
      'label'    => 'Year Data Collection Ended'));
    $builder->add('subject_of_study', 'entity', array(
      'class'    => 'AppBundle:SubjectOfStudy',
      'property' => 'subject_of_study',
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
      'property'=> 'keyword',
      'required' => false,
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.keyword','ASC');
      },
      'multiple' => true,
      'attr'=>array('style'=>'width:100%'),
      'by_reference'=>false,
      'label'     => 'Subject Keywords',
    ));


    $builder->add('save','submit',array(
      "label"=>"Submit",
      'attr'=>array('class'=>'spacer')));
     

  }

  public function getName() {
    return 'dataset';
  }

  public function __construct($userIsAdmin, $datasetUid) {
    $this->years = range(date('Y'),1790);
    $yearList = range(date('Y'),1790);
    array_unshift($yearList, "Present");
    $this->yearsIncludingPresent = array_combine($yearList, $yearList);
    $this->userIsAdmin = $userIsAdmin;
    $this->datasetUid = $datasetUid;
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
