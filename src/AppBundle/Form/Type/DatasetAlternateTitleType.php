<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form builder for Alternate Title entity
 */
class DatasetAlternateTitleType extends AbstractType {

  /**
   * Build the form
   *
   * @param FormBuilderInterface
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('alt_title','text',array(
      'label'=>false,
      'attr'=>array('placeholder'=>'Alternate Title'))
      );
  }

  /**
   * Set defaults
   *
   * @param OptionsResolverInterface
   */
  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'AppBundle\Entity\DatasetAlternateTitle'
    ));
  }

  public function getName() {
    return 'datasetAlternateTitle';
  }

}

