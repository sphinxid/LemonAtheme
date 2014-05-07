<?php
/*
 * 05/07/2014
 * PHP Wrapper for Atheme XMLRPC.
 * by sphinx <firman@kodelatte.com>
 *
 */

require_once('xmlrpc.inc');

Class LemonAtheme {

	private $_data = array(
	'server'	=>	'127.0.0.1',
	'port'		=>	'8080',
	'api'		=>	'/xmlrpc'
	);

	private $_user_ip = '1.1.1.1';

	public function __construct()
	{
	}

	private function _atheme($cookie, $service, $command, $params)
	{
	  return atheme($this->_data['server'], $this->_data['port'], $this->_data['api'],
                 $this->_user_ip, $cookie, $service, $command, $params);
	}

	public function setUserIP($user_ip)
	{
	  $this->_user_ip = $user_ip;
	}

	public function setServerInfo($server, $port, $api = '/xmlrpc')
	{
	  $this->_data['server'] = $server;
	  $this->_data['port']  = $port;
	  $this->_data['api']	= $api;
	}

	public function registerNick($nickname, $password, $email)
	{
	  $params = array('REGISTER', $nickname, $password, $email);
	  $ret = $this->_atheme('.', 'NICKSERV', 'atheme.command', $params);

	  return $ret;
	}

	public function authNick($nickname, $password)
	{
	  $params = array($nickname, $password);
	  $ret = $this->_atheme(null, null, 'atheme.login', $params);

	  return $ret;
	}

	public function checkAccount($nickname, $password)
	{
	  $ret = $this->authNick($nickname, $password);

	  if (strstr($ret, 'Command failed'))
	    return false;

	  return true;
	}
}

function atheme($hostname, $port, $path, $sourceip, $cookie = '.', $service, $command = "atheme.command", $params)
{
	$client = new xmlrpc_client($path, $hostname, $port);

	$message = new xmlrpcmsg($command);

	if (strcmp($command, "atheme.command") == 0)
	{

	  $message->addParam(new xmlrpcval($cookie, "string"));
	  $message->addParam(new xmlrpcval($service, "string"));
	  $message->addParam(new xmlrpcval($sourceip, "string"));
	  $message->addParam(new xmlrpcval($service, "string"));
	}

	  if ($params != NULL)
	  {
		foreach($params as $param)
		{
			$message->addParam(new xmlrpcval($param, "string"));
		}
		$response = $client->send($message);
	  }



	if (!$response->faultCode())
	{
		return $response->serialize();
	}
	else
	{
		return "Command failed: " . $response->faultString();
	}

}
