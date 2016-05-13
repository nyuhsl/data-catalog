<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form builder for Person Association entry
 */
class PersonAssociationType extends AbstractType {
  /**
   * Build the form
   *
   * @param FormBuilderInterface
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('role', 'choice', array(
      'label'     => false,
      'choices' => array('Local Expert'=>'Local Expert',
                         'Author'=>'Author'),
    ));
    $builder->add('person', 'entity', array(
      'class'   => 'AppBundle:Person',
      'property'=> 'full_name',
      'multiple'=> false,
      'label'   => false,
    ));

  }

  public function getName() {
    return 'personAssociation';
  }

  /**
   * Set defaults
   *
   * @param OptionsResolverInterface
   */
  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver->setDefaults(array(
      'data_class'  => 'AppBundle\Entity\PersonAssociation',
    ));
  }

}
