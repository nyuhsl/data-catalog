<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form builder for Other Resource entry
 */
class OtherResourceType extends AbstractType {

  /**
   * Build the form
   *
   * @param FormBuilderInterface
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('resource_name','text',array(
      'label'=>false,
      'attr'=>array('placeholder'=>'Resource Name/Type'))
      );
    $builder->add('resource_description','text',array(
      'label'=>false,
      'attr'=>array('placeholder'=>'Resource Description'))
      );
    $builder->add('resource_url','text',array(
      'label'=>false,
      'attr'=>array('placeholder'=>'Resource URL'))
      );
  }

  /**
   * Set defaults
   *
   * @param OptionsResolverInterface
   */
  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'AppBundle\Entity\OtherResource'
    ));
  }

  public function getName() {
    return 'OtherResource';
  }

}

