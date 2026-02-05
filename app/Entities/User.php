<?php

class User
{
    public $id;
    public $username;
    public $email;
    public $role;
    public $password;
    public $created_at;

    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->id = $data['id'] ?? null;
            $this->username = $data['username'] ?? null;
            $this->email = $data['email'] ?? null;
            $this->role = $data['role'] ?? null;
            $this->password = $data['password'] ?? null;
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
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->role,
            'password' => $this->password,
            'created_at' => $this->created_at
        ];
    }
}
