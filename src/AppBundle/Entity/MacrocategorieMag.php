<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * MacrocategorieMag.
 *
 * @ORM\Table(name="macrocategoriemag")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MacrocategorieMagRepository")
 * @UniqueEntity(
 *     fields={"name"},
 *     errorPath="name",
 *     message="This name is already in use"
 * )
 */
class MacrocategorieMag
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
     *
     * @ORM\Column(name="name", type="string", length=80)
     *
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string")
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=5)
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="MacrocategorieMag", mappedBy="parent", cascade={"persist", "remove"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="MacrocategorieMag", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     *
     * @AppAssert\HasTranslationParent()
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="MacrocategorieMag", mappedBy="parentMultilangue", cascade={"persist", "remove"})
     */
    private $childrenMultilangue;

    /**
     * @ORM\ManyToOne(targetEntity="MacrocategorieMag", inversedBy="childrenMultilangue")
     * @ORM\JoinColumn(name="parent_multilangue_id", referencedColumnName="id")
     */
    private $parentMultilangue;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Content", mappedBy="macrocategorieMag")
     */
    private $contents;
    /**
     * @var string
     *
     * @ORM\Column(name="miocampo", type="string", length=200)
     *
     * @Assert\NotBlank()
     */
    private $miocampo;
	
    protected $image;
    public function __construct($locale = null)
    {
        $this->children = new ArrayCollection();
        $this->childrenMultilangue = new ArrayCollection();
        $this->contents = new ArrayCollection();

        $this->setLocale($locale);
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return MacrocategorieMag
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return MacrocategorieMag
     */
    public function setMiocampo($miocampo)
    {
        $this->miocampo = $miocampo;

        return $this;
    }
    /**
     * Get miocampo.
     *
     * @return string
     */
    public function getMiocampo()
    {
        return $this->miocampo;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return MacrocategorieMag
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set locale.
     *
     * @param string $locale
     *
     * @return MacrocategorieMag
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return MacrocategorieMag
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add child.
     *
     * @param \AppBundle\Entity\MacrocategorieMag $child
     *
     * @return MacrocategorieMag
     */
    public function addChild(MacrocategorieMag $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child.
     *
     * @param \AppBundle\Entity\MacrocategorieMag $child
     */
    public function removeChild(MacrocategorieMag $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent.
     *
     * @param \AppBundle\Entity\MacrocategorieMag $parent
     *
     * @return MacrocategorieMag
     */
    public function setParent(MacrocategorieMag $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return \AppBundle\Entity\MacrocategorieMag
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add content.
     *
     * @param \AppBundle\Entity\Content $content
     *
     * @return MacrocategorieMag
     */
    public function addContent(Content $content)
    {
        $this->contents[] = $content;

        return $this;
    }

    /**
     * Remove content.
     *
     * @param \AppBundle\Entity\Content $content
     */
    public function removeContent(Content $content)
    {
        $this->contents->removeElement($content);
    }

    /**
     * Get contents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * Add childrenMultilangue.
     *
     * @param \AppBundle\Entity\MacrocategorieMag $childrenMultilangue
     *
     * @return MacrocategorieMag
     */
    public function addChildrenMultilangue(MacrocategorieMag $childrenMultilangue)
    {
        $this->childrenMultilangue[] = $childrenMultilangue;

        return $this;
    }

    /**
     * Remove childrenMultilangue.
     *
     * @param \AppBundle\Entity\MacrocategorieMag $childrenMultilangue
     */
    public function removeChildrenMultilangue(MacrocategorieMag $childrenMultilangue)
    {
        $this->childrenMultilangue->removeElement($childrenMultilangue);
    }

    /**
     * Get childrenMultilangue.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildrenMultilangue()
    {
        return $this->childrenMultilangue;
    }

    /**
     * Set parentMultilangue.
     *
     * @param \AppBundle\Entity\MacrocategorieMag $parentMultilangue
     *
     * @return MacrocategorieMag
     */
    public function setParentMultilangue(MacrocategorieMag $parentMultilangue = null)
    {
        $this->parentMultilangue = $parentMultilangue;

        return $this;
    }

    /**
     * Get parentMultilangue.
     *
     * @return \AppBundle\Entity\MacrocategorieMag
     */
    public function getParentMultilangue()
    {
        return $this->parentMultilangue;
    }

    public function __toString()
    {
        return $this->getName().' ['.$this->getLocale().']';
    }
    public function getUploadRootDir()
    {
    	// absolute path to your directory where images must be saved
    	return __DIR__.'/../../../../../web/'.$this->getUploadDir();
    }
    
    public function getUploadDir()
    {
    	return 'uploads/myentity';
    }
    
    public function getAbsolutePath()
    {
    	return null === $this->image ? null : $this->getUploadRootDir().'/'.$this->image;
    }
    
    public function getWebPath()
    {
    	return null === $this->image ? null : '/'.$this->getUploadDir().'/'.$this->image;
    }
    
}
