<?php

require_once __DIR__ . '/../../Repositories/CategoryRepository/getCategoryById.php';

class GetCategoryByIdService {
    private $get_category_by_id;

    public function __construct(){
        $this->get_category_by_id = new GetCategoryByIdRepo();
    }

    public function getCategoryById($category_id){
        return $this->get_category_by_id->getCategoryById($category_id);
    }
}