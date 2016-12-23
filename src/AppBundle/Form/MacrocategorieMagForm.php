<?php

namespace AppBundle\Form;

use AppBundle\Form\FormEvent\ParentMacrocategorieMagTranslationSubscriber;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\MacrocategorieMag;

/**
 * Class MacrocategorieMagForm
 * @package AppBundle\Form
 */
class MacrocategorieMagForm extends AbstractType
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var string
     */
    private $localeActive;

    /**
     * @var MacrocategorieMag
     */
    private $parent;

    /**
     * @param EntityManager $em
     * @param $localeActive
     */
    public function __construct(EntityManager $em, $localeActive)
    {
        $this->em = $em;
        $this->localeActive = $localeActive;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->parent = $options['parent'];

        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'macrocategorieMag.name',
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'macrocategorieMag.description',
					"attr" => array("class"=>"tinymce"),
                ]
            )
            ->add(
                'slug',
                TextareaType::class,
                [
                    'label' => 'macrocategorieMag.slug',
					"attr" => array("class"=>"tinymce"),
                ]
            )
            ->add(
                'miocampo',
                TextareaType::class,
                [
                    'label' => 'macrocategorieMag.miocampo',
					"attr" => array("class"=>"tinymce"),
                ]
            )
            /*
            ->add('image', 'comur_image', array(
            		'uploadConfig' => array(
            				'uploadRoute' => 'comur_api_upload',        //optional
            				'uploadUrl' => $myEntity->getUploadRootDir(),       // required - see explanation below (you can also put just a dir path)
            				'webDir' => $myEntity->getUploadDir(),              // required - see explanation below (you can also put just a dir path)
            				'fileExt' => '*.jpg;*.gif;*.png;*.jpeg',    //optional
            				'libraryDir' => null,                       //optional
            				'libraryRoute' => 'comur_api_image_library', //optional
            				'showLibrary' => true,                      //optional
            				'saveOriginal' => 'originalImage',          //optional
            				'generateFilename' => true          //optional
            		),
            		'cropConfig' => array(
            				'minWidth' => 588,
            				'minHeight' => 300,
            				'aspectRatio' => true,              //optional
            				'cropRoute' => 'comur_api_crop',    //optional
            				'forceResize' => false,             //optional
            				'thumbs' => array(                  //optional
            						array(
            								'maxWidth' => 180,
            								'maxHeight' => 400,
            								'useAsFieldImage' => true  //optional
            						)
            				)
            		)
            ))
            */
			
        ;

        if (!$this->parent) {
            $builder
                ->add(
                    'parent',
                    EntityType::class,
                    [
                        'class' => 'AppBundle\Entity\MacrocategorieMag',
                        'query_builder' => $this->selectMacrocategorieMagLocaleActive(),
                        'label' => 'macrocategorieMag.parent',
                        'placeholder' => 'macrocategorieMag.parent.select',
                        'required' => false,
                    ]
                );
        } else {
            $builder->addEventSubscriber(new ParentMacrocategorieMagTranslationSubscriber($this->em, $this->parent));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => MacrocategorieMag::class,
                'parent' => null,
            )
        );
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function selectMacrocategorieMagLocaleActive()
    {
        return $this->em->getRepository('AppBundle:MacrocategorieMag')
            ->selectMacrocategorieMagLocaleActive($this->localeActive);
    }
}
