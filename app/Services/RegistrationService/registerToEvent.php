<?php

require_once __DIR__  . '/../../Repositories/RegistrationRepository/registerToEvent.php';
require_once __DIR__  . '/../../Repositories/RegistrationRepository/isRegisteredToEvent.php';
require_once __DIR__ . '/../../Entities/Registration.php';
require_once __DIR__ . '/../EventService/getEventById.php';

class RegisterToEventService{
    private $register_to_event;
    private $get_event_by_id;
    private $is_registered_to_event;

    public function __construct(){
        $this->register_to_event = new RegisterToEventRepo();
        $this->get_event_by_id = new GetEventByIdService();
        $this->is_registered_to_event = new IsRegisteredToEventRepo();
    }

    public function registerToEvent($user_id, $event_id) {

        if(empty($user_id) || empty($event_id)){
            return ['success'=> false, 'message'=> 'Error. User id and Event id is needed to register.'];
        }

        $event = $this->get_event_by_id->getEventById($event_id);

        if(!$event) {
            return ['success'=> false, 'message'=> 'Registration failed. Event does not exist.'];
        }

        if($event->isPastEvent()){
            return ['success'=> false, 'message'=> 'Error. Cannot register for past events.'];
        }

        if($this->is_registered_to_event->isRegisteredToEvent($user_id, $event_id)){
            return ['success'=> false, 'message'=> 'You are already registered to this event.'];
        }

        $register = $this->register_to_event->registerToEvent($user_id, $event_id);

        if($register){
            return ['success'=> true, 'message'=> 'Registration successfull.'];
        }

        return ['success'=> false, 'message'=> 'Error in registering for event.'];

    }
}