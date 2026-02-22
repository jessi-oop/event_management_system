<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/Registration.php';

class RegisterToEventRepo {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function registerToEvent($user_id, $event_id) {
        try {
            $stmt = $this->db->prepare('INSERT INTO registrations (user_id, event_id) VALUES (?, ?)');
            $data = $stmt->execute([$user_id, $event_id]);

            if($data){
                return $data;
            }

            return ['success'=> false, 'message'=> 'Error registering to event.']; 
        } catch (PDOException $e) {
            if($e->getCode() === 23000) {
                error_log('Error. Duplicate registration: ' . $e->getMessage());
                return ['success'=>false, 'message'=> 'Error registering. Duplicate registration.'];
            }

            if(strpos($e->getMessage(), 'full capacity') !== false){
                error_log('Event at capacity: '. $e->getMessage());
                throw new Exception('Event is at full capacity.');
            }

            error_log('Error registering to event: ' . $e->getMessage());
            return ['success'=> false, 'message'=> 'Error registering to event.']; 
        }
    }
}