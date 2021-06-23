<?php
	/*
	* Wecor PHP - SQL Query Builder
	* V=1.1 - Ulises Rendon
	*/
	
	namespace wecor;
	
	class DBWhereAux{

		protected $method;
		protected $table;
		protected $filters;
		public $sql;

		public function __construct($method = '', $table = ''){
			$this->table = DBConnector::escape($table);
			$this->metod = DBConnector::escape(strtolower($method));
		}

		public function whereBase($cond = null, $operator = null, $value = null, $logic){
			if( is_string($cond) && !is_null($operator) && !is_null($value) ){
				if( empty($this->filters) )
					$this->filters = "{$cond} {$operator} '{$value}'";
				else $this->filters .= " {$logic} {$cond} {$operator} '{$value}'";
			}else if( is_string($cond) ){
				if( empty($this->filters) ) $this->filters = $cond;
				else $this->filters .= " {$logic} {$cond}";
			}else if( is_callable($cond) ){
				$instanceaux = new DBWhereAux;
				$cond($instanceaux);
				if( empty($this->filters) )
					$this->filters = $instanceaux->getFilters();
				else $this->filters .= " {$logic} ".$instanceaux->getFilters();
			}
		}

		public function where($cond = null, $operator = null, $value = null){
			$this->whereBase($cond, $operator, $value, 'AND');
			return $this;
		}

		public function orWhere($cond = null, $operator = null, $value = null){
			$this->whereBase($cond, $operator, $value, 'OR');
			return $this;
		}

		private function getFilters(){
			return "({$this->filters})";
		}
	}
	class DB extends DBWhereAux{

		private $columns = '';
		private $limit = '';
		private $joins = '';
		private $order = '';
		private $values = '';
		private $toset = '';

		public function __construct($method = '', $table = ''){
			$this->table = $table;
			$this->metod = $method;
		}

		public static function __callStatic($name, $arguments){
			$instance = new self( $name, $arguments[0] );
			return $instance;
		}

		public function columns($datos){
			$datos = DBConnector::escape($datos);
			if( is_array($datos) ) 
				$this->columns = implode(', ', $datos);
			return $this;
		}

		public function limit($limit = null, $offset = null){
			if( !is_null($limit) && !is_null($offset) ) $this->limit = " LIMIT {$offset}, {$limit}";
			else if( !is_null($limit) ) $this->limit = " LIMIT {$limit}";
			return $this;
		}

		public function join($table, $table1, $cond, $table2){
			$this->joins .= " INNER JOIN {$table} ON {$table1} {$cond} {$table2}";
			return $this;
		}

		public function leftJoin($table, $table1, $cond, $table2){
			$this->joins .= " LEFT JOIN {$table} ON {$table1} {$cond} {$table2}";
			return $this;
		}

		public function rightJoin($table, $table1, $cond, $table2){
			$this->joins .= " RIGHT JOIN {$table} ON {$table1} {$cond} {$table2}";
			return $this;
		}

		public function order( $columns ){
			$this->order = " ORDER BY {$columns}";
			return $this;
		}

		public function fullTextSearch($columns, $search, $alias = null){
			$columns = implode(', ', $columns);
			$fulltexcols = "MATCH ({$columns}) AGAINST ('{$search}')";
			if( $alias ) $fulltexcols .= " AS {$alias}";
			if( empty($this->columns) ) $this->columns = $fulltexcols;
			else $this->columns .= ", {$fulltexcols}";
			$GLOBALS['columns'] = $columns;
			$GLOBALS['search'] = $search;
			$this->where(function($query){
				$query->where(
					"MATCH ({$GLOBALS['columns']}) AGAINST ('{$GLOBALS['search']}')"
				);
			});
			return $this;
		}

		private function valuesAux( $values ){
			$values = DBConnector::escape($values);
			foreach ($values as $k => $v) $values[$k] = "'{$v}'";
			$values = implode(', ', $values);
			if( !empty($this->values) ) $this->values .= ', ';
			$this->values .= "({$values})";
		}

		public function values( $values ){
			$is_arraybi = false;
			foreach ($values as $v){
				if( is_array($v) ) $is_arraybi = true;
				break;
			}
			if( $is_arraybi ){
				foreach ($values as $v) $this->valuesAux( $v );
			}else $this->valuesAux( $values );
			return $this;
		}

		public function set( $data ){
			$toset = array();
			if( is_array($data) ){
				foreach ($data as $k => $v) {
					$k = DBConnector::escape($k);
					$v = DBConnector::escape($v);
					$toset[] = "{$k}='{$v}'";
				}
			}else $toset[] = $data;
			$this->toset = implode(', ', $toset);
			return $this;
		}

		public function getSelectSQL(){
			if( empty($this->filters) ) $this->filters = 1;
			if( empty($this->columns) ) $this->columns = '*';
			$this->sql = "SELECT {$this->columns} FROM {$this->table}{$this->joins} WHERE {$this->filters}{$this->order}{$this->limit}";
			return $this->sql;
		}

		public function getInsertSQL(){
			if( !empty( $this->columns ) ) $this->columns = "({$this->columns})";
			$this->sql = "INSERT INTO {$this->table}{$this->columns} VALUES{$this->values}";
			return $this->sql;
		}

		public function getDeleteSQL(){
			if( empty($this->filters) ) $this->filters = 1;
			$this->sql = "DELETE {$this->table} FROM {$this->table}{$this->joins} WHERE {$this->filters}";
			return $this->sql;
		}

		public function getUpdateSQL(){
			if( empty($this->filters) ) $this->filters = 1;
			$this->sql = "UPDATE {$this->table}{$this->joins} SET {$this->toset} WHERE {$this->filters}";
			return $this->sql;
		}

		public function getSQL(){
			if( $this->metod == 'insert' ) $this->getInsertSQL();
			else if( $this->metod == 'select' ) $this->getSelectSQL();
			else if( $this->metod == 'delete' ) $this->getDeleteSQL();
			else if( $this->metod == 'update' ) $this->getUpdateSQL();
			return $this->sql;
		}

		public function send(){
			$this->getSQL();
			return DBConnector::sendQuery($this->sql);
		}

		public function get(){
			$this->getSQL();
			return DBConnector::query($this->sql);
		}

		public function first(){
			$result = DBConnector::sendQuery($this->getSQL());
			if( is_object($result) ) return DBConnector::fetchAssoc($result);
			return array();
		}
	}
