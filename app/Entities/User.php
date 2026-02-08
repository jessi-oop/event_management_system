<?php

class User
{
    public $user_id;
    public $full_name;
    public $email;
    public $role;
    public $password_hash;
    public $created_at;

    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->user_id = $data['user_id'] ?? null;
            $this->full_name = $data['full_name'] ?? null;
            $this->email = $data['email'] ?? null;
            $this->role = $data['role'] ?? null;
            $this->password_hash = $data['password_hash'] ?? null;
            $this->created_at = $data['created_at'] ?? null;
        }
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isOrganizer()
    {
        return $this->role === 'organizer';
    }

    public function isAttendee()
    {
        return $thus->role === 'attendee';
    }

    public function toArray()
    {
        return [
            'id' => $this->user_id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'role' => $this->role,
            'password' => $this->password_hash,
            'created_at' => $this->created_at
        ];
    }
}
