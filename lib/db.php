<?php
class DB {
	private $sql;
	private $stmts = array();
	
	public static function connect($host, $user, $pass, $database, $charset="UTF-8") {
		$db = new DB();
		$db->sql = new mysqli($host, $user, $pass, $database);
		if($db->sql->connect_errno)
			return null;
		$db->sql->set_charset($charset);
		return $db;
	}
	public function database($db) {
		$this->sql->select_db($db);
	}
	public function charset($charset) {
		$this->sql->set_charset($charset);
	}
	public function close() {
		$this->sql->close();
	}
	
	public function query($query) {
		$status = $this->sql->query($query);
	}
	public function prepare($name, $query) {
		$this->stmts[$name] = array('stmt'=>null, 'params'=>array());
		$this->stmts[$name]['stmt'] = $this->sql->prepare($query);
		return ($this->stmts[$name]['stmt'])? true : false;
	}
	public function execute($name) {
		if(count($this->stmts[$name]['params'])) {
			$types = "";
			$params = array();
			foreach($this->stmts[$name]['params'] as $item) {
				$this->stmts[$name]['stmt']->bind_param($item[0], $item[1]);
				$types .= $item[0];
				$params []= &$item[1];
			}
			$params = array_merge(array($types), $params);
			call_user_func_array(array($this->stmts[$name]['stmt'], 'bind_param'), $params);
		}
		
		$this->stmts[$name]['stmt']->execute();
		$result = $this->stmts[$name]['stmt']->get_result();
		if($result === false)
			return false;
		else {
			$rows = array();
			while($row = $result->fetch_array(MYSQLI_ASSOC))
				$rows []= $row;
			return (count($rows))? $rows : null;
		}
	}
	public function param($name, $type, $value) {
		$i = count($this->stmts[$name]['params']);
		$this->stmts[$name]['params'] []= array($type, $value);
	}
	
	public function id($name) {
		return $this->sql->insert_id;
	}
	public function error() {
		return $this->sql->error;
	}
}