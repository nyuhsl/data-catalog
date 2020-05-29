<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
    $builder->add('person', EntityType::class, array(
      'class'   => 'App:Person',
      'choice_label'=> 'full_name',
      'attr'=> array('style'=>'width:100%;', 'class'=>'author-add-form'),
      'multiple'=> false,
      'label'   => false,
      'query_builder'=> function(EntityRepository $er) {
          return $er->createQueryBuilder('u')->orderBy('u.full_name','ASC');
      },
    ));
    $builder->add('display_order', NumberType::class, array(
      'label' => false,
      'attr'     => array(
        'placeholder'=>'* Author Position #',
        'style'=>'width:100%',

    )));
    $builder->add('is_corresponding_author', CheckboxType::class, array(
      'label'     => 'Corresponding Author',
      'required'=>false,
      'attr'      => array(
      )
    ));
    $builder->add('role', HiddenType::class, array(
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
   * @param OptionsResolver
   */
  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults(array(
      'data_class'  => 'App\Entity\PersonAssociation',
    ));
  }

}
