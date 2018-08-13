<?php
namespace App\Table;

use App\Entity\Post;
use App\Entity\Tag;
use PDO;

/**
 * Class PostTable
 * @package App\Table
 */
class PostTable extends Table
{

	protected $entity = Post::class;

	/**
	 * @param string $alias
	 * @return array
	 */
	public function getAll(string $alias = 't'): array
	{
		$posts = parent::getAll('p');
		foreach ($posts as $post) {
			if (!empty($this->findTags($post->id))) {
				$post->tags = $this->findTags($post->id);
			}
		}
		return $posts;
	}

	public function findTags(int $post_id): array
	{
		$statement = $this->db->createQueryBuilder()
			->select('t.*')
			->from('tags', 't')
			->innerJoin('t', 'posts_tags', 'pt', 'pt.tag_id = t.id')
			->where('pt.post_id = ?')
			->setParameter(0, $post_id)
			->execute();
		$statement->setFetchMode(PDO::FETCH_CLASS, Tag::class);
		return $statement->fetchAll();
	}

	/**
	 * @param array $data
	 * @return int
	 * @throws \Doctrine\DBAL\DBALException
	 * @throws \PhpDocReader\AnnotationException
	 * @throws \ReflectionException
	 */
    public function save(array $data = []): int
    {
    	$tags = [];
		if (isset($data['tag'])) {
			$tags = $data['tag'];
			unset($data['tag']);
		}
        $this->db->insert('posts', $data);
		$lastIdPost = $this->db->lastInsertId();
		if ($lastIdPost && !empty($tags)) {
			$this->saveTags($tags, $lastIdPost);
		}
        return $lastIdPost;
    }

	/**
	 * @param string $tags
	 * @param int $lastPostId
	 * @return PostTable
	 * @throws \Doctrine\DBAL\DBALException
	 * @throws \PhpDocReader\AnnotationException
	 * @throws \ReflectionException
	 */
	public function saveTags(string $tags, int $lastPostId): self
	{
		$tagTable    = new TagTable($this->db);
		$tagsInTable = [];
		foreach ($tagTable->getAll() as $tag) {
			$tagsInTable[$tag->name] = $tag->slug;
		}
		$tags = explode(',', $tags);
		$tags = array_filter($tags);
		foreach ($tags as $tag) {
			$lastIdTag = null;
			if (!array_key_exists($tag, $tagsInTable)) {
				$lastIdTag = $tagTable->save($tag);
			} else {
				$lastIdTag = $tagTable->findBySlug($tagsInTable[$tag])->id;
			}
			$tagTable->insertPostTag($lastPostId, $lastIdTag);
		}
		return $this;
	}

}