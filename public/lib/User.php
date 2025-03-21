<?php

abstract class User {
    protected $username;
    protected $email;
    protected $name;
    protected $bio;
    protected $profile_pic;
    protected $data_changes_history;

    /**
     * @param $username
     * @param $email
     * @param $name
     * @param $bio = null
     * @param $profile_pic = null
     */
    public function __construct($username, $email, $name, $bio = null, $profile_pic = null)
    {
        $this->username = $username;
        $this->email = $email;
        $this->name = $name;
        $this->bio = $bio;
        $this->profile_pic = $profile_pic;
        $this->data_changes_history = ['origin' => ['username' => $username, 'email' => $email, 'name' => $name, 'bio' => $bio, 'profile_pic' => $profile_pic]];
    }

    abstract public function isUsernameExist($username);
}
