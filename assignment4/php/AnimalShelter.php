<?php
// AnimalShelter.php
class AnimalShelter implements JsonSerializable {
    private $username;
    private $fullname;
    private $dateOfBirth;
    private $email;

    // Constructor to initialize properties
    public function __construct($username, $fullname, $dateOfBirth, $email) {
        $this->username = $username;
        $this->fullname = $fullname;
        $this->dateOfBirth = $dateOfBirth;
        $this->email = $email;
    }

    // Implement JsonSerializable interface method
    public function jsonSerialize() {
        return [
            'username' => $this->username,
            'fullname' => $this->fullname,
            'dateOfBirth' => $this->dateOfBirth,
            'email' => $this->email
        ];
    }
}
?>
