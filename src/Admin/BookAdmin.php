<?php
namespace App\Admin;

use App\Entity\Book;
use AppBundle\Form\Type\ImageTypeExtension;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class BookAdmin extends AbstractAdmin
{
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper->add('name', TextType::class);
		$formMapper->add('description', TextareaType::class);
		$formMapper->add('cover',FileType::class, [
			'mapped' => false,
			//'image_property' => $this->getSubject()->getCover(),

			'required' => false,
		]);
		$formMapper->add('publish_year', DatePickerType::class, ['input_format' => 'Y',
			'format' => 'y',
			'input' => 'string']);
		$formMapper->add('authors', ModelType::class, [
		'property' => 'name',
		'multiple' => true,
	])
	;
		//$formMapper->add('publishing_year', Data)
	}

	protected function configureDatagridFilters(DatagridMapper $datagridMapper)
	{
		$datagridMapper->add('name');
		$datagridMapper->add('publish_year');
		$datagridMapper->add('description');
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
