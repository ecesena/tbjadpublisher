<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * You can't load the Model class using the autoload file, so we have to
 * include it here for the ActiveRecord class to inherit from
 */
require (BASEPATH . '/libraries/Model.php');

/**
 * CodeIgniter ActiveRecord Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Matthew Pennell
 * @author		Anton Muraviev
 * @version		0.5.2
 * @link		http://codeigniter.com/wiki/ActiveRecord_Class_Mod/
 */

/**
 * Define some global types of search
 */
if ( !defined('ALL') ) define('ALL', 'all');
if ( !defined('IS_NULL') ) define ('IS_NULL', ' is null');
if ( !defined('NOT_NULL') ) define ('NOT_NULL', ' <> ""');

class ActiveRecord extends Model {

	/**
	 * Constructor
	 *
	 * @access	public
	 */
	function ActiveRecord ()
	{
		parent::Model();
		$this->_class_name = strtolower(get_class($this));
		$this->_table = $this->_class_name . 's';
		$this->_belongs_to = array();
		$this->_has_many = array();
		$this->_has_one = array();
		$this->_has_and_belongs_to_many = array(); 

		$this->_select = array();
		
		log_message('debug', "ActiveRecord Class Initialized");
	}
	
	/**
	 * __call
	 *
	 * Catch-all function to capture method requests for Active Record-style
	 * functions and pass them off to private methods. If no function is
	 * recognised, this acts as a getter/setter (depending on whether any
	 * arguments were passed in).
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @return	function || void
	 * @link	http://uk.php.net/manual/en/language.oop5.overloading.php
	 */
	function __call ($method, $args)
	{
		$watch = array('find_by_','find_all_by_','joining_','counting_','concatenating_','fetch_related_');
		
		foreach ( $watch as $found )
		{
			if ( stristr($method, $found) )
			{
				return $this->{'_' . substr($found, 0, -1)}(str_replace($found, '', $method), $args);
			}
		}
	}

	/**
	 * exists
	 *
	 * Boolean check to see if a record was returned from a query
	 *
	 * @access    public
	 * @return    bool
	 */
	function exists ()
	{
		return isset($this->id);
	}

	/**
	 * create
	 *
	 * Shorthand way to create and instantiate a new record in one go.
	 * Pass in a hash of key/value pairs that correspond to the columns
	 * in the relevant table.
	 *
	 * @access	public
	 * @param	array
	 * @return	object
	 */
	function create ($args)
	{
		if ( $this->db->insert($this->_table, $args) )
		{
			$return = new $this->_class_name();
			
			foreach ($args as $key => $value)
			{
				$return->$key = "$value";
			}
			$return->id = $this->db->insert_id();
			
			return $return;
		} 
		else
		{
			log_message('error', $this->db->last_query());
		}
	}

	/**
	 * delete
	 *
	 * Simple method to delete the current object's record from the database.
	 *
	 * @access	public
	 * @return	void
	 */
	function delete ()
	{
		if ( $this->db->delete($this->_table, array('id' => $this->id)) )
		{
			unset($this);
		}
		else
		{
			log_message('error', $this->db->last_query());
		}
	}

	/**
	 * delete_all
	 *
	 * Delete all records from the associated table. This method does not
	 * need to called on an instantiated object.
	 *
	 * @access	public
	 * @return	void
	 */
	function delete_all ()
	{
		if ( $this->exists() )
		{
			return false; //some safeguards against deleting the table
		}
		else if (!$this->db->query('DELETE FROM ' . $this->_table))
		{
			log_message('error', $this->db->last_query());
		}
		
		return true;
	}

	/**
	 * save
	 *
	 * Similar to the create() method, but this function assumes that the
	 * corresponding properties have been set on the current object for each
	 * table column.
	 *
	 * Oct 25 - check: Check whether object has property set.
	 *
	 * @access	public
	 * @return	object
	 */
	function save ()
	{
		$data = array();
		
		foreach ( $this->db->list_fields($this->_table) as $field )
		{
			if ( $field != 'id' && isset($this->$field) ) 
			{
				$data["$field"] = $this->$field;
			}
		}
		
		if ( $this->db->insert($this->_table, $data) )
		{
			$this->id = $this->db->insert_id();
		}
		else
		{
			log_message('error', $this->db->last_query());
		}
	}

	/**
	 * update
	 *
	 * Similar to the save() method, except that it will update the row
	 * corresponding to the current object.
	 *
	 * Oct 25 - chromice: Check whether object has a property set.
	 *
	 * @access	public
	 * @return	void
	 */
	function update ()
	{
		$data = array();
		
		foreach ( $this->db->list_fields($this->_table) as $field )
		{
			if ( $field != 'id' && isset($this->$field) )
			{
				$data["$field"] = $this->$field;
			}
		}

		$this->db->where('id', $this->id);
		
		if ( !$this->db->update($this->_table, $data) )
		{
			log_message('error', $this->db->last_query());
		}
	}
	/** 
	 * _compile
	 *
	 * This function compiles a list of fields to select. 
	 *
	 * @author	Anton Muraviev
	 * @access	private
	 * @return	void
	 */
	function _compile ()
	{
		$this->_select[] = $this->_table . '.*';
		$this->db->select(implode(',',$this->_select));
	}
	
	/**
	 * find
	 *
	 * Basic find function. Pass in a numeric id to find that table row.
	 *
	 * @access	public
	 * @param	int
	 * @return	object || array
	 */
	function find ($id = null)
	{
		if ( is_null($id) ) 
		{
			$this->_compile();
			$query = $this->db->from($this->_table)->get();
			
			return $this->_return_one($query);
		}
		else
		{
			$this->db->where($this->_table.'.id',$id);
			
			return $this->find();
		}
	}
	
	/**
	 * find_all ()
	 *
	 * Return all records.
	 *
	 */
	function find_all ($sortBy="")
	{
		$this->_compile();
		if($sortBy != "")
			$this->db->orderby($sortBy);
		
		$query = $this->db->from($this->_table)->get();
		
		return $this->_return_many($query);
	}
	
	/**
	 * _find_by
	 *
	 * Query by a particular field by passing in a string/int. You can also
	 * pass in an optional hash of additional query modifiers.
	 *
	 * NOTE: This function only ever returns the first record it finds! To
	 * find all matching records, use find_all_by_fieldname();
	 *
	 * @access	private
	 * @param	string
	 * @param	array
	 * @return	object
	 */
	function _find_by ($column, $query)
	{
		$column = $this->_table.'.'.$column;

		switch ( $query[0] )
		{
			case IS_NULL:
				$this->db->where($column . IS_NULL);
				break;
			case NOT_NULL:
				$this->db->where($column . NOT_NULL);
				break;
			default:
				$this->db->where($column, $query[0]);
		}

		return $this->find();
	}

	/**
	 * _find_all_by
	 *
	 * Same as _find_by() except this time it returns all matching records.
	 *
	 * There are some special search terms that you can use for particular searches:
	 * IS_NULL to find null or empty fields
	 * NOT_NULL to find fields that aren't empty or null
	 *
	 * By passing in a second parameter of an array of key/value pairs, you 
	 * can build more complex queries (of course, if it's getting too complex,
	 * consider creating your own function in the actual model class).
	 *
	 * @access	private
	 * @param	string
	 * @param	array
	 * @return	array
	 */
	function _find_all_by ($column, $query)
	{
		$column = $this->_table.'.'.$column;
		
		switch ( $query[0] )
		{
			case IS_NULL:
				$this->db->where($column . IS_NULL);
				break;
			case NOT_NULL:
				$this->db->where($column . NOT_NULL);
				break;
			default:
				$this->db->where($column, $query[0]);
		}
				
		return $this->find_all();
	}

	/**
	 * find_and_limit_by
	 *
	 * Basic find function but with limiting (useful for pagination).
	 * Pass in the number of records and the start index, and optionally
	 * an array, where the first index of the array is an array of
	 * modifiers for the query, and the second index is a JOIN statement
	 * (assuming one is needed).
	 *
	 * @access	public
	 * @param	int
	 * @param	int
	 * @param	array
	 * @return	array
	 */
	function find_and_limit_by ($num, $start)
	{
		$this->db->limit($num, $start);

		return $this->find_all();
	}
	
	/**
	 * _return_many
	 *
	 * Helper method that create an ActiveRecord wrapper for each 
	 * result row and adds them to an array.
	 *
	 * @author	Anton Muraviev
	 * @access	private
	 * @param	object
	 * @param	string
	 * @return	array
	 */
	function _return_many ($query, $custom_class_name = '')
	{
		$return = array();
		$columns = $query->list_fields();

		foreach ( $query->result() as $row )
		{
			if ( strlen($custom_class_name) > 0 )
				$x = new $custom_class_name;
			else
				$x = new $this->_class_name();
			
			foreach ( $columns as $column )
				$x->$column = $row->$column;

			$return[] = $x;
			$x = null;
		}

		return $return;
	}
	
	/**
	 * _return_one
	 *
	 * Helper method that create an ActiveRecord wrapper for the 
	 * result row and returns it.
	 *
	 * @author	Anton Muraviev
	 * @access	private
	 * @param	object
	 * @return	object
	 */
	function _return_one($query)
	{
		if ( $query->num_rows() > 0 ) 
		{
			$columns = $query->list_fields();
			$found = $query->row();
			$return = new $this->_class_name();
			
			foreach ( $columns as $column )
				$return->$column = $found->$column;

			return $return;
		} 
		else
		{
			return false;
		}
	}
	
	/**
	 * filtering
	 *
	 * This query modifier is a simple straightforward where-clause wrapper.
	 *
	 * You can chain this method with any find methods, e.g.
	 *   $authors->filtering('age >',25)->find_all();
	 *
	 * @access	public
	 * @param	string || array
	 * @param	string || int
	 * @return	object
	 */
	 function filtering($key, $value = NULL)
	 {
	 	$this->db->where($key,$value);
		
	 	return $this;
	 }

	/**
	 * ordering
	 *
	 * This query modifier is a simple straightforward orderby-clause wrapper.
	 *
	 * You can chain this method with any find methods, e.g.
	 *   $authors->ordering('id','desc')->find_all();
	 *
	 * @access	public
	 * @param	string || array
	 * @param	string
	 * @return	object
	 */
	 function ordering($key, $direction = 'ASC')
	 {
	 	$this->db->orderby($key,$direction);
		
	 	return $this;
	 }
	 
	/**
	 * searching
	 *
	 * This query modifier is a simple straightforward like-clause wrapper.
	 *
	 * You can chain this method with any find methods, e.g.
	 *   $authors->searching('name','John')->find_all();
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	object
	 */
	function searching($key, $value)
	{
		$this->db->like($key,$value);	
		 
		return $this;
	}
	
	/**
	 * count
	 *
	 * Count table rows.
	 *
	 * @author	Anton Muraviev
	 * @access	public
	 * @return	int
	 */
	function count ()
	{
		$query = $this->db->select('COUNT(*) AS count')->get($this->_table);
		
		if ( $query->num_rows() > 0 )
		{
			$result = $query->row();
			
			return $result->count;
		} 
		else
		{
			return 0;
		}
	}
	
	/**
	 * _counting
	 *
	 * You can count all rows of has_many and has_and_belongs_to_many relatives 
	 * with this query modifier. The number will be returned as 
	 * num_[relative_table_name]
	 *
	 * You can chain this method with any find methods, e.g.
	 *   $authors->counting_books()->find_all();
	 *
	 * @author	Anton Muraviev
	 * @access	private
	 * @param	string
	 * @return	object
	 */
	function _counting ($table,$args)
	{
		$inflected = isset($args[0]) ? $args[0] : '';
		
		if ( in_array($table, $this->_has_and_belongs_to_many) )
		{
			$this->_join_many($this->_table,$table,$this->_class_name,$inflected,true);
		}
		else if ( in_array($table, $this->_has_many) )
		{
			$this->_join_many($this->_table,$table,$this->_class_name);
		}
		else
		{
			return $this;
		}
		
		$this->_select[] = 'COUNT(' . $table . '.id) AS num_' . $table;
		$this->db->groupby($this->_table . '.id');
		
		return $this;
	}
	
	/**
	 * _concatenating
	 *
	 * You can concatenate a specific field of has_many and has_and_belongs_to_many
	 * relatives with this query modifier. The concatenated string will be returned
	 * as a field of the same name as the relative table.
	 *
	 * You can chain this method with any find methods, e.g.
	 *   $this->concatenating_authors('name')->find_all();
	 *
	 * @author	Anton Muraviev
	 * @access	private
	 * @param	string
	 * @param	array
	 * @return	object
	 */
	function _concatenating ($table,$args)
	{
		$field = $args[0];
		$inflected = isset($args[1]) ? $args[1] : '';
		$distinct = isset($args[2]) ? $args[2] : FALSE;
		$separator = isset($args[3]) ? $args[3] : ', ';
		
		if ( in_array($table, $this->_has_and_belongs_to_many) )
		{
			$this->_join_many($this->_table,$table,$this->_class_name,$inflected,true);
		}
		else if ( in_array($table, $this->_has_many) )
		{
			$this->_join_many($this->_table,$table,$this->_class_name);
		}
		else
		{
			return $this;
		}

		$this->_select[] = 'GROUP_CONCAT(' . 
			($distinct ? 'DISTINCT ' : '') .
			$table . '.' . $field . 
			' ORDER BY ' . $table . '.' . $field .
			' SEPARATOR \'' . str_replace('\'','\\\'',$separator) . '\'' .
			') AS ' . $table;

		$this->db->groupby($this->_table . '.id');

		return $this;
	}
	
	/**
	 * create_relationship
	 *
	 * Create a relationship (i.e. an entry in the relationship table)
	 * between the current object and another one passed in as the first
	 * argument. Or pass in two objects as an anonymous call.
	 *
	 * @access	public
	 * @param	object
	 * @param	object
	 * @return	void
	 */
	function create_relationship ($a, $b = '')
	{
		if ( $b == '' )
		{
			$relationship_table = ($this->_table < $a->_table) ? $this->_table . '_' . $a->_table : $a->_table . '_' . $this->_table;
			
			$this->db->query('
				INSERT INTO ' . $relationship_table . ' 
					(' . $this->_class_name . '_id, ' . $a->_class_name . '_id) 
				VALUES 
					(' . $this->id . ', ' . $a->id . ')
			');
		}
		else
		{
			$relationship_table = ($a->_table < $b->_table) ? $a->_table . '_' . $b->_table : $b->_table . '_' . $a->_table;
			
			$this->db->query('
				INSERT INTO ' . $relationship_table . ' 
					(' . $a->_class_name . '_id, ' . $b->_class_name . '_id) 
				VALUES 
					(' . $a->id . ', ' . $b->id . ')
			');
		}
	}
	/**
	 * _join_many
	 *
	 * Performs a join for has_many (xref = false) and has_and_belongs_to_many
	 * (xref = true) relationship types. Class_b is not required for has_many 
	 * join.
	 *
	 * @author	Anton Muraviev
	 * @access	private
	 * @param	string
	 * @param	string
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	void
	 */
	function _join_many ($table_a, $table_b, $class_a = '', $class_b = '', $xref = FALSE)
	{
		if ( !$class_a )
			$class_a = substr($table_a, 0, -1);
		
		if ( !$class_b ) 
			$class_b = substr($table_b, 0, -1);

		if ( !$xref )
		{
			$this->db->join($table_b, $table_a . '.id = ' . $table_b . '.' . $class_a . '_id','left');
		}
		else
		{
			$relationship_table = ($table_a < $table_b) ? $table_a . '_' . $table_b : $table_b . '_' . $table_a;
			$this->db->join($relationship_table, $table_a . '.id = ' . $relationship_table . '.' . $class_a . '_id','left');
			$this->db->join($table_b, $table_b . '.id = ' . $relationship_table . '.' . $class_b . '_id','left');
		}
	}
	
	/**
	 * MODIFIED BY ANTHONY MACIEL 5-22-2008
	 * joining_related
	 *
	 * Joins all tables this object's table belongs to for the next query.
	 * You can also specify an array of tables to join.
	 *
	 * If you need custom inflection for your tables use respective array keys,
	 * e.g. array('singular_1' => 'plural_1', 'singular_2' => 'plural_2',
	 * 'plural_3'). This is valid for both $this->_blongs_to attribute and $custom 
	 * parameter.
	 *
	 * You can chain this method with any find methods, e.g.
	 *   $this->joining_related()->find_all();
	 *
	 * @author	Anton Muraviev
	 * @access	public
	 * @param	array
	 * @return	object
	 */
	function joining_related ($custom = array())
	{
		$tables = (sizeof($custom) > 0) ? $custom : $this->_belongs_to;
		foreach ( $tables as $inflected => $table )
		{
			if ( !is_string($inflected) )
				$inflected = substr($table, 0, -1);
			
			$infTbl = $inflected . $table;
			
			$select = array();
			
			foreach ( $this->db->list_fields($table) as $field )
			{
				if ( $field != 'id' )
				{
					$select[] = $infTbl . '.' . $field . ' AS ' . $inflected . '_' . $field;
				}
			}

			if ( sizeof($select) > 0 )
			{
				$this->_select[] = implode(',',$select);
				$this->db->join($table . " AS " . $infTbl, $this->_table . '.' . $inflected . '_id = ' . $infTbl . '.id','left');
			}
		}
		
		return $this;
	}
	
	/**
	 * _joining_related
	 *
	 * Helper function that calls joining_related for one particular table.
	 *
	 * You can chain this method with any find methods, e.g.
	 *   $this->joining_related_objects()->find_all();
	 *
	 * @author	Anton Muraviev
	 * @access	private
	 * @param	string
	 * @param	array
	 * @return	object
	 */
	function _joining ($table, $args)
	{
		if ( isset($args[0]) && is_string($args[0]) )
		{
			$this->joining_related(array($args[0] => $table));
		}
		else
		{
			$this->joining_related(array($table));
		}
		
		return $this;
	}

	/**
	 * _fetch_related
	 *
	 * Fetch all related records using the relationship table to establish
	 * relationships. Results are stored as an array of objects in a
	 * property corresponding to the name of the related objects. If the 
	 * singular of the related object isn't logical, pass it in as the
	 * first argument, e.g. $woman->fetch_related_men('man');
	 *
	 * @access	private
	 * @param	string
	 * @param	string
	 * @return	void
	 */

	function _fetch_related ($table, $inflected)
	{
		$inflected = ($inflected) ? $inflected[0] : substr($table, 0, -1);

		if (in_array($table,$this->_belongs_to) )
		{
			$foreign_key = $inflected . '_id';
			$sql='SELECT * FROM ' . $table . ' WHERE id = ' . $this->$foreign_key;
			$query = $this->db->query($sql);
		}
		else if ( in_array($table,$this->_has_many) || in_array($table,$this->_has_one) )
		{
			$sql='SELECT * FROM ' . $table . ' WHERE '. $this->_class_name .'_id = '.$this->id;
			$query = $this->db->query($sql);
		}
		else if ( in_array($table,$this->_has_and_belongs_to_many) )
		{
			$relationship_table = ($this->_table < $table) ? $this->_table . '_' . $table : $table . '_' . $this->_table;
			$sql='
				SELECT
					' . $table . '.*
				FROM
					' . $table . '
				LEFT JOIN
					' . $relationship_table . '
				ON
					' . $table . '.id = ' . $inflected . '_id
				LEFT JOIN
					' . $this->_table . '
				ON
					' . $this->_table . '.id = ' . $this->_class_name . '_id
				WHERE
					' . $this->_table . '.id = ' . $this->id;

			$query = $this->db->query($sql);
		}
		else 
		{
			return false;
		}
		
		if (class_exists(ucfirst($inflected)))
			return $this->_return_many($query,ucfirst($inflected));
		else
			return $query->result();
	}
}

?>