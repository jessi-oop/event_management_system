<?php

require_once __DIR__ . '/../../Repositories/CategoryRepository/getAllCategories.php';

class GetAllCategoriesService {
    private $get_all_categories;

    public function __construct(){
        $this->get_all_categories = new GetAllCategoriesRepo();
    }

    public function getAllCategories() {
        return $this->get_all_categories->getAllCategories();
    }
}
