<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * Form builder for the Access Restriction entity
 */
class AccessRestrictionType extends AbstractType {

  /**
   * Build the form
   *
   * @param FormBuilderInterface
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('restriction');
    $builder->add('save','submit',array('label'=>'Submit'));
  }

  /**
   * Set default options
   *
   * @param OptionsResolverInterface
   */
  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver
      ->setDefaults(array('data_class' => 'AppBundle\Entity\AccessRestriction'));
  }

  public function getName() {
    return 'accessRestriction';
  }

}

