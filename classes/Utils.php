<?php

/**
 * Class to perform background tasks for website
 */
class Utils
{
    /**
     * Database container
     */
    private $db;

    /**
     * Create connection to db
     */
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Returns the user with the specified email
     * @exception if SQL error
     * @return false if no user exists
     */
    public function getUser($email)
    {
        $ret = $this->db->query("select * from user where email = ?;", "s", $email);

        if ($ret === false) {
            die("User retrieval failed");
        }

        if (sizeof($ret) === 0) {
            // No user exists
            return false;
        }

        // there can only be one user (unique email) return this user
        return $ret[0];
    }

    /**
     * Create a user given a username and password
     * @return true if success
     * @return false otherwise
     */
    public function createUser($email, $password)
    {
        return $this->db->query("insert into user (email, password) values (?, ?)", "ss", $email, password_hash($password, PASSWORD_DEFAULT));
    }
}
