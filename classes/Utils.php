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
     * Returns the composition given a composition name
     * @return false if there are no compositions given the name
     */
    public function getComposition($name)
    {
        $ret = $this->db->query("select * from composition where name = ?;", "s", $name);

        if ($ret === false) {
            die("Composition retrieval failed");
        }

        if (sizeof($ret) === 0) {
            // No user exists
            return false;
        }

        // there can only be one composition (unique name) return this composition
        return $ret[0];
    }

    /**
     * Create a user given a username and password
     * @return true if success
     * @return false otherwise
     */
    public function createUser($name, $email, $password)
    {
        return $this->db->query("insert into user (name, email, password) values (?, ?, ?);", "sss", $name, $email, password_hash($password, PASSWORD_DEFAULT));
    }

    /**
     * Creates a composition for current user (uses $_SESSION) given name and backtrack file location
     * @return true if success
     * @return false if failed
     */
    public function createComposition($name, $location)
    {
        $result = $this->db->query("insert into composition (name, composer_email, location) values (?, ?, ?);", "sss", $name, $_SESSION["email"], $location);

        // check for errors
        if ($result === false) {
            die("An error occured while querying SQL");
        }

        return $this->db->query("insert into UserComposition (email, name) values (?, ?);", "ss", $_SESSION["email"], $name);
    }

    /**
     * Get a list of all compositions the current user is a member of (uses $_SESSION)
     */
    public function listCompositions()
    {
        $result = $this->db->query("select name from UserComposition where email = ?;", "s", $_SESSION["email"]);

        if ($result === false) {
            die("An error occured while querying SQL");
        }

        $return = [];

        foreach ($result as $composition) {
            $real_composition = $this->getComposition($composition["name"]);
            if ($real_composition === false) {
                die("Fatal error, grabbing a composition from merge table did not exist");
            }

            // trace back to the composer and add their name
            $real_composition["composer_name"] = $this->getUser($real_composition["composer_email"])["name"];

            array_push($return, $real_composition);
        }

        return $return;
    }
}
