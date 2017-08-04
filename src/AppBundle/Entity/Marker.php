<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Marker
 *
 * @ORM\Table(name="marker")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MarkerRepository")
 */
class Marker
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="coord_x", type="decimal", precision=10, scale=7)
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="double"
     * )
     */
    private $coordX;

    /**
     * @var string
     * @ORM\Column(name="coord_y", type="decimal", precision=10, scale=7)
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="double",
     * )
     */
    private $coordY;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string"
     * )
     */
    private $name;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set coordX
     *
     * @param string $coordX
     *
     * @return Marker
     */
    public function setCoordX($coordX)
    {
        $this->coordX = $coordX;

        return $this;
    }

    /**
     * Get coordX
     *
     * @return string
     */
    public function getCoordX()
    {
        return $this->coordX;
    }

    /**
     * Set coordY
     *
     * @param string $coordY
     *
     * @return Marker
     */
    public function setCoordY($coordY)
    {
        $this->coordY = $coordY;

        return $this;
    }

    /**
     * Get coordY
     *
     * @return string
     */
    public function getCoordY()
    {
        return $this->coordY;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Marker
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Marker
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

