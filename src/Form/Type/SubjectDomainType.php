<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Form builder for Subject Domain entry
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
class SubjectDomainType extends AbstractType {

  /**
   * Build form
   *
   * @param FormBuilderInterface
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('subject_domain');
    $builder->add('mesh_code', TextType::class, array(
      'label' => 'MeSH URI',
    ));
    $builder->add('save',SubmitType::class,array('label'=>'Submit'));
  }

  /**
   * Set defaults
   *
   * @param OptionsResolver
   */
  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'App\Entity\SubjectDomain'
    ));
  }

  public function getName() {
    return 'subjectDomain';
  }

}

