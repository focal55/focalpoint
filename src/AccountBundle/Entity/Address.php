<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/** @ORM\Embeddable */
class Address
{
    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $streetNumber;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $streetName;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $line1;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $subpremise;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $subLocality;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $locality;

    /**
     * @ORM\Column(name="admin_level1_code", type="string", length=64, nullable=true)
     */
    protected $adminLevel1Code;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $adminLevel1;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $adminLevel2Code;

    /**
     * @ORM\Column(name="admin_level2_code", type="string", length=128, nullable=true)
     */
    protected $adminLevel2;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $adminLevel3;

    /**
     * @ORM\Column(name="admin_level3_code", type="string", length=64, nullable=true)
     */
    protected $adminLevel3Code;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    protected $postalCode;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $country;

    /**
     * @Assert\Country()
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    protected $countryCode;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $formattedAddress;

    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    public function setStreetNumber($streetNumber)
    {
        $this->streetNumber = $streetNumber;
        $this->setLine1($this->streetNumber . ' ' . $this->streetName);
    }

    public function getStreetName()
    {
        return $this->streetName;
    }

    public function setStreetName($streetName)
    {
        $this->streetName = $streetName;
        $this->setLine1($this->streetNumber . ' ' . $this->streetName);
    }

    public function getLine1()
    {
        return $this->line1;
    }

    public function setLine1($line1)
    {
        $this->line1 = $line1;
    }

    public function getSubpremise()
    {
        return $this->subpremise;
    }

    public function setSubpremise($subpremise)
    {
        $this->subpremise = $subpremise;
    }

    public function getSubLocality()
    {
        return $this->subLocality;
    }

    public function setSubLocality($subLocality)
    {
        $this->subLocality = $subLocality;
    }

    public function getLocality()
    {
        return $this->locality;
    }

    public function setLocality($locality)
    {
        $this->locality = $locality;
    }

    public function getAdminLevel1()
    {
        return $this->adminLevel1;
    }

    public function setAdminLevel1($adminLevel1)
    {
        $this->adminLevel1 = $adminLevel1;
    }

    public function getAdminLevel1Code()
    {
        return $this->adminLevel1Code;
    }

    public function setAdminLevel1Code($adminLevel1Code)
    {
        $this->adminLevel1Code = $adminLevel1Code;
    }

    public function getAdminLevel2()
    {
        return $this->adminLevel2;
    }

    public function setAdminLevel2($adminLevel2)
    {
        $this->adminLevel2 = $adminLevel2;
    }

    public function getAdminLevel2Code()
    {
        return $this->adminLevel2Code;
    }

    public function setAdminLevel2Code($adminLevel2Code)
    {
        $this->adminLevel2Code = $adminLevel2Code;
    }

    public function getAdminLevel3()
    {
        return $this->adminLevel3;
    }

    public function setAdminLevel3($adminLevel3)
    {
        $this->adminLevel3 = $adminLevel3;
    }

    public function getAdminLevel3Code()
    {
        return $this->adminLevel3Code;
    }

    public function setAdminLevel3Code($adminLevel3Code)
    {
        $this->adminLevel3Code = $adminLevel3Code;
    }

    public function getPostalCode()
    {
        return $this->postalCode;
    }

    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function getCountryCode()
    {
        return $this->countryCode;
    }

    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }

    public function getFormattedAddress()
    {
        return $this->formattedAddress;
    }

    public function setFormattedAddress($formattedAddress)
    {
        $this->formattedAddress = $formattedAddress;
    }

    public function buildFormattedAddress()
    {
        $addr = $this->line1;
        if (!empty($this->subpremise)) {
            $addr .= ' '.$this->subpremise;
        }
        $addr .= ', '.$this->locality;
        $addr .= ', '.$this->adminLevel1Code;
        $addr .= ' '.$this->postalCode;
        if (isset($this->countryCode)) {
            $addr .= ', '.$this->countryCode;
        }
        $this->formattedAddress = $addr;
    }

    public function getMarkupAddress()
    {
        $addr = '<div class="address">';
        $addr .='<div class="address-street">' . $this->line1;
        if (!empty($this->subpremise)) {
            $addr .= ' ' . $this->subpremise;
        }
        $addr .= '</div><div class="locality">' . $this->locality;
        $addr .= ', ' . $this->adminLevel1Code;
        $addr .= ' ' . $this->postalCode;
        $addr .= ', ' . $this->countryCode . '</div></div>';
        return $addr;
    }
}
