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
    public function createUserCompositionRecording($id, $name, $composition, $location)
    {
        return $this->db->query("insert into Recording (id, name, location, author, composition) values (?, ?, ?, ?, ?)", "issss", $id, $name, $location, $_SESSION["email"], $composition);
    }

    /**
     * Returns the next id a recording can take (1+highest recording ID)
     */
    public function getNextRecordingID()
    {
        // https://mariadb.com/kb/en/max/
        $result = $this->db->query("select max(id) max from Recording")[0];
        return $result["max"] + 1;
    }

    /**
     * Returns the next id a product can take (1+highest product ID)
     */
    public function getNextProductID()
    {
        // https://mariadb.com/kb/en/max/
        $result = $this->db->query("select max(id) max from Product")[0];
        return $result["max"] + 1;
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
     * Gets the product with the specified ID
     * @return false if no product is found
     */
    public function getProduct($id)
    {
        $response = $this->db->query("select * from Product where id=?", "s", $id);

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
        unlink("audio/$id.webm");
        return $this->db->query("delete from Recording where id=?", "s", $id);
    }

    /**
     * Delete the product with the specified ID
     * Note: dont use this without validation
     */
    public function deleteProduct($id)
    {
        unlink("products/$id.webm");
        return $this->db->query("delete from Product where id=?", "s", $id);
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

    /**
     * Merge together audio for each id in ids list
     * Takes in float-left margins to get offset
     */
    public static function mergeAudioAPIResponse($ids, $margins)
    {
        // Store all audio data for each id
        $all_audios = [];

        // For each id, add the audio data to the list
        foreach ($ids as $id) {
            array_push($all_audios, Utils::getAudioByteData($id));
        }

        // Place semicolons between each list
        // And add margins to the right of !
        $input = implode(";", $all_audios) . "!" . implode(";", $margins);

        // Curl code from https://stackoverflow.com/questions/45339010/send-post-form-data-to-url-with-php
        // Connect to remote server and provide audio data
        $url = "76.104.28.67/pyaudioserver/index.php";
        $data = array(
            'audio' => gzcompress($input),
            "password" => "dontusethisapipleaseunlessyouareme"
        );


        $postvars = http_build_query($data) . "\n";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

        // Return float data
        return gzuncompress($server_output);
    }

    /**
     * Get file audio byte data seperated by commas
     */
    public static function getAudioByteData($id)
    {
        $contents = file_get_contents("audio/" . $id . ".webm");
        return implode(",", unpack("C*", $contents));
    }

    /**
     * Saves a product in the products database given
     * the id
     * the name
     * the width (of the box)
     * the location
     * the composition
     */
    public function createCompositionProduct($id, $name, $width, $location, $composition)
    {
        return $this->db->query("insert into Product (id, name, width, location, composition) values (?, ?, ?, ?, ?);", "isiss", $id, $name, $width, $location, $composition);
    }

    /**
     * Returns all products for a composition name
     */
    public function getCompositionProducts($composition_name)
    {
        return $this->db->query("select * from Product where composition = ?", "s", $composition_name);
    }

    /**
     * Merges all audio under each id in given ids list and given each margin
     * Uses python API
     * If a composition is specified, persist the audio for the composition
     * Otherwise, it is saved temporarily
     * If the composition is specified, a name should also be specified
     * @return list [$file_location, $width]
     */
    public function mergeAudio($ids, $margins, $composition = null, $name = null)
    {
        // Split the audio bytes and the new box width
        $response = explode("!", Utils::mergeAudioAPIResponse($ids, $margins));
        $bytes = explode(",", $response[0]);
        $new_width = $response[1];

        // Create location for next product
        // If there is no composition, save in tempfiles
        if ($composition === null) {
            $file_location = "tempfiles/" . $this->getUser($_SESSION["email"])["id"] . ".webm";
        } else {
            // A composition is specified, save in products

            // Get the next id
            $id = $this->getNextProductID();

            // Save under that id
            $file_location = "products/" . $id . ".webm";
        }

        // Save the product in the specified location
        $bin_data = pack('C*', ...$bytes);
        file_put_contents($file_location, $bin_data);

        // If composition is specified, save the product in the products database
        if ($composition !== null) {
            $this->createCompositionProduct($id, $name, $new_width, $file_location, $composition);
        }

        // Return the file location and the width
        return [$file_location, $new_width];
    }

    /**
     * Returns whether or not the given string is alphanumeric or a space
     */
    public static function isAlphaNumericOrSpace($str)
    {
        $legal = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 ";

        // For each character, check if it is a legal character
        for ($i = 0; $i < strlen($str); $i++) {
            if (strpos($legal, $str[$i]) === false) {
                // Illegal character found
                return false;
            }
        }

        // All characters are legal, this is alphanumeric (or space)
        return true;
    }
}
