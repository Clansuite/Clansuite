<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Repositories\StaticpagesRepository")
 * @Table(name="staticpages")
 */
class Staticpages
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(type="string")
     */
    protected $title;

    /**
     * @Column(type="string")
     */
    protected $description;

    /**
     * @Column(type="string")
     */
    protected $url;

    /**
     * @Column(type="string")
     */
    protected $html;

    /**
     * @Column(type="string")
     */
    protected $iframe;

    /**
     * @Column(type="integer")
     */
    protected $iframe_height;

    /**
     * @var datetime $createdAt
     */
    protected $createdAt;

    /**
     * @var datetime $publishedAt
     */
    protected $publishedAt;

    /**
     * @var datetime $updatedAt
     */
    protected $updatedAt;

    /**
     * @var string $slug
     */
    protected $slug;

    public function __construct()
    {
        $this->createdAt = $this->updatedAt = new \DateTime('now');
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getHtml()
    {
        return $this->html;
    }

    public function setHtml($html)
    {
        $this->html = $html;
    }

    public function getIframe()
    {
        return $this->iframe;
    }

    public function setIframe($iframe)
    {
        $this->iframe = $iframe;
    }

    public function getIframe_height()
    {
        return $this->iframe_height;
    }

    public function setIframe_height($iframe_height)
    {
        $this->iframe_height = $iframe_height;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
}
?>