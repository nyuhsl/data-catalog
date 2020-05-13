<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/*
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
class AwardType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('award',TextareaType::class,array(
      'label'=>'Grant Number or Name',
    ));
    $builder->add('award_funder',TextareaType::class,array(
      'label'=>'Funding Agency',
    ));
    $builder->add('funder_type',ChoiceType::class,array(
      'label'=>'Funder Type',
      'choices'=>array('Federal, NIH'    => 'Federal, NIH',
                       'Federal, non-NIH'=> 'Federal, non-NIH',
                       'Non-federal'     => 'Non-federal',
                       'Non-US'          => 'Non-US',
                       'Private'         => 'Private'
                      ),
    ));
    $builder->add('award_url', TextareaType::class, array(
      'required'=>false,
      'label'=>'NIH Reporter URL',
    ));
    $builder->add('save',SubmitType::class,array('label'=>'Submit'));
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'App\Entity\Award'
    ));
  }

  public function getName() {
    return 'award';
  }

}

