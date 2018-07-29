<?php
namespace App\Table;

use App\Entity\Post;
use Doctrine\DBAL\Connection;
use PDO;

class PostTable
{

    /**
     * @var Connection
     */
    private $db;

    /**
     * PostTable constructor
     *
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Get all posts
     *
     * @return Post[]
     */
    public function getAll(): array
    {
        $statement = $this->db->createQueryBuilder()
            ->select('*')
            ->from('posts', 'p')
            ->execute();
        $statement->setFetchMode(PDO::FETCH_CLASS, Post::class);
        return $statement->fetchAll();
    }

    /**
     * @param string $slug
     * @return Post
     */
    public function findBySlug(string $slug): ?Post
    {
        $statement = $this->db->createQueryBuilder()
            ->select('*')
            ->from('posts', 'p')
            ->where('p.slug = ?')
            ->setParameter(0, $slug)
            ->execute();
        $statement->setFetchMode(PDO::FETCH_CLASS, Post::class);
        return $statement->fetch() ?: null;
    }

    /**
     * @param array $data
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(array $data = []): int
    {
        return $this->db->insert('posts', $data);
    }

}