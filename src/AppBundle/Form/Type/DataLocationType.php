<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form builder for Data Location
 */
class DataLocationType extends AbstractType {

  /**
   * Build the form 
   *
   * @param FormBuilderInterface
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('data_location','text',array(
      'label'=>false,
      'required'=>false,
      'attr'=>array('placeholder'=>'Data Location'))
      );
    $builder->add('location_content','text',array(
      'label'=>false,
      'required'=>false,
      'attr'=>array('placeholder'=>'Location Content'))
      );
    $builder->add('data_access_url','text',array(
      'label'=>false,
      'required'=>false,
      'attr'=>array('placeholder'=>'Location URL'))
      );
  }

  /**
   * Set defaults
   *
   * @param OptionsResolverInterface
   */
  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'AppBundle\Entity\DataLocation'
    ));
  }

  public function getName() {
    return 'dataLocation';
  }

}

