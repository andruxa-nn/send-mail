<?php
class SendMail {
    public $db;
    public $Data = array();

    function __construct() {
        $this->db = new DataBase();
    }

    private function emailCheck($email) {
        return !!preg_match('/([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})/im', $email);
    }

    private function emailRegularize($mixedString) {
        preg_match_all('/(?:[a-z0-9_\.-]+)@(?:[a-z0-9_\.-]+)\.(?:[a-z\.]{2,6})/im', $mixedString, $result);
        return $result;
    }

    private function nameUniqueCheck($email) {
        try {
            $query_db = $this->db->query("SELECT COUNT(*) FROM mail WHERE item = '{$email}'");
            $result = $query_db->fetchColumn();
        } catch (PDOException $exeption) {
            $this->Data['Errors'][] = "Ошибка! $exeption";
        }
        return !$result ? true : false;
    }

    public function addUrl($url) {
        if (preg_match('/(([a-z0-9\-\.]+)?[a-z0-9\-]+(!?\.[a-z]{2,6}))/is', $url)) {
            $result = $this->emailRegularize(file_get_contents($url));
            foreach ($result[0] as $key => $value) {
                try {
                    $query_db = $this->db->query("INSERT INTO mail SET item = '{$item}'");
                    $this->Data['Success'][] = "Адрес $addEmail успешно добавлен в базу.";
                } catch (PDOException $exeption) {
                    $this->Data['Errors'][] = "Ошибка! $exeption";
                }
            }
        } else {
            $this->Data['Errors'][] = "URL не является форматом www.mycompany.ru.";
        }
    }

    public function addEmail($email) {
        $email = strip_tags(trim($email));
        if ($this->emailCheck($email)) {
            if ($this->nameUniqueCheck($email)) {
                try {
                    $query_db = $this->db->query("INSERT INTO mail SET item = '{$email}'");
                    $this->Data['Success'][] = "Адрес $email успешно добавлен в базу.";
                } catch (PDOException $exeption) {
                    $this->Data['Errors'][] = "Ошибка! $exeption";
                }
            } else {
                $this->Data['Errors'][] = "Адрес $email уже содержится в базе.";
            }
        } else {
            $this->Data['Errors'][] = "Адрес $email не является форматом info@mycompany.ru.";
        }
    }

    public function editEmail($id, $newName) {
        $newName = strip_tags(trim($newName));
        if ((int)$id && $this->emailCheck($newName)) {
            try {
                $query_db = $this->db->prepare("UPDATE mail SET item = ? WHERE id = ?");
                $query_db->execute(array($newName, $id));
                $this->Data['Success'][] = "Адрес $newName успешно изменен.";
            } catch (PDOException $exeption) {
                $this->Data['Errors'][] = "Ошибка! $exeption";
            }
        } else {
            $this->Data['Errors'][] = "Адрес $newName не является форматом info@mycompany.ru.";
        }
    }

    public function delEmail($id) {
        if ((int)$id) {
            try {
                $this->db->query("DELETE FROM mail WHERE id = $id");
                $this->Data['Success'][] = "Адрес $id успешно удален.";
                $this->Data['deleted'] = true;
            } catch (PDOException $exeption) {
                $this->Data['Errors'][] = "Ошибка! $exeption";
            }
        }
    }

    public function getEmail($id) {
        if ((int)$id) {
            try {
                $query_db = $this->db->query("SELECT * FROM mail WHERE id = $id");
                $query_db->setFetchMode(PDO::FETCH_ASSOC);
                $this->Data['Email'] = $query_db->fetch();
            } catch (PDOException $exeption) {
                $this->Data['Errors'][] = "Ошибка! $exeption";
            }
        }
    }

    public function getListEmails() {
        $query_db = $this->db->query("SELECT * FROM mail ORDER BY id LIMIT 0, 10000");
        $this->Data['Emails'] = $query_db->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_COLUMN|PDO::FETCH_GROUP);
        return $this->Data['Emails'];
    }

    public function parseFolder() {
        $listDir = new RecursiveDirectoryIterator(Config::basedir . Config::dir1);
        $iterator = new RecursiveIteratorIterator($listDir);
        foreach($iterator as $value) {
            if (is_file($value)) {
                $item = $this->emailRegularize(@file_get_contents($value));
                foreach ($item[0] as $email) {
                    if ($this->nameUniqueCheck($email)) {
                        try {
                            $query = $this->db->prepare("INSERT INTO mail SET item = ?");
                            $query->execute(array($email));
                            $this->Data['Success'][] = "Адрес $email успешно добавлен в базу.";
                        } catch (PDOException $exeption) {
                            $this->Data['Errors'][] = "Ошибка! $exeption";
                        }
                    } else {
                        $this->Data['Errors'][] = "Адрес $email уже содержится в базе.";
                        continue;
                    }
                }
            }
            @copy($value, Config::basedir . Config::dir2 . '/' . basename($value));
            @unlink($value);
        }
    }
}