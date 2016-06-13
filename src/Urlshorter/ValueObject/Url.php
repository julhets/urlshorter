<?php

namespace Urlshorter\ValueObject;

use Respect\Validation\Validator as v;

/**
 * @Entity @Table(name="url")
 **/
class Url implements \JsonSerializable
{
  /**
   * @Id
   * @Column(type="integer")
   * @GeneratedValue
   */
  protected $id = null;
  /**
   * @Column(type="integer")
   */
  protected $hits;
  /**
   * @Column(type="string", length=255)
   */
  protected $url;
  /**
   * @Column(type="string", length=100)
   */
  protected $shortUrl;
  /**
   * @ManyToOne(targetEntity="User", inversedBy="urls")
   * @JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
   */
  protected $user;

  protected $basePath;

  public function bind(array $data)
  {
    $this->url = $data['url'];
    if ($this->url && ((strpos($this->url, 'http://') === false) || (strpos($this->url, 'https://') === false))) {
      $this->url = 'http://' . $this->url;
    }
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
  public function getHits()
  {
    return $this->hits;
  }

  /**
   * @param mixed $hits
   */
  public function setHits($hits)
  {
    $this->hits = $hits;
  }

  /**
   * @return mixed
   */
  public function getUrl()
  {
    return $this->url;
  }

  /**
   * @param mixed $url
   */
  public function setUrl($url)
  {
    $this->url = $url;
  }

  /**
   * @return mixed
   */
  public function getShortUrl()
  {
    return $this->shortUrl;
  }

  public function getFormatedShortUrl()
  {
    return $this->getBasePath() . '/' . $this->getShortUrl();
  }

  /**
   * @return mixed
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * @param mixed $user
   */
  public function setUser($user)
  {
    $this->user = $user;
  }

  /**
   * @param mixed $shortUrl
   */
  public function setShortUrl($shortUrl)
  {
    $this->shortUrl = $shortUrl;
  }

  /**
   * @return mixed
   */
  public function getBasePath()
  {
    return $this->basePath;
  }

  /**
   * @param mixed $basePath
   */
  public function setBasePath($basePath)
  {
    $this->basePath = $basePath;
  }

  public function assert()
  {
    v::notEmpty()
        ->setTemplate('{{name}} Invalid Short Url!')
        ->assert($this->getShortUrl());
  }

  public function jsonSerialize()
  {
    $properties = get_object_vars($this);
    $properties['user'] = $this->getUser()->getId();
    $properties['shortUrl'] = $this->getFormatedShortUrl();
    unset($properties['basePath']);

    return $properties;
  }
}
