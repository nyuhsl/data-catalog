<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;

/**
 * Form builder for Submit Dataset form
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
 */
class SubmitDatasetFormEmailType extends AbstractType {

  private $affiliationOptions;

  /**
   * Build the form
   *
   * @param FormBuilderInterface
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {

    $builder->add('full_name', 'text', array(
      'required' => true,
      'label_attr'=>array('class'=>'asterisk')));

    $builder->add('email_address', 'email', array(
      'label_attr'=>array('class'=>'asterisk')));

    $builder->add('phone_number', 'text', array(
      'required' => false,
      'label' => 'Phone number (if phone call preferred)',
      'label_attr'=>array('class'=>'asterisk')));

    $builder->add('school_center', 'text', array(
      'required' => false,
      'label'=> 'School/Center',
	  'label_attr'=>array('class'=>'no-asterisk')));

    $builder->add('department', 'text', array(
      'required' => false,
      'label'=> 'Department',
	  'label_attr'=>array('class'=>'no-asterisk')));

    $builder->add('dataset_url', 'text', array(
      'required' => false,
      'label'=> 'If your dataset(s) are already publicly available, please provide the URL',
	  'label_attr'=>array('class'=>'no-asterisk')));

    $builder->add('details', 'textarea', array(
      'required' => false,
      'attr' => array('rows'=>'5'),
      'label_attr'=>array('class'=>'no-asterisk'),
      'label'=>'Please tell us some details about your research and your datasets',
    ));

    $builder->add('comments', 'textarea', array(
      'required' => false,
      'attr' => array('rows'=>'5'),
      'label_attr'=>array('class'=>'no-asterisk'),
      'label'=>'Any other questions or comments',
    ));

    $builder->add('checker', 'text', array(
      'required'=>false,
      'attr'=>array('class'=>'checker'),
      'label_attr'=>array('class'=>'no-asterisk checker')));
    
    $builder->add('recaptcha', EWZRecaptchaType::class);

    $builder->add('save','submit',array('label'=>'Send'));
  }


  /**
   * Build institutional affiliation options list
   * 
   * @param $affiliationOptions
   */
  public function __construct($affiliationOptions) {
    $this->affiliationOptions = [];

    foreach ($affiliationOptions as $option) {
      $this->affiliationOptions[$option] = $option;
    }
  }


  /**
   * Set defaults
   *
   * @param OptionsResolverInterface
   */
  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'AppBundle\Entity\SubmitDatasetFormEmail'
    ));
  }


  public function getName() {
    return 'submitDatasetFormEmail';
  }

}
