<?php

class EntityNotFoundException extends Exception {
	private $entity;
	private $id;
	function __construct($entity, $id)
	{
		parent::__construct ("Object '$entity'#'$id' does not exist");
		$this->entity = $entity;
		$this->id = $id;
	}
	function getEntity()
	{
		return $this->entity;
	}
	function getId()
	{
		return $this->id;
	}
}

class RealmNotFoundException extends Exception {
	private $realm;
	function __construct($realm)
	{
		parent::__construct ("Realm '$realm' does not exist");
		$this->realm = $realm;
	}
	function getRealm()
	{
		return $this->realm;
	}
}



class NotUniqueException extends Exception
{
	private $subject;
	function __construct ($what = NULL)
	{
		$this->subject = $what;
		parent::__construct ('Cannot add duplicate record' . ($what === NULL ? '' : " (${what} must be unique)"));
	}
	function getSubject()
	{
		return $this->subject;
	}
}

class InvalidArgException extends Exception
{
	private $name;
	private $value;
	function __construct ($name, $value)
	{
		parent::__construct ("Argument '${name}' of value '".var_export(${value},true)."' is invalid");
		$this->name = $name;
		$this->value = $value;
	}
	function getName()
	{
		return $this->name;
	}
	function getValue()
	{
		return $this->value;
	}
}

function dumpArray($arr)
{
	echo '<table class="exceptionParametersDump">';
	foreach($arr as $key=>$value)
	{
		echo "<tr><th>$key</th><td>$value</td></tr>";
	}
	echo '</table>';
}

function print404($e)
{
	header("HTTP/1.1 404 Not Found");
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n";
	echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">'."\n";
	echo "<head><title> Exception </title>\n";
	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
	echo "<link rel=stylesheet type='text/css' href=pi.css />\n";
#	echo "<link rel=icon href='" . getFaviconURL() . "' type='image/x-icon' />";
	echo '</head> <body>';
	echo '<h2>Object: '.$e->getEntity().'#'.$e->getId().' not found</h2>';
	echo '</body></html>';

}

function printGenericException($e)
{
	header("HTTP/1.1 500 Internal Server Error");
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n";
	echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">'."\n";
	echo "<head><title> Exception </title>\n";
	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
	echo "<link rel=stylesheet type='text/css' href=pi.css />\n";
	echo "<link rel=icon href='" . getFaviconURL() . "' type='image/x-icon' />";
	echo '</head> <body>';
	echo '<h2>Uncaught exception: '.get_class($e).'</h2><code>'.$e->getMessage().'</code> (<code>'.$e->getCode().'</code>)';
	echo '<p>at file <code>'.$e->getFile().'</code>, line <code>'.$e->getLine().'</code></p><pre>';
	print_r($e->getTrace());
	echo '</pre>';
	echo '<h2>Parameters:</h2>';
	echo '<h3>GET</h3>';
	dumpArray($_GET);
	echo '<h3>POST</h3>';
	dumpArray($_POST);
	echo '<h3>COOKIE</h3>';
	dumpArray($_COOKIE);
	echo '</body></html>';

}

function printException($e)
{
	if (get_class($e) == 'EntityNotFoundException')
		print404($e);
	else
		printGenericException($e);
}

?>