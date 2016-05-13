<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\Security\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/** 
 * Form builder for User entry
 */
class UserType extends AbstractType {

  /**
   * Build form
   *
   * @param FormBuilderInterface
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('username');
    $builder->add('firstName');
    $builder->add('lastName');
    $builder->add('roles', 'entity', array(
      'required'=>true,
      'class'   =>'AppBundle\Entity\Security\Role',
      'property'=>'name',
      'multiple'=>true,
      'label'   => 'Website role',
      'expanded'=>true,
    ));

    $builder->add('save', 'submit');
  }

  /**
   * Set defaults
   *
   * @param OptionsResolverInterface
   */
  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'AppBundle\Entity\Security\User'
    ));
  }

  public function getName() {
    return 'user';
  }

}

