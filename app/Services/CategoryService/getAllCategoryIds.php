<?php

require_once __DIR__ . '/../../Repositories/CategoryRepository/getAllCategoryIds.php';

class GetAllCategoryIdsService {
    private $get_all_category_ids;

    public function __construct(){
        $this->get_all_category_ids = new GetAllCategoryIdsRepo();
    }

    public function getAllCategoryIds(){
        return $this->get_all_category_ids->getAllCategoryIds();
    }
}