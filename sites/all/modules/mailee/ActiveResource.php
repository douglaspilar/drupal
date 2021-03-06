<?php

/**
 * Basic implementation of the Ruby on Rails ActiveResource REST client.
 * Intended to work with RoR-based REST servers, which all share similar
 * API patterns.
 *
 * Usage:
 *
 * <?php
 *
 * require_once ('ActiveResource.php');
 *
 * class Song extends ActiveResource {
 *     var $site = 'http://localhost:3000/';
 *     var $element_name = 'songs';
 * }
 *
 * // create new item
 * $song = new Song (array ('artist' => 'Joe Cocker', 'title' => 'A Little Help From My Friends'));
 * $song->save ();
 *
 * // fetch and update an item
 * $song->find (44)->set ('title', 'The River')->save ();
 *
 * // line by line
 * $song->find (44);
 * $song->title = 'The River';
 * $song->save ();
 *
 * // get all songs
 * $songs = $song->find ('all');
 *
 * // delete a song
 * $song->find (44);
 * $song->destroy ();
 *
 * // custom method
 * $songs = $song->get ('by_year', array ('year' => 1999));
 *
 * ?>
 *
 * @author John Luxford <lux@dojolearning.com>
 * @version 0.1 alpha
 * @license http://opensource.org/licenses/lgpl-2.1.php
 */
class ActiveResource {
	/**
	 * The REST site address, e.g., http://user:pass@domain:port/
	 */
	var $site = false;

	/**
	 * The remote collection, e.g., person or things
	 */
	var $element_name = false;

	/**
	 * The data of the current object, accessed via the anonymous get/set methods.
	 */
	var $_data = array ();

	/**
	 * An error message if an error occurred.
	 */
	var $error = false;

	/**
	 * The error number if an error occurred.
	 */
	var $errno = false;

	/**
	 * Constructor method.
	 */
	function __construct ($data = array ()) {
		$this->_data = $data;
	}

	/**
	 * Saves a new record or updates an existing one via:
	 *
	 * POST /collection.xml
	 * PUT  /collection/id.xml
	 */
	function save () {
		if (isset ($this->_data['id'])) {
		    return $this->_send_and_receive ($this->site . $this->element_name . '/' . $this->_data['id'] . '.xml', 'PUT', $this->_data); // update
		}
		return $this->_send_and_receive ($this->site . $this->element_name . '.xml', 'POST', $this->_data); // create
	}

	/**
	 * Deletes a record via:
	 *
	 * DELETE /collection/id.xml
	 */
	function destroy () {
		return $this->_send_and_receive ($this->site . $this->element_name . '/' . $this->_data['id'] . '.xml', 'DELETE');
	}

	/**
	 * Finds a record or records via:
	 *
	 * GET /collection/id.xml
	 * GET /collection.xml
	 */
	function find ($id = false, $options = null) {
		if (! $id) {
			$id = $this->_data['id'];
		}
		if ($id == 'all') {
			return $this->_send_and_receive ($this->site . $this->element_name . '.xml', 'GET');
		}
		if ($id == 'first') {
			$req = $this->site . $this->element_name . '.xml';
	  	if (count ($options) > 0) {

	  		$req .= '?' . http_build_query ($options);
	  	}
	  	//die("$req\n\n");
	  	return $this->_send_and_receive($req, 'GET');

		}
		return $this->_send_and_receive ($this->site . $this->element_name . '/' . $id . '.xml', 'GET');
	}

	/**
	 * Gets a specified custom method on the current object via:
	 *
	 * GET /collection/id/method.xml
	 * GET /collection/id/method.xml?attr=value
	 */
	function get ($method, $options = array ()) {
		$req = $this->site . $this->element_name . '/' . $this->_data['id'] . '/' . $method . '.xml';
		if (count ($options) > 0) {
			$req .= '?' . http_build_query ($options);
		}
		return $this->_send_and_receive ($req, 'GET');
	}

	/**
	 * Posts to a specified custom method on the current object via:
	 *
	 * POST /collection/id/method.xml
	 */
	function post ($method, $options = array ()) {
		return $this->_send_and_receive ($this->site . $this->element_name . '/' . $this->_data['id'] . '/' . $method . '.xml', 'POST', $options);
	}

	/**
	 * Puts to a specified custom method on the current object via:
	 *
	 * PUT /collection/id/method.xml
	 */
	function put ($method, $options = array ()) {
		return $this->_send_and_receive ($this->site . $this->element_name . '/' . $this->_data['id'] . '/' . $method . '.xml', 'PUT', $options);
	}

	/**
	 * Build the request, call _fetch() and parse the results.
	 */
	function _send_and_receive ($url, $method, $data = array ()) {
	  //echo("**********\nBuscando... $url\n*************");
		$params = '';
		$el = substr ($this->element_name, 0, -1);
		foreach ($data as $k => $v) {
			if ($k != 'id' && $k != 'created-at' && $k != 'updated-at') {
				$params .= '&' . $el . '[' . $k . ']=' . rawurlencode ($v);
			}
		}
		$params = substr ($params, 1);

		$res = $this->_fetch ($url, $method, $params);

		list ($headers, $res) = explode ("\r\n\r\n", $res);

		if (! $res) {
			return $this;
		} elseif ($res == ' ') {
			$this->error = 'Empty reply';
			return $this;
		}

		// parse XML response
		//pr($res);
		$xml = new SimpleXMLElement ($res);

		if ($xml->getName () == $this->element_name) {
			// multiple
			$res = array ();
			$cls = get_class ($this);
			foreach ($xml->children () as $child) {
				$obj = new $cls;
				foreach ((array) $child as $k => $v) {
					if (isset ($v['nil']) && $v['nil'] == 'true') {
						continue;
					} else {
						$obj->_data[$k] = $v;
					}
				}
				$res[] = $obj;
			}
			return $res;
		} elseif ($xml->getName () == 'errors') {
			// parse error message
			$this->error = $xml->error;
			return false;
		}

		foreach ((array) $xml as $k => $v) {
			if (isset ($v['nil']) && $v['nil'] == 'true') {
				continue;
			} else {
				$this->_data[$k] = $v;
			}
		}
		return $this;
	}

	/**
	 * Fetch the specified request via cURL.
	 */
	function _fetch ($url, $method, $params) {
		if (! extension_loaded ('curl')) {
			$this->error = 'cURL extension not loaded.';
			return false;
		}
		$ch = curl_init ();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_MAXREDIRS, 3);
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_VERBOSE, 0);
		curl_setopt ($ch, CURLOPT_HEADER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 10);
		switch ($method) {
			case 'POST':
				curl_setopt ($ch, CURLOPT_POST, 1);
				curl_setopt ($ch, CURLOPT_POSTFIELDS, $params);
				curl_setopt ($ch, CURLOPT_HTTPHEADER, array ("Content-Type: application/x-www-form-urlencoded\n"));
				break;
			case 'DELETE':
				curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
				break;
			case 'PUT':
				curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt ($ch, 'HTTP_X_HTTP_METHOD_OVERRIDE', 'PUT');
				curl_setopt ($ch, CURLOPT_POSTFIELDS, $params);
				curl_setopt ($ch, CURLOPT_HTTPHEADER, array ("Content-Type: application/x-www-form-urlencoded\n"));
				break;
			case 'GET':
			default:
			    break;
		}
		$res = curl_exec ($ch);
		if (! $res) {
			$this->errno = curl_errno ($ch);
			$this->error = curl_error ($ch);
			curl_close ($ch);
			return false;
		}
		curl_close ($ch);
		return $res;
	}

	/**
	 * Getter for internal object data.
	 */
	function __get ($k) {
		if (isset ($this->_data[$k])) {
			return $this->_data[$k];
		}
		return $this->{$k};
	}

	/**
	 * Setter for internal object data.
	 */
	function __set ($k, $v) {
		if (isset ($this->_data[$k])) {
			$this->_data[$k] = $v;
			return;
		}
		$this->{$k} = $v;
	}

	/**
	 * Quick setter for chaining methods.
	 */
	function set ($k, $v = false) {
		if (! $v && is_array ($k)) {
			foreach ($k as $key => $value) {
				$this->_data[$key] = $value;
			}
		} else {
			$this->_data[$k] = $v;
		}
		return $this;
	}
}

