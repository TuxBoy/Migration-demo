<?php
namespace App\Entity;

use Cocur\Slugify\Slugify;
use SDAM\Traits\HasTimestamp;

/**
 * Post
 */
class Post
{
    use HasTimestamp;

    /**
     * @length 60
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $slug;

    /**
     * @text
     * @var string
     */
    public $content;

    /**
     * @var boolean
     */
    public $online = true;

    /**
     * @link belongsTo
     * @var Category
     */
    public $category;

	/**
	 * @link belongsToMany
	 * @var Tag[]
	 */
    public $tags;

	/**
	 * @param string|null $slug
	 * @return Post
	 */
	public function setSlug(?string $slug): self
	{
		if (is_null($slug)) {
			$this->slug = (new Slugify())->slugify($this->name);
		} else {
			$this->slug = $slug;
		}
		return $this;
	}

}