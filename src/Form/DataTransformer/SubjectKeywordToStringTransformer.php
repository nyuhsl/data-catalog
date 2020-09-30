<?php
namespace App\Form\DataTransformer;

use Doctrine\ORM\PersistentCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use App\Entity\SubjectKeyword;
use Doctrine\ORM\EntityManager;

/**
 * Converts SubjectKeyword object to string and back
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
class SubjectKeywordToStringTransformer implements DataTransformerInterface {
  
  /**
   * @var EntityManager
   */
  private $em;

  
  /**
   * @param EntityManager $em
   */
  public function __construct(EntityManager $em) {
    $this->em = $em;
  }  


  /**
   * Transforms a SubjectKeyword object to a string
   *
   * @param array $array
   * @return string
   */
  public function transform($array) {
    if (null === $array) {
      return "";
    }
    /*if (!($array instanceof PersistentCollection)) {
      return new ArrayCollection();
    }*/
    
    $options = array();
    foreach ($array as $key=>$subjectKeyword) {
      $options[] = $subjectKeyword->getKeyword();
    }
    return new ArrayCollection($options);
    //return $options;
    /*for ($i=1; $i<=count($subjectKeyword); $i++) {
      $subjectKeyword[$i] = $subjectKeyword[$i]->getKeyword();
    }
    return $subjectKeyword;*/

  }


  /**
   * Transforms a string (the keyword) to an object (SubjectKeyword)
   *
   * @param string $keyword
   * @return SubjectKeyword|null
   * @throws TransformationFailedException if object is not found
   */
  public function reverseTransform($array) {
    if (!$array) {
      return null;
    }
    $keywords = array();
    foreach ($array as $key=>$value) {
      $keyword = $this->em
        ->getRepository('App:SubjectKeyword')
        ->findOneBy(array('keyword'=>$value));
      if (!is_null($keyword)) {
        $keywords[$key] = $keyword;
      }
    }

   /* if (null === $subjectKeyword) {
      throw new TransformationFailedException(sprintf(
        'The SubjectKeyword "%s" does not exist',
        $keyword
      ));
   }*/

    return new PersistentCollection($this->em, 'App:SubjectKeyword', new ArrayCollection($keywords));
  }

}
