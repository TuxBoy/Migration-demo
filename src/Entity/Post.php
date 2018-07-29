<?php
namespace App\Entity;
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
     * @var integer
     */
    public $post_count;

    /**
     * @link belongsTo
     * @var Category
     */
    public $category;

}