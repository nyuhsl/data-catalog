<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AwardType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('award','text',array(
      'label'=>'Grant Number',
    ));
    $builder->add('award_funder','text',array(
      'label'=>'Funding Agency',
    ));
    $builder->add('funder_type','choice',array(
      'label'=>'Funder Type',
      'choices'=>array('Federal, NIH'    => 'Federal, NIH',
                       'Federal, non-NIH'=> 'Federal, non-NIH',
                       'Non-federal'     => 'Non-federal'),
    ));
    $builder->add('award_url', 'text',array(
      'required'=>false,
      'label'=>'NIH Reporter URL',
    ));
    $builder->add('save','submit',array('label'=>'Submit'));
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'AppBundle\Entity\Award'
    ));
  }

  public function getName() {
    return 'award';
  }

}

