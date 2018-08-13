<?php
namespace App\Table;

use App\Entity\Tag;
use Cocur\Slugify\Slugify;

class TagTable extends Table
{

	protected $entity = Tag::class;

	/**
	 * @param int $lastPostId
	 * @param int $lastIdTag
	 * @return int
	 * @throws \Doctrine\DBAL\DBALException
	 */
	public function insertPostTag(int $lastPostId, int $lastIdTag): int
	{
		return $this->db->insert('posts_tags', [
			'post_id' => $lastPostId,
			'tag_id'  => $lastIdTag
		]);
	}

	/**
	 * @param $tag string
	 * @return int The last insert id
	 * @throws \Doctrine\DBAL\DBALException
	 */
	public function save(string $tag): int
	{
		$this->db->insert('tags', [
			'name' => $tag,
			'slug' => (new Slugify())->slugify($tag)
		]);
		return $this->db->lastInsertId();
	}

}