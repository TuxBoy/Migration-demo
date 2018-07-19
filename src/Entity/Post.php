<?php
namespace App\Entity;

/**
 * Post
 */
class Post
{

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
    public $online;

}