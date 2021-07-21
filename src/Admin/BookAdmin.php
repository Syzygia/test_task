<?php
namespace App\Admin;

use App\Entity\Book;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class BookAdmin extends AbstractAdmin
{
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper->add('name', TextType::class);
		$formMapper->add('description', TextareaType::class);
		$formMapper->add('authors', ModelAutocompleteType::class, [
		'property' => 'name',
		'multiple' => true,
		'to_string_callback' => function($entity, $property) {
				return $entity->getName();
			},
	])
	;
		//$formMapper->add('publishing_year', Data)
	}

	protected function configureDatagridFilters(DatagridMapper $datagridMapper)
	{
		$datagridMapper->add('name');
	}

	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper->addIdentifier('name');
	}
	public function toString($object)
	{
		return $object instanceof Book
			? $object->getName()
			: 'Book'; // shown in the breadcrumb on the create view
	}
}
