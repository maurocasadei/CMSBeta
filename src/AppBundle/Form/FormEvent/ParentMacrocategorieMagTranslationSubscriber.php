<?php

namespace AppBundle\Form\FormEvent;

use AppBundle\Entity\MacrocategorieMag;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ParentMacrocategorieMagTranslationSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var MacrocategorieMag
     */
    private $parentMacrocategorieMag;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, MacrocategorieMag $parentMacrocategorieMag)
    {
        $this->em = $em;
        $this->parentMacrocategorieMag = $parentMacrocategorieMag;
    }

    public function onPostSetData(FormEvent $event)
    {
        $macrocategorieMag = $event->getData();

        $macrocategorieMag->setParentMultilangue($this->parentMacrocategorieMag);
        $macrocategorieMag->setParent($this->selectMacrocategorieMagParent($this->parentMacrocategorieMag->getParent(), $macrocategorieMag->getLocale()));
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SET_DATA => 'onPostSetData',
        ];
    }

    private function selectMacrocategorieMagParent($parentMultilangue, $locale)
    {
        return $this->em->getRepository('AppBundle:MacrocategorieMag')
            ->selectMacrocategorieMagParent($parentMultilangue, $locale);
    }
}
