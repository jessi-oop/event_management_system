<?php

require_once __DIR__  . '/../../Repositories/RegistrationRepository/cancelRegistration.php';
require_once __DIR__  . '/../../Repositories/RegistrationRepository/isRegisteredToEvent.php';
require_once __DIR__ . '/../../Entities/Registration.php';
require_once __DIR__ . '/../EventService/getEventById.php';

class CancelRegistrationService {
    private $cancel_registration;
    private $get_event_by_id;
    private $registration_entity;
    private $is_registered_to_event;

    public function __construct(){
        $this->cancel_registration = new CancelRegistrationRepo();
        $this->get_event_by_id = new GetEventByIdService();
        $this->registration_entity = new Registration();
        $this->is_registered_to_event = new IsRegisteredToEventRepo();
    }

    public function cancelRegistration($user_id, $event_id) {

        if(empty($user_id) || empty($event_id)){
            return ['success'=> false, 'message'=> 'Error. User id and Event id is needed to cancel registration.'];
        }

        $event = $this->get_event_by_id->getEventById($event_id);

        if(!$event) {
            return ['success'=> false, 'message'=> 'Event does not exist.'];
        }

        if($event->isPastEvent()){
            return ['success'=> false, 'message'=> 'Error. Cannot cancel registration for past events.'];
        }

        if(!$this->is_registered_to_event->isRegisteredToEvent($user_id, $event_id)){
            return ['success'=> false, 'message'=> 'You cannot cancel registration to events you are not registered to.'];
        }

        $cancel = $this->cancel_registration->cancelRegistration($user_id, $event_id);

        if($cancel){
            return ['success'=> true, 'message'=> 'Registration cancelled successfully.'];
        }

        return ['success'=> false, 'message'=> 'Error in cancelling registration for event.'];

    }
}