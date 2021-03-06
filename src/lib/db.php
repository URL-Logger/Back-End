<?php
class DB {
	private $sql;
	private $stmts = array();
	
	public static function connect($host, $user, $pass, $database) {
		$db = new DB();
		$db->sql = new mysqli($host, $user, $pass, $database);
		if($db->sql->connect_errno)
			return null;
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
		$result = $this->sql->query($query);
		if(is_bool($result)) {
			return $result;
		}
		else {
			$rows = array();
			while($row = $result->fetch_array(MYSQLI_ASSOC))
				$rows []= $row;
			return (count($rows))? $rows : null;
		}
	}
	public function prepare($name, $query) {
		$this->stmts[$name] = array('stmt'=>null, 'params'=>array(), 'result'=>null);
		$this->stmts[$name]['stmt'] = $this->sql->prepare($query);
		return ($this->stmts[$name]['stmt'])? true : false;
	}
	public function execute($name) {
		if(count($this->stmts[$name]['params'])) {
			$types = "";
			$params = array();
			for($i=0; $i<count($this->stmts[$name]['params']); ++$i) {
				$types .= $this->stmts[$name]['params'][$i][0];
				$params []= &$this->stmts[$name]['params'][$i][1];
			}
			$params = array_merge(array($types), $params);
			call_user_func_array(array($this->stmts[$name]['stmt'], 'bind_param'), $params);
		}
		
		$this->stmts[$name]['stmt']->execute();
		$result = $this->stmts[$name]['stmt']->get_result();
		$this->stmts[$name]['params'] = array();
		if($result === false) {
			$this->stmts[$name]['result'] = null;
			return null;
		}
		else {
			$this->stmts[$name]['result'] = $result;
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
	public function fields($name) {
		if(isset($this->stmts[$name]) && $this->stmts[$name]['result'] !== null) {
			$fields = array();
			$info = $this->stmts[$name]['result']->fetch_fields();
			foreach($info as $entry)
				$fields []= $entry->name;
			return $fields;
		}
		else
			return null;
	}
	
	public function id() {
		return $this->sql->insert_id;
	}
	public function error() {
		return $this->sql->error;
	}
}