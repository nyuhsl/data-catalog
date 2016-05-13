<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form builder for Data Location URL
 */
class DataLocationURLType extends AbstractType {

  /** 
   * Build the form·
   *
   * @param FormBuilderInterface
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('data_access_url','text',array(
      'label'=>false,
      'attr'=>array('placeholder'=>'Data Location URL'))
      );
  }

  /**
   * Set defaults
   *
   * @param OptionsResolverInterface
   */
  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'AppBundle\Entity\DataLocationURL'
    ));
  }

  public function getName() {
    return 'dataLocationURL';
  }

}

