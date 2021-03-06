<?php

namespace AppBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Asset\Packages;

/**
 * Class AppAdminExtension
 *
 * All the twig functions enabled in the administration
 * - Check if a category has a translation
 * - Check if a content has a translation
 * - Check if a navigation has a translation
 * - Rendering a file
 *
 * @package AppBundle\Twig
 */
class AppAdminExtension extends \Twig_Extension
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Packages
     */
    private $packages;

    /**
     * @var string
     */
    private $uploads_directory_name;

    /**
     * @param EntityManager $em
     * @param Packages $packages
     * @param $uploads_directory_name
     */
    public function __construct(EntityManager $em, Packages $packages, $uploads_directory_name)
    {
        $this->em = $em;
        $this->packages = $packages;
        $this->uploads_directory_name = $uploads_directory_name;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('is_category_translation', array($this, 'isCategoryTranslation')),
            new \Twig_SimpleFunction('is_content_translation', array($this, 'isContentTranslation')),
            new \Twig_SimpleFunction('is_nav_translation', array($this, 'isNavTranslation')),
            new \Twig_SimpleFunction('render_file', [$this, 'render_file'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('is_macrocategorieMag_translation', array($this, 'isMacrocategorieMagTranslation')),
        );
    }

    /**
     * Check if a category has a translation
     *
     * @param $localeDefault
     * @param $localeCategory
     * @param $parentMultilangue
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function isCategoryTranslation($localeDefault, $localeCategory, $parentMultilangue)
    {
        return $this->em->getRepository('AppBundle:Category')->createQueryBuilder('c')
            ->leftJoin('c.parentMultilangue', 't')
            ->andWhere('c.locale != :localeDefault and c.locale = :localeCategory and t.id = :parentMultilangue')
            ->setParameter('localeDefault', $localeDefault)
            ->setParameter('localeCategory', $localeCategory)
            ->setParameter('parentMultilangue', $parentMultilangue)
            ->getQuery()
            ->getOneOrNullResult();
    }
    /**
     * Check if a MacrocategorieMag has a translation
     *
     * @param $localeDefault
     * @param $localeMacrocategorieMag
     * @param $parentMultilangue
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function isMacrocategorieMagTranslation($localeDefault, $localeMacrocategorieMag, $parentMultilangue)
    {
        return $this->em->getRepository('AppBundle:MacrocategorieMag')->createQueryBuilder('c')
            ->leftJoin('c.parentMultilangue', 't')
            ->andWhere('c.locale != :localeDefault and c.locale = :localeMacrocategorieMag and t.id = :parentMultilangue')
            ->setParameter('localeDefault', $localeDefault)
            ->setParameter('localeMacrocategorieMag', $localeMacrocategorieMag)
            ->setParameter('parentMultilangue', $parentMultilangue)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Check if a content has a translation
     *
     * @param $localeDefault
     * @param $localeContent
     * @param $parentMultilangue
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function isContentTranslation($localeDefault, $localeContent, $parentMultilangue)
    {
        return $this->em->getRepository('AppBundle:Content')->createQueryBuilder('c')
            ->leftJoin('c.parentMultilangue', 't')
            ->andWhere('c.locale != :localeDefault and c.locale = :localeContent and t.id = :parentMultilangue')
            ->setParameter('localeDefault', $localeDefault)
            ->setParameter('localeContent', $localeContent)
            ->setParameter('parentMultilangue', $parentMultilangue)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Check if a navigation has a translation
     *
     * @param $localeDefault
     * @param $localeContent
     * @param $parentMultilangue
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function isNavTranslation($localeDefault, $localeContent, $parentMultilangue)
    {
        return $this->em->getRepository('AppBundle:Nav')->createQueryBuilder('n')
            ->leftJoin('n.parentMultilangue', 't')
            ->andWhere('n.locale != :localeDefault and n.locale = :localeContent and t.id = :parentMultilangue')
            ->setParameter('localeDefault', $localeDefault)
            ->setParameter('localeContent', $localeContent)
            ->setParameter('parentMultilangue', $parentMultilangue)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Rendering a file
     *
     * @param $filename
     * @param $extension
     * @return string
     */
    public function render_file($filename, $extension)
    {
        $img = ['png', 'jpg', 'jpeg', 'gif', 'svg', 'pjpeg'];

        $path = $this->uploads_directory_name . '/' . $filename;

        if (in_array($extension, $img)) {
            return '<img src="' . $this->packages->getUrl($path) . '" height="42">';
        }

        return '<a href="' . $this->packages->getUrl($path) . '" download><i class="fa fa-download"></i></a>';
    }

    public function getName()
    {
        return 'app_admin.twig.extension';
    }
}
