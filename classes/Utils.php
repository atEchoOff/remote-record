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
        $ret = $this->db->query("select * from User where email = ?;", "s", $email);

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
        $ret = $this->db->query("select * from Composition where name = ?;", "s", $name);

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
     * Returns whether or not the current user is a member of the given composition name
     */
    public function memberOfComposition($composition)
    {
        $result = $this->db->query("select * from UserComposition where email = ? and name = ?", "ss", $_SESSION["email"], $composition);

        if ($result === false) {
            die("An SQL Error Occured");
        }

        return sizeof($result) !== 0;
    }

    /**
     * Create a user given a name and username and password
     * @return true if success
     * @return false otherwise
     */
    public function createUser($name, $email, $password)
    {
        return $this->db->query("insert into User (name, email, password) values (?, ?, ?);", "sss", $name, $email, password_hash($password, PASSWORD_DEFAULT));
    }

    /**
     * Creates a composition for current user (uses $_SESSION) given name and backtrack file location
     * @return true if success
     * @return false if failed
     */
    public function createComposition($name, $location)
    {
        $result = $this->db->query("insert into Composition (name, composer_email, location) values (?, ?, ?);", "sss", $name, $_SESSION["email"], $location);

        // check for errors
        if ($result === false) {
            die("An error occured while querying SQL");
        }

        return $this->db->query("insert into UserComposition (email, name) values (?, ?);", "ss", $_SESSION["email"], $name);
    }

    /**
     * Creates a user composition
     * Effectively, adds a composition to the current user
     */
    public function createUserComposition($composition)
    {
        return $this->db->query("insert into UserComposition (email, name) values (?, ?)", "ss", $_SESSION["email"], $composition);
    }

    /**
     * Returns a list of all compositions you are not a member of
     */
    public function allForeignCompositions()
    {
        $result = $this->db->query("select * from Composition");

        if ($result === false) {
            die("An error occured while querying SQL");
        }

        $return = [];

        // For each composition, add the composer name element
        foreach ($result as $composition) {
            if ($this->memberOfComposition($composition["name"]) === false) {
                // trace back to the composer and add their name
                $composition["composer_name"] = $this->getUser($composition["composer_email"])["name"];

                array_push($return, $composition);
            }
        }

        return $return;
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

        // For each composition, add the composer name element
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

    /**
     * Returns all recordings for current user and given composition name (Uses $_SESSION)
     */
    public function getUserCompositionRecordings($composition)
    {
        return $this->db->query("select * from Recording where composition = ? and author = ?", "ss", $composition["name"], $_SESSION["email"]);
    }

    /**
     * Returns all recordings for a given composition and ties the owner name to each one
     */
    public function getCompositionRecordings($composition)
    {
        $list = $this->db->query("select * from Recording where composition = ?", "s", $composition["name"]);

        // For each composition, add the composer name
        $ret_list = [];
        foreach ($list as $recording) {
            $recording["author_name"] = $this->getUser($recording["author"])["name"];
            array_push($ret_list, $recording);
        }

        return $ret_list;
    }

    /**
     * Creates a recording for current user and given composition name and location (uses $_SESSION)
     */
    public function createUserCompositionRecording($name, $composition, $location)
    {
        return $this->db->query("insert into Recording (name, location, author, composition) values (?, ?, ?, ?)", "ssss", $name, $location, $_SESSION["email"], $composition);
    }

    /**
     * Gets the recording with the specified ID
     * @return false if no recording was found
     */
    public function getRecording($id)
    {
        $response = $this->db->query("select * from Recording where id=?", "s", $id);

        if (sizeof($response) === 0) {
            return false;
        }

        return $response[0];
    }

    /**
     * Delete the recording with the specified ID
     * Note: dont use this without validation
     */
    public function deleteRecording($id)
    {
        return $this->db->query("delete from Recording where id=?", "s", $id);
    }

    /**
     * Static function to convert the location into a clean string
     * Used to get the name of waveforms in composition and record page
     * Used to get clean IDs throughout composition and record page
     */
    public static function cleanLocation($location)
    {
        return strtok(str_replace(" ", "space", str_replace("/", "slash", str_replace("-", "dash", $location))), ".");
    }
}
