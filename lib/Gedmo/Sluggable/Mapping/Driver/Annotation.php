<?php

namespace Gedmo\Sluggable\Mapping\Driver;

use Gedmo\Mapping\Driver,
    Doctrine\Common\Annotations\AnnotationReader,
    Doctrine\ORM\Mapping\ClassMetadataInfo,
    Gedmo\Exception\InvalidArgumentException;

/**
 * This is an annotation mapping driver for Sluggable
 * behavioral extension. Used for extraction of extended
 * metadata from Annotations specificaly for Sluggable
 * extension.
 * 
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @package Gedmo.Sluggable.Mapping.Driver
 * @subpackage Annotation
 * @link http://www.gediminasm.org
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class Annotation implements Driver
{
    /**
     * Annotation to mark field as sluggable and include it in slug building
     */
    const ANNOTATION_SLUGGABLE = 'Gedmo\Sluggable\Mapping\Sluggable';
    
    /**
     * Annotation to identify field as one which holds the slug
     * together with slug options
     */
    const ANNOTATION_SLUG = 'Gedmo\Sluggable\Mapping\Slug';
    
    /**
     * List of types which are valid for slug and sluggable fields
     * 
     * @var array
     */
    private $_validTypes = array(
        'string'
    );
    
    /**
     * {@inheritDoc}
     */
    public function validateFullMetadata(ClassMetadataInfo $meta, array $config)
    {
        if ($config && !isset($config['fields'])) {
            throw new InvalidArgumentException("Unable to find any sluggable fields specified for Sluggable entity - {$meta->name}");
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function readExtendedMetadata(ClassMetadataInfo $meta, array &$config) {
        require_once __DIR__ . '/../Annotations.php';
        $reader = new AnnotationReader();
        $reader->setAnnotationNamespaceAlias('Gedmo\Sluggable\Mapping\\', 'gedmo');
        
        $class = $meta->getReflectionClass();        
        // property annotations
        foreach ($class->getProperties() as $property) {
            if ($meta->isMappedSuperclass && !$property->isPrivate() ||
                $meta->isInheritedField($property->name) ||
                $meta->isInheritedAssociation($property->name)
            ) {
                continue;
            }
            // sluggable property
            if ($sluggable = $reader->getPropertyAnnotation($property, self::ANNOTATION_SLUGGABLE)) {
                $field = $property->getName();
                if (!$meta->hasField($field)) {
                    throw new InvalidArgumentException("Unable to find sluggable [{$field}] as mapped property in entity - {$meta->name}");
                }
                if (!$this->_isValidField($meta, $field)) {
                    throw new InvalidArgumentException("Cannot slug field - [{$field}] type is not valid and must be 'string' in class - {$meta->name}");
                }
                $config['fields'][] = $field;
            }
            // slug property
            if ($slug = $reader->getPropertyAnnotation($property, self::ANNOTATION_SLUG)) {
                $field = $property->getName();
                if (!$meta->hasField($field)) {
                    throw new InvalidArgumentException("Unable to find slug [{$field}] as mapped property in entity - {$meta->name}");
                }
                if (!$this->_isValidField($meta, $field)) {
                    throw new InvalidArgumentException("Cannot use field - [{$field}] for slug storage, type is not valid and must be 'string' in class - {$meta->name}");
                } 
                if (isset($config['slug'])) {
                    throw new InvalidArgumentException("There cannot be two slug fields: [{$slugField}] and [{$config['slug']}], in class - {$meta->name}.");
                }
                
                $config['slug'] = $field;
                $config['style'] = $slug->style;
                $config['updatable'] = $slug->updatable;
                $config['unique'] = $slug->unique;
                $config['separator'] = $slug->separator;
            }
        }
    }
    
    /**
     * Checks if $field type is valid as Sluggable field
     * 
     * @param ClassMetadataInfo $meta
     * @param string $field
     * @return boolean
     */
    protected function _isValidField(ClassMetadataInfo $meta, $field)
    {
        return in_array($meta->getTypeOfField($field), $this->_validTypes);
    }
}