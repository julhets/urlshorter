<?php

namespace Urlshorter\ValueObject;

use Respect\Validation\Validator as v;

/**
 * @Entity @Table(name="user")
 **/
class User implements \JsonSerializable
{
  /** @Id @Column(type="integer") * */
  protected $id = null;
  /**
   * @OneToMany(targetEntity="Url", mappedBy="user")
   * @OrderBy({"hits" = "DESC"})
   */
  protected $urls;

  public function __construct()
  {
    $this->urls = new \Doctrine\Common\Collections\ArrayCollection();
  }

  public function bind(array $data)
  {
    $this->id = isset($data['id']) ? $data['id'] : null;
  }

  public function __clone()
  {
  }

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param mixed $id
   */
  public function setId($id)
  {
    $this->id = $id;
  }

  /**
   * @return mixed
   */
  public function getUrls()
  {
    return $this->urls;
  }

  /**
   * @param mixed $urls
   */
  public function setUrls($urls)
  {
    $this->urls = $urls;
  }

  public function assert()
  {
    return v::numeric()
        ->assert($this->getId());
  }

  public function jsonSerialize()
  {
    $properties = get_object_vars($this);
    $properties['id'] = $this->getId();

    return $properties;
  }
}
