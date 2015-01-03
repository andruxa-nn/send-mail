<?php
require("./config.php");

class SendMail extends Config {

	function __construct() {
		try {
			$this->dbh = new PDO("mysql:host=$this->db_host;dbname=$this->db_name", $this->db_user, $this->db_pass);
		} catch(PDOException $exeption) {
			echo $exeption->getMessage();
		}

		if (!is_dir($this->dir1) || !is_dir($this->dir2)) {
			@mkdir($this->dir1);
			@mkdir($this->dir2);
		}

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
        }
	}

	private function emailCheck($email) {
		return (preg_match('/([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})/im', $email)) ? true : false;
	}

	private function emailRegularize($mixedString) {
		preg_match_all('/([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})/im', $mixedString, $result);
		return $result;
	}

	private function nameDubleCheck($email) {
		try {
			$query_db = $this->dbh->query("SELECT COUNT(*) FROM mail WHERE item = '{$email}'");
			$query_db->setFetchMode(PDO::FETCH_ASSOC);
			$result = $query_db->fetch();
		} catch( PDOException $exeption ) {
			$this->Data['Errors'][] = "Ошибка! $exeption";
		}
		if ($result["COUNT(*)"] > 0) return false;
	}

	public function addUrl($url) {
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

    public function addEmail($email) {
        $email = htmlspecialchars(strip_tags(trim($email)));
        if ($this->emailCheck($email)) {
            if ($this->nameDubleCheck($email) !== false) {
                try {
                    $query_db = $this->dbh->query("INSERT INTO mail SET item = '{$email}'");
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

	public function delEmail($id) {
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
    
    public function getEmail($id) {
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

	public function getListEmails() {
		$query_db = $this->dbh->query("SELECT * FROM mail ORDER BY id LIMIT 15000, 10000");
		$query_db->setFetchMode(PDO::FETCH_ASSOC);
		while($row = $query_db->fetch()) {
			$this->Data['Emails'][$row['id']] = $row['item'];
		}
	}
    
    public function parseFolder() {
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

?>