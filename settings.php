<?php
error_reporting(E_ALL ^ E_NOTICE);
//phpinfo();
define(siteUrl, 'http://127.0.0.1');
define(projectName, 'База E-mail адресов');

class Settings {
	public $db_host = '127.0.0.1';
	public $db_user = 'root';
	public $db_pass = '';
	public $dir1 = 'C:\\xampp\\htdocs\\test\\folder1\\';
	public $dir2 = 'C:\\xampp\\htdocs\\test\\folder2\\';
	public $Data = array();
	public $dbh = '';

	function __construct() {
		try {
			$this->dbh = new PDO("mysql:host=$this->db_host;dbname=emails", $this->db_user, $this->db_pass);
			//$query_db = $dbh->query("CREATE TABLE mail(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, item TEXT NOT NULL)");
		} catch(PDOException $exeption) {
			echo $exeption->getMessage();
		}
		
		/*
		mysql_connect($this->bd_host, $this->bd_user, $this->bd_pass) or die("Нет соединения с базой MySQL!");
		@mysql_query("CREATE DATABASE emails");
		mysql_select_db("emails");
		mysql_query("CREATE TABLE mail(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, item TEXT NOT NULL)");
		*/

		if( !is_dir($this->dir1) || !is_dir($this->dir2) ) {
			@mkdir($this->dir1);
			@mkdir($this->dir2);
		}

		if( is_dir($this->dir1) ) {
			$d = opendir($this->dir1);
			while( ($e = readdir($d)) !== false ) {
				$result = $this->emailRegularize(@file_get_contents($this->dir1.$e));
				

				foreach( $result[0] as $key => $value ) {
					/*
					if( $this->nameCheck($value) !== false ) {
						try {
							$query_db = $this->dbh->query("INSERT INTO mail SET item = '{$value}'");
							$this->Data['Success'][] = "Адрес $addEmail успешно добавлен в базу.";
						} catch( PDOException $exeption ) {
							$this->Data['Errors'][] = "Ошибка! $exeption";
						}
					} else {
						$this->Data['Errors'][] = "Адрес $addEmail уже содержится в базе.";
						continue;
					}
					*/
					print_r($result[0]);
				}
				/*
				@copy($this->dir1.$e, $this->dir2.$e);
				@unlink($this->dir1.$e);
				
				*/
			}
		}

		if( $_REQUEST['addEmail'] ) $this->addEmail(htmlspecialchars(strip_tags(trim($_REQUEST['addEmail']))));
		if( $_REQUEST['addUrl'] ) $this->addUrl(htmlspecialchars(strip_tags(trim($_REQUEST['addUrl']))));
		if( (int)$_REQUEST['delEmail'] ) $this->delEmail($_REQUEST['delEmail']);
		if( (int)$_REQUEST['editEmail'] ) $this->editItem($_REQUEST['editEmail'], htmlspecialchars(strip_tags(trim($_REQUEST['newName']))));
	}

	private function emailCheck($email) {
		if( preg_match('/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/is', $email) ) {
			return true;
		} else {
			return false;
		}
	}

	private function emailRegularize($emailsArray) {
		$chars = array(',', '>', ':', ';', '\'', '~', '!', '"', '#', '$', '%', '&', '(', ')', '<', '=', '?', '{', '}', '/');
		@preg_match_all('/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/is', trim(str_replace($chars, ' ', $emailsArray)), $result);
		return $result;
	}

	private function nameCheck($email) {
		try {
			$query_db = $this->dbh->query("SELECT COUNT(*) FROM mail WHERE item = '{$email}'");
			$query_db->setFetchMode(PDO::FETCH_ASSOC);
			$result = $query_db->fetch();
		} catch( PDOException $exeption ) {
			$this->Data['Errors'][] = "Ошибка! $exeption";
		}
		if( $result["COUNT(*)"] > 0 ) return false;
	}

	private function addEmail($email) {
		if( $this->emailCheck($email) ) {
			if( $this->nameCheck($email) !== false ) {
				try {
					$query_db = $this->dbh->query("INSERT INTO mail SET item = '{$email}'");
					$this->Data['Success'][] = "Адрес $email успешно добавлен в базу.";
				} catch( PDOException $exeption ) {
					$this->Data['Errors'][] = "Ошибка! $exeption";
				}
			} else {
				$this->Data['Errors'][] = "Адрес $email уже содержится в базе.";
			}
		} else {
			$this->Data['Errors'][] = "Адрес $email не является форматом info@mycompany.ru.";
		}
	}

	private function addUrl($url) {
		if( preg_match('/(([a-z0-9\-\.]+)?[a-z0-9\-]+(!?\.[a-z]{2,6}))/is', $url) ) {
			$result = $this->emailRegularize(file_get_contents($url));
			print_r($result);
			foreach( $result[0] as $key => $value ) {
				try {
					$query_db = $this->dbh->query("INSERT INTO mail SET item = '{$item}'");
					$this->Data['Success'][] = "Адрес $addEmail успешно добавлен в базу.";
				} catch( PDOException $exeption ) {
					$this->Data['Errors'][] = "Ошибка! $exeption";
				}
			}
		} else {
			$this->Data['Errors'][] = "URL не является форматом www.mycompany.ru.";
		}
	}

	private function editItem($id, $newName) {
		echo $id . " " . $newName;
		if( (int)$id && $this->emailCheck($newName) ) {
			try {
				$query_db = $this->dbh->query("UPDATE mail SET item = '{$newName}' WHERE id = '{$id}'");
				$this->Data['Success'][] = "Адрес $newName успешно изменен.";
			} catch( PDOException $exeption ) {
				$this->Data['Errors'][] = "Ошибка! $exeption";
			}
		} else {
			$this->Data['Errors'][] = "Адрес $newName не является форматом info@mycompany.ru.";
		}
	}

	public function delEmail($id) {
		if( (int)$id ) {
			try {
				$query_db = $this->dbh->query("DELETE FROM mail WHERE id = $id");
				$this->Data['Success'][] = "Адрес $id успешно удален.";
			} catch( PDOException $exeption ) {
				$this->Data['Errors'][] = "Ошибка! $exeption";
			}
		}
	}

	public function getListEmails() {
		$query_db = $this->dbh->query("SELECT * FROM mail ORDER BY id");
		$query_db->setFetchMode(PDO::FETCH_ASSOC);
		while($row = $query_db->fetch()) {
			$this->Data['Emails'][$row['id']] = $row['item'];
		}
	}
}

?>