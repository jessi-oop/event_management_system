<?php

require_once __DIR__ . '/../../Repositories/RegistrationRepository/getRegistrationCount.php';
require_once __DIR__ . '/../EventService/getEventById.php';

class GetRegistrationCount {
    private $get_registration_count;
    private $get_event_by_id;

    public function __construct(){
        $this->get_registration_count = new GetRegistrationCountRepo();
        $this->get_event_by_id = new GetEventByIdService();
    }

    public function getRegistrationCount($event_id){
        if(empty($event_id)){
            return ['success'=> false, 'message'=> 'Error. Event ID is empty.'];
        }

        $event = $this->get_event_by_id->getEventById($event_id);

        if(!$event) {
            return ['success'=> false, 'message'=> 'Error getting registration count. Event does not exist.'];
        }

        $registration_count = $this->get_registration_count->getRegistrationCount($event_id);

        if($registration_count){
            return ['success'=> true, 'message'=> 'Registration count retrieved successfully.', 'data'=>$registration_count];
        }

        return ['success'=> false, 'message'=> 'Error. Failed to retrieve registration count.'];
    }
}