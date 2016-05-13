<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form builder for Contact Us form
 */
class ContactFormEmailType extends AbstractType {

  /**
   * Build the form
   *
   * @param FormBuilderInterface
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('employee_id', 'text', array(
      'label'=> 'Employee ID',
      'label_attr'=>array('class'=>'no-asterisk'),
    ));
    $builder->add('full_name', 'text', array(
      'label_attr'=>array('class'=>'no-asterisk')));
    $builder->add('email_address', 'email', array(
      'label_attr'=>array('class'=>'no-asterisk')));
    $builder->add('affiliation', 'choice', array(
      'label'=>'Institutional Affiliation',
      'label_attr'=>array('class'=>'no-asterisk'),
      'choices' => array(
        'Set these options' => 'Set these options',
        'in the file' => 'in the file',
        'src/AppBundle/Form/Type/ContactFormEmailType' => 'src/AppBundle/Form/Type/ContactFormEmailType',
        )));
       
    $builder->add('reason', 'choice', array(
      'expanded'=>true,
      'label_attr'=>array('class'=>'no-asterisk'),
      'choices' =>array(
        'Volunteer as a local expert' => 'Volunteer as a local expert',
        'Suggest a new dataset' => 'Suggest a new dataset',
        'Request uploading of dataset' => 'Request uploading of your dataset(s)',
        'General inquiry'    => 'General inquiry or comments',
      ),
      'multiple'=>false)
    );
    $builder->add('message_body', 'textarea', array(
      'attr' => array('rows'=>'5'),
      'label_attr'=>array('class'=>'no-asterisk'),
      'label'=>'Please provide some details about your question/comment',
    ));
    $builder->add('checker', 'text', array(
      'required'=>false,
      'attr'=>array('class'=>'checker'),
      'label_attr'=>array('class'=>'no-asterisk checker')));
    $builder->add('save','submit',array('label'=>'Send'));
  }

  /**
   * Set defaults
   *
   * @param OptionsResolverInterface
   */
  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'AppBundle\Entity\ContactFormEmail'
    ));
  }

  public function getName() {
    return 'contactFormEmail';
  }

}

