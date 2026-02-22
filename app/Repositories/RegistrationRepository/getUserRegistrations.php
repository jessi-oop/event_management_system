<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/Registration.php';

class GetUserRegistrationsRepo {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function getUserRegistrations($user_id){
        try {
            $stmt = $this->db->prepare(
                'SELECT * FROM user_event_registrations 
                WHERE user_id = ? 
                ORDER BY event_date ASC');
            $stmt->execute([$user_id]);
            
            $registrations = [];
            while($data = $stmt->fetch()){
                $registrations[] = new Registration($data);
            }

            return $registrations;
        } catch (PDOException $e) {
            error_log('Error gettin user registrations: ' . $e->getMessage());
            return [];
        }
    }
}