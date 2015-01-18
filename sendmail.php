<?php

class SendMail
{
    public function __construct($c)
    {
        $this->dbh = $c['db'];
        $this->dir1 = $c['settings']['dir1'];
        $this->dir2 = $c['settings']['dir2'];
        if (!is_dir($this->dir1) || !is_dir($this->dir2)) {
            @mkdir($this->dir1);
            @mkdir($this->dir2);
        }
/*
        switch($_REQUEST['do']) {
            case 'addEmail':
                $this->addEmail($_REQUEST['email']);
                break;
            case 'editEmail':
                $this->getEmail($_REQUEST['id']);
                $this->editEmail($_REQUEST['id'], $_REQUEST['newName']);
                break;
            case 'delEmail':
                $this->getEmail($_REQUEST['id']);
                $this->delEmail($_REQUEST['id']);
                break;
            case 'addUrl':
                $this->addUrl($_REQUEST['url']);
                break;
            case 'parseFolder':
                $this->parseFolder();
                break;
        }*/
    }

    private function _emailCheck($email)
    {
        return preg_match('/([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})/im', $email);
    }

    private function _emailRegularize($mixedString)
    {
        preg_match_all('/([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})/im', $mixedString, $result);
        return $result;
    }

    private function _nameDubleCheck($email)
    {
        try {
            $query_db = $this->dbh->query("SELECT COUNT(*) FROM mail WHERE item = ?", [$email]);
            $result = $query_db->el();
        } catch (PDOException $exeption) {
            $this->Data['Errors'][] = "Ошибка! $exeption";
        }
        return ($result == 0);
    }

    public function addUrl($url)
    {
        if (preg_match('/(([a-z0-9\-\.]+)?[a-z0-9\-]+(!?\.[a-z]{2,6}))/is', $url)) {
            $result = $this->emailRegularize(file_get_contents($url));
            foreach ($result[0] as $key => $value) {
                try {
                    $query_db = $this->dbh->query("INSERT INTO mail SET item = '{$item}'");
                    $this->Data['Success'][] = "Адрес $addEmail успешно добавлен в базу.";
                } catch (PDOException $exeption) {
                    $this->Data['Errors'][] = "Ошибка! $exeption";
                }
            }
        } else {
            $this->Data['Errors'][] = "URL не является форматом www.mycompany.ru.";
        }
    }

    public function addEmail($email)
    {
        $email = htmlspecialchars(strip_tags(trim($email)));
        if (!$this->_emailCheck($email)) {
            $this->error = "Адрес $email не является форматом info@mycompany.ru.";
            return false;
        }
        if ($this->_nameDubleCheck($email) === false) {
            $this->error = "Адрес $email уже содержится в базе.";
            return false;
        }
        if ($this->dbh->query("INSERT INTO mail SET item = ?", [$email])) {
            return true;
        }
        // $this->Data['Success'][] = "Адрес $email успешно добавлен в базу.";
        return false;
    }

    public function editEmail($id, $newName)
    {
        $newName = htmlspecialchars(strip_tags(trim($newName)));
        if ((int)$id && $this->emailCheck($newName)) {
            try {
                $query_db = $this->dbh->query("UPDATE mail SET item = '{$newName}' WHERE id = '{$id}'");
                $this->Data['Success'][] = "Адрес $newName успешно изменен.";
            } catch (PDOException $exeption) {
                $this->Data['Errors'][] = "Ошибка! $exeption";
            }
        } else {
            $this->Data['Errors'][] = "Адрес $newName не является форматом info@mycompany.ru.";
        }
    }

    public function delEmail($id)
    {
        if ((int)$id) {
            try {
                $query_db = $this->dbh->query("DELETE FROM mail WHERE id = $id");
                $this->Data['Success'][] = "Адрес $id успешно удален.";
                $this->Data['deleted'] = true;
            } catch (PDOException $exeption) {
                $this->Data['Errors'][] = "Ошибка! $exeption";
            }
        }
    }
    
    public function getEmail($id)
    {
        if ((int)$id) {
            try {
                $query_db = $this->dbh->query("SELECT * FROM mail WHERE id = $id");
                $query_db->setFetchMode(PDO::FETCH_ASSOC);
                $this->Data['Email'] = $query_db->fetch();
            } catch (PDOException $exeption) {
                $this->Data['Errors'][] = "Ошибка! $exeption";
            }
        }
    }

    public function getListEmails()
    {
        return $this->dbh->query("SELECT * FROM mail ORDER BY id LIMIT 10000")->assoc();
    }
    
    public function parseFolder()
    {
        if (is_dir($this->dir1)) {
            $d = opendir($this->dir1);
            while (($e = readdir($d)) !== false) {
                $result = $this->emailRegularize(@file_get_contents($this->dir1.$e));
                foreach ($result[0] as $key => $value) {
                    if ($this->nameDubleCheck($value) !== false) {
                        try {
                            $query_db = $this->dbh->query("INSERT INTO mail SET item = '{$value}'");
                            $this->Data['Success'][] = "Адрес $addEmail успешно добавлен в базу.";
                        } catch (PDOException $exeption) {
                            $this->Data['Errors'][] = "Ошибка! $exeption";
                        }
                    } else {
                        $this->Data['Errors'][] = "Адрес $addEmail уже содержится в базе.";
                        continue;
                    }
                }
                @copy($this->dir1.$e, $this->dir2.$e);
                @unlink($this->dir1.$e);
            }
        }
    }
}
