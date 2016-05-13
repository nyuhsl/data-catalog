<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form builder for Publication entry
 */
class PublicationType extends AbstractType {

  /**
   * Build form
   *
   * @param FormBuilderInterface
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('citation');
    $builder->add('url', 'text', array(
     'label'=>'URL'));
    $builder->add('doi', 'text',array(
     'required'=>false,
     'label'   => 'DOI'));
    $builder->add('save','submit',array('label'=>'Submit'));
  }

  /**
   * Set defaults
   *
   * @param OptionsResolverInterface
   */
  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'AppBundle\Entity\Publication'
    ));
  }

  public function getName() {
    return 'publication';
  }

}

