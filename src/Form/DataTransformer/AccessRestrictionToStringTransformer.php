<?php
namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\AccessRestriction;

/**
 * Converts AccessRestriction object to string and back
 *
 *   This file is part of the Data Catalog project.
 *   Copyright (C) 2016 NYU Health Sciences Library
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
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
      ->getRepository('App:AccessRestriction')
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
