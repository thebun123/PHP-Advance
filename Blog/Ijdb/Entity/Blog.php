<?php
namespace Ijdb\Entity;
use Cassandra\Date;

class Blog{
    public int $id;
    public string $title;

    public string $description;
    public string $image;
    public int $status;
    private ?object $author;

    public string $created;
    public string $updated;

    public function __construct(private ?\Website\DatabaseTable $authorTable){
    }


}