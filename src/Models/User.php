<?php

namespace Models\Users;
abstract class User {
    private $username;
    private $email;
    private $name;
    private $bio;
    private $profile_pic;
    private $data_changes_history;

    /**
     * @param $username
     * @param $email
     * @param $name
     * @param $bio = null
     * @param $profile_pic = null
     */
    public function __construct($username, $email, $name, $bio = null, $profile_pic = null) {
        $this->username = $username;
        $this->email = $email;
        $this->name = $name;
        $this->bio = $bio;
        $this->profile_pic = $profile_pic;
        $this->data_changes_history = ['origin' => ['username' => $username, 'email' => $email, 'name' => $name, 'bio' => $bio, 'profile_pic' => $profile_pic]];
    }

    /**
     * @return mixed
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    protected function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    protected function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    protected function setName($name) {
        $this->name = $name;
    }

    /**
     * @return mixed|null
     */
    public function getBio() {
        return $this->bio;
    }

    /**
     * @param mixed|null $bio
     */
    protected function setBio($bio) {
        $this->bio = $bio;
    }

    /**
     * @return mixed|null
     */
    public function getProfilePic() {
        return $this->profile_pic;
    }

    /**
     * @param mixed|null $profile_pic
     */
    protected function setProfilePic($profile_pic) {
        $this->profile_pic = $profile_pic;
    }

    // Functional methods
    public function displayUserInfo() {
        return "Username: " . $this->username . ", Email: " . $this->email . ", Name: " . $this->name . ", Bio: " . $this->bio . ", Profile Pic: " . $this->profile_pic;
    }

    abstract public function isUsernameExist($username);
}
