<?php
namespace App\Table;

use Doctrine\DBAL\Connection;
use ICanBoogie\Inflector;
use PDO;
use ReflectionClass;
use SDAM\Annotation\Annotation;
use SDAM\Annotation\AnnotationsName;

/**
 * Class Table
 * @package App\Table
 */
class Table
{

	/**
	 * @var Connection
	 */
	protected $db;

	/**
	 * @var string
	 */
	protected $entity;

	/**
	 * @var string
	 */
	protected $tableName;

	/**
	 * PostTable constructor
	 *
	 * @param Connection $db
	 * @throws \PhpDocReader\AnnotationException
	 * @throws \ReflectionException
	 */
	public function __construct(Connection $db)
	{
		$this->db        = $db;
		$this->tableName = $this->getTableName();
	}

	/**
	 * Get all posts
	 *
	 * @param string $alias
	 * @return object[]
	 */
	public function getAll(string $alias = 't'): array
	{
		$statement = $this->db->createQueryBuilder()
			->select($alias . '.*')
			->from($this->tableName, $alias)
			->execute();
		$statement->setFetchMode(PDO::FETCH_CLASS, $this->entity);
		return $statement->fetchAll();
	}

	/**
	 * @param string $slug
	 * @return object
	 */
	public function findBySlug(string $slug)
	{
		$statement = $this->db->createQueryBuilder()
			->select('*')
			->from($this->tableName, 'p')
			->where('p.slug = ?')
			->setParameter(0, $slug)
			->execute();
		$statement->setFetchMode(PDO::FETCH_CLASS, $this->entity);
		return $statement->fetch() ?: null;
	}

	/**
	 * @return null|string
	 * @throws \PhpDocReader\AnnotationException
	 * @throws \ReflectionException
	 */
	private function getTableName(): ?string
	{
		$tableName = null;
		$className = $this->entity;
		if (Annotation::of($className)->hasAnnotation(AnnotationsName::C_STORE_NAME)) {
			$tableName = Annotation::of($className)
				->getAnnotation(AnnotationsName::C_STORE_NAME)
				->getValue();
		} else {
			$class     = new ReflectionClass($className);
			$tableName = Inflector::get()->pluralize(strtolower($class->getShortName()));
		}
		return $tableName;
	}

}