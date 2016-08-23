<?php

namespace Dim\TodoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Stoke
 *
 * @ORM\Table(name="stoke")
 * @ORM\Entity(repositoryClass="Dim\TodoBundle\Repository\StokeRepository")
 */
class Stoke {
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
	 *
	 * @ORM\Column(name="name", type="string", length=255)
	 */
	private $name;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="count", type="integer")
	 *
	 * @Assert\Range(
	 *      min = 0,
	 *      minMessage = "Значение не может быть отрицательным"
	 * )
	 */
	private $count;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="date", type="datetime")
	 */
	private $date;

	/**
	 * @var int
	 *
	 *
	 * @ORM\ManyToOne(targetEntity="User")
	 * @ORM\JoinColumn(referencedColumnName="id")
	 */
	protected $userId;

	public function __construct() {
		$this->date = new \DateTime();
	}

	/**
	 * Get id
	 *
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return Stoke
	 */
	public function setName($name) {
		$this->name = $name;

		return $this;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set count
	 *
	 * @param integer $count
	 *
	 * @return Stoke
	 */
	public function setCount($count) {
		$this->count = $count;

		return $this;
	}

	/**
	 * Get count
	 *
	 * @return int
	 */
	public function getCount() {
		return $this->count;
	}

	/**
	 * Set date
	 *
	 * @param \DateTime $date
	 *
	 * @return Stoke
	 */
	public function setDate($date) {
		$this->date = $date;

		return $this;
	}

	/**
	 * Get date
	 *
	 * @return \DateTime
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * Set userId
	 *
	 * @param integer $userId
	 *
	 * @return Stoke
	 */
	public function setUserId($userId) {
		$this->userId = $userId;

		return $this;
	}

	/**
	 * Get userId
	 *
	 * @return int
	 */
	public function getUserId() {
		return $this->userId;
	}
}
