<?php

namespace FabulaGTK;

/**
 * classe que trata as rotas e inicialização do app
 */
class Bootstrap
{
	static $instance = NULL;

	/**
	 * 
	 */
	static public function instance()
	{
		if(self::$instance == null) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * 
	 */
	public function __construct()
	{ }

	/**
	 * 
	 */
	public function run($windowClass)
	{
		$window = new $windowClass();
	}
}