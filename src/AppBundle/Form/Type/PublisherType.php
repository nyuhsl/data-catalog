<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form builder for Publisher entry
 */
class PublisherType extends AbstractType {

  /**
   * Build form
   *
   * @param FormBuilderInterface
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('publisher_name');
    $builder->add('publisher_url');
    $builder->add('publisher_categories','entity',array(
      'class'   => 'AppBundle:PublisherCategory',
      'property'=> 'publisherCategory',
      'multiple'=> true,
      'expanded'=> true,
      'label'   => 'Publisher Type'
    ));
    $builder->add('save','submit',array('label'=>'Submit'));
  }

  /**
   * Set default
   *
   * @param OptionsResolverInterface
   */
  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'AppBundle\Entity\Publisher'
    ));
  }

  public function getName() {
    return 'publisher';
  }

}

