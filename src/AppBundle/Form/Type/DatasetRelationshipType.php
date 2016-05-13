<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form builder for Dataset Relationship entry
 */
class DatasetRelationshipType extends AbstractType {

  /**
   * Build the form
   *
   * @param FormBuilderInterface
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('related_dataset_uid','text',array(
      'label'=>false,
      'attr'=>array('placeholder'=>'Related Dataset UID'))
      );
    $builder->add('relationship_attributes','choice',array(
      'label'=>false,
      'required'=>false,
      'attr'=>array('data-placeholder'=>'Relationship'),
      'choices'=>array('Superceded by'=>'Superceded by',
                       'Preceded by'  =>'Preceded by',
                       'Derived from'  =>'Derived from',
                       'Transformed to'  =>'Transformed to',
                       'Same publisher as'  =>'Same publisher as',
                       'Linkage dataset between'  =>'Linkage dataset between'),
        )
      );
    $builder->add('relationship_notes','text',array(
      'label'=>false,
      'required'=>false,
      'attr'=>array('placeholder'=>'Relationship Notes'))
      );
  }

  /**
   * Set defaults
   *
   * @param OptionsResolverInterface
   */
  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'AppBundle\Entity\DatasetRelationship'
    ));
  }

  public function getName() {
    return 'datasetRelationship';
  }

}

