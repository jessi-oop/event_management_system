<?php

class Categories
{
    public $category_id;
    public $category_name;
    public $description;
    public $created_at;

    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->category_id = $data['category_id'] ?? null;
            $this->category_name = $data['category_name'] ?? null;
            $this->description = $data['description'] ?? null;
            $this->created_at = $data['created_at'] ?? null;
        }

    }
}
