<?php


namespace App\Form\DataTransformer;


use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TagDataTransformer implements DataTransformerInterface
{
    /**
     * @var TagRepository
     */
    private $repository;

    /**
     * TagDataTransformer constructor.
     * @param TagRepository $repository
     */
    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Transforms a value from the original representation to a transformed representation.
     *
     * This method is called when the form field is initialized with its default data, on
     * two occasions for two types of transformers:
     *
     * 1. Model transformers which normalize the model data.
     *    This is mainly useful when the same form type (the same configuration)
     *    has to handle different kind of underlying data, e.g The DateType can
     *    deal with strings or \DateTime objects as input.
     *
     * 2. View transformers which adapt the normalized data to the view format.
     *    a/ When the form is simple, the value returned by convention is used
     *       directly in the view and thus can only be a string or an array. In
     *       this case the data class should be null.
     *
     *    b/ When the form is compound the returned value should be an array or
     *       an object to be mapped to the children. Each property of the compound
     *       data will be used as model data by each child and will be transformed
     *       too. In this case data class should be the class of the object, or null
     *       when it is an array.
     *
     * All transformers are called in a configured order from model data to view value.
     * At the end of this chain the view data will be validated against the data class
     * setting.
     *
     * This method must be able to deal with empty values. Usually this will
     * be NULL, but depending on your implementation other empty values are
     * possible as well (such as empty strings). The reasoning behind this is
     * that data transformers must be chainable. If the transform() method
     * of the first data transformer outputs NULL, the second must be able to
     * process that value.
     *
     * @param PersistentCollection $value The value in the original representation
     *
     * @return mixed The value in the transformed representation
     *
     * @throws TransformationFailedException when the transformation fails
     */
    public function transform($value)
    {
        if ($value == null) {
            return "";
        }

        $tagArray = [];

        foreach ($value as $tag) {
            array_push($tagArray, $tag->getTagName());
        }

        return implode(", ", $tagArray);
    }

    /**
     * Transforms a value from the transformed representation to its original
     * representation.
     *
     * This method is called when {@link Form::submit()} is called to transform the requests tainted data
     * into an acceptable format.
     *
     * The same transformers are called in the reverse order so the responsibility is to
     * return one of the types that would be expected as input of transform().
     *
     * This method must be able to deal with empty values. Usually this will
     * be an empty string, but depending on your implementation other empty
     * values are possible as well (such as NULL). The reasoning behind
     * this is that value transformers must be chainable. If the
     * reverseTransform() method of the first value transformer outputs an
     * empty string, the second value transformer must be able to process that
     * value.
     *
     * By convention, reverseTransform() should return NULL if an empty string
     * is passed.
     *
     * @param mixed $value The value in the transformed representation
     *
     * @return mixed The value in the original representation
     *
     * @throws TransformationFailedException when the transformation fails
     */
    public function reverseTransform($value)
    {
        $tagCollection = new ArrayCollection();

        //Transformation de la liste des tags en tableau
        $tagArray = explode(", ", $value);

        //nettoyage des éléments du tableau
        $tagArray = array_map(function ($item) {
            return trim($item);
            },
            $tagArray
        );

        //Unicité des tags
        $tagArray = array_unique($tagArray);

        foreach ($tagArray as $tagName) {

            //Recherche d'un tag existant
            $tag = $this->repository->findOneBy(["tagName"=>$tagName]);

            //Création d'un nouveau tag si inexistant
            if(! $tag){
                $tag = new Tag();
                $tag->setTagName($tagName);
            }

            $tagCollection->add($tag);
        }

        return $tagCollection;
    }
}