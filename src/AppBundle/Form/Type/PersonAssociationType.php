<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form builder for Person Association entry
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
class PersonAssociationType extends AbstractType {
  /**
   * Build the form
   *
   * @param FormBuilderInterface
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('person', 'entity', array(
      'class'   => 'AppBundle:Person',
      'property'=> 'full_name',
      'choice_value'=>'displayName',
      'attr'=> array('style'=>'width:50%;', 'class'=>'author-add-form'),
      'multiple'=> false,
      'label'   => false,
    ));
    $builder->add('display_order', 'number', array(
      'label' => false,
      'attr'     => array(
        'placeholder'=>'Author Position #',
        'style'=>'width:50%',

    )));
    $builder->add('is_corresponding_author', 'checkbox', array(
      'label'     => 'Corresponding Author',
      'attr'      => array(
      )
    ));
    $builder->add('role', 'hidden', array(
      'label' => false,
      'required' => false,
      'data'  => 'Author'
    ));


  }

  public function getName() {
    return 'personAssociation';
  }

  /**
   * Set defaults
   *
   * @param OptionsResolverInterface
   */
  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver->setDefaults(array(
      'data_class'  => 'AppBundle\Entity\PersonAssociation',
    ));
  }

}
