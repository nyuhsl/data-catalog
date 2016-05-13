<?php
namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\AccessRestriction;

/**
 * Converts AccessRestriction object to string and back
 */
class AccessRestrictionToStringTransformer implements DataTransformerInterface {
  
  /**
   * @var ObjectManager
   */
  private $om;

  
  /**
   * @param ObjectManager $om
   */
  public function __construct(ObjectManager $om) {
    $this->om = $om;
  }  


  /**
   * Transforms an AccessRestriction object to a string (the restriction)
   *
   * @param AccessRestriction|null $accessRestriction
   * @return string
   */
  public function transform($accessRestriction) {
    if (null === $accessRestriction) {
      return "";
    }
    $options = array();
    foreach ($accessRestriction as $restriction) {
      $options[] = $restriction->getRestriction();
    }
    $opts = implode(",", $options);
    return $opts;
  }


  /**
   * Transforms a string (the restriction) to an object (AccessRestriction)
   *
   * @param string $restriction
   * @return AccessRestriction|null
   * @throws TransformationFailedException if object (restriction) is not found
   */
  public function reverseTransform($restriction) {
    if (!$restriction) {
      return null;
    }

    $issue = $this->om
      ->getRepository('AppBundle:AccessRestriction')
      ->findOneBy(array('restriction'=>$restriction));

    if (null === $restriction) {
      throw new TransformationFailedException(sprintf(
        'The restriction "%s" does not exist',
        $restriction
      ));
    }

    return $restriction;
  }

}
