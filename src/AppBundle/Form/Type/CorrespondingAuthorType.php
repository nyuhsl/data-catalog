<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CorrespondingAuthorType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('full_name');
    $builder->add('kid');
    $builder->add('orcid_id', 'text', array(
      'required' => false,
      'label'    => 'ORCID ID',
    ));
    $builder->add('bio_url');
    $builder->add('save','submit',array('label'=>'Submit'));
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'AppBundle\Entity\Person'
    ));
  }

  public function getName() {
    return 'correspondingAuthor';
  }

}

