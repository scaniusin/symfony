<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\BlogPostRepository")
 * @ORM\Table(name="blog_post")
 * @JMSSerializer\ExclusionPolicy("all")
 */
class BlogPost implements \JsonSerializable
{
  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   * @JMSSerializer\Expose
   */
  protected $id;
  /**
   * @ORM\Column(type="string", name="title")
   * @JMSSerializer\Expose
   */
  private $title;
  /**
   * @ORM\Column(type="string", name="body")
   * @JMSSerializer\Expose
   */
  private $body;
  /**
   * @ORM\Column(type="integer", name="uid", nullable=FALSE)
   * @JMSSerializer\Expose
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="blogpost")
   * @ORM\JoinColumn(name="uid", referencedColumnName="id")
   */
  private $uid;

  /**
   * @var string
   * @JMSSerializer\Expose
   */
  private $author;

  /**
   * @return mixed
   */
  public function getAuthor()
  {
    return $this->author;
  }
  /**
   * @param mixed author
   * @return BlogPost
   */
  public function setAuthor($author)
  {
    $this->author = $author;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getUid()
  {
    return $this->uid;
  }

  /**
   * @param mixed $uid
   */
  public function setUid($uid)
  {
    $this->uid = $uid;
  }
  /**
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }
  /**
   * @return mixed
   */
  public function getTitle()
  {
    return $this->title;
  }
  /**
   * @param mixed $title
   * @return BlogPost
   */
  public function setTitle($title)
  {
    $this->title = $title;
    return $this;
  }
  /**
   * @return mixed
   */
  public function getBody()
  {
    return $this->body;
  }
  /**
   * @param mixed $body
   * @return BlogPost
   */
  public function setBody($body)
  {
    $this->body = $body;
    return $this;
  }
  /**
   * @return mixed
   */
  function jsonSerialize()
  {
    return [
      'id'    => $this->id,
      'title' => $this->title,
      'body'  => $this->body,
    ];
  }
}