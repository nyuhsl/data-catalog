<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;

/**
 * Entity to describe emails submitted via the contact form
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
 *
 *
 * @ORM\Entity
 * @ORM\Table(name="submitdatasetform_emails")
 */
class SubmitDatasetFormEmail {
  /**
   * @ORM\Column(type="integer",name="submitdataset_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /** 
   * @Recaptcha\IsTrue
   */
  public $recaptcha;



  /**
   * @ORM\Column(type="string",length=128,nullable=true)
   */
  protected $school_center;

  /**
   * @ORM\Column(type="string",length=128,nullable=true)
   */
  protected $department;

  /**
   * @ORM\Column(type="string",length=128,nullable=true)
   */
  protected $full_name;

  /**
   * @ORM\Column(type="string",length=128)
   */
  protected $email_address;

  /**
   * @ORM\Column(type="string",length=32,nullable=true)
   */
  protected $phone_number;

  /**
   * @ORM\Column(type="string",length=1024,nullable=true)
   */
  protected $dataset_url;

  /**
   * @ORM\Column(type="string",length=8192,nullable=true)
   */
  protected $details;

  /**
   * @ORM\Column(type="string",length=8192, nullable=true)
   */
  protected $comments;

  /**
   * @ORM\Column(type="string",length=128,nullable=true)
   * @Assert\Blank()
   */
  protected $checker;



  /**
   * Get name for display
   *
   * @return string
   */
  public function getDisplayName() {
    return $this->full_name;
  }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set full_name
     *
     * @param string $fullName
     * @return SubmitDatasetFormEmail
     */
    public function setFullName($fullName)
    {
        $this->full_name = $fullName;

        return $this;
    }

    /**
     * Get full_name
     *
     * @return string 
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * Set email_address
     *
     * @param string $emailAddress
     * @return SubmitDatasetFormEmail
     */
    public function setEmailAddress($emailAddress)
    {
        $this->email_address = $emailAddress;

        return $this;
    }

    /**
     * Get email_address
     *
     * @return string 
     */
    public function getEmailAddress()
    {
        return $this->email_address;
    }



    /**
     * Set phone_number
     *
     * @param string $phoneNumber
     * @return SubmitDatasetFormEmail
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phone_number = $phoneNumber;

        return $this;
    }

    /**
     * Get email_address
     *
     * @return string 
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }


    /**
     * Set school_center
     *
     * @param string $school_center
     * @return SubmitDatasetFormEmail
     */
    public function setSchoolCenter($school_center)
    {
        $this->school_center = $school_center;

        return $this;
    }

    /**
     * Get school_center
     *
     * @return string 
     */
    public function getSchoolCenter()
    {
        return $this->school_center;
    }

   /**
     * Set department
     *
     * @param string $department
     * @return SubmitDatasetFormEmail
     */
    public function setDepartment($department)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return string 
     */
    public function getDepartment()
    {
        return $this->department;
    }
	
	
  /**
     * Set dataset_url
     *
     * @param string $datasetUrl
     * @return SubmitDatasetFormEmail
     */
    public function setDatasetUrl($datasetUrl)
    {
        $this->dataset_url = $datasetUrl;

        return $this;
    }

    /**
     * Get dataset_url
     *
     * @return string 
     */
    public function getDatasetUrl()
    {
        return $this->dataset_url;
    }

	
    /**
     * Set details
     *
     * @param string $details
     * @return SubmitDatasetFormEmail
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return string 
     */
    public function getDetails()
    {
        return $this->details;
    }


    /**
     * Set comments
     *
     * @param string $comments
     * @return SubmitDatasetFormEmail
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string 
     */
    public function getComments()
    {
        return $this->comments;
    }



    /**
     * Set checker
     *
     * @param string $checker
     * @return SubmitDatasetFormEmail
     */
    public function setChecker($checker)
    {
        $this->checker = $checker;

        return $this;
    }

    /**
     * Get checker
     *
     * @return string 
     */
    public function getChecker()
    {
        return $this->checker;
    }

}
