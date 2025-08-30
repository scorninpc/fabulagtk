<?php

namespace FabulaGTK;

/**
 * classe que manipula e faz os carregamentos automaticos do glade
 */
class Window
{
	protected $builderObject = NULL;

	protected $windowName = NULL;

	public $window = NULL;

	/**
	 * 
	 */
	public function __construct()
	{
		// recupera o nome do window
		$class = get_class($this);
		$parts = explode("\\", $class);
		$this->windowName = end($parts);

		// verifica se o arquivo existe
		$builderFile = APPLICATION_PATH . "/Views/glade/" . $this->windowName . ".glade";
		if(!file_exists($builderFile)) {
			throw new \Exception("glade file for " . $this->windowName . " not found in " . $builderFile);
		}

		// carrega o arquivo
		$this->builderObject = \GtkBuilder::new_from_File($builderFile);
		// $this->builderObject->connect_signals_full(); // esse metodo só funciona se os metodos do connect forem estaticos

		// le manualmente o xml
		$xml = simplexml_load_file($builderFile);
		$this->readXML($xml);


		// foreach ($xml as $item) {

		// 	echo $item->object;

		// 	if($item->object) {
		// 		echo "\n" . $item->id;
		// 	}

		// }


		// armazena o window
		$this->window = $this->getObjectById($this->windowName);

		// inicializa
		$this->initialize();

		// mostra a janela
		$this->window->show_all();
	}

	/**
	 * faz o parse no XML do builder
	 */
	private function readXML($xml, $objectName="")
	{
		foreach ($xml as $key => $value) {

			// se for object, procura se tem signals
			if($key == "object") {
				// recupera o id
				$id = (string)$value['id'];
				$this->readXML($value, $id);
			}

			// se for signal, precisa ter $objectName para fazer o connect
			elseif($key == "signal") {
				$signal = (string)$value['name'];
				$handler = (string)$value['handler'];

				var_dump($objectName . " -> " . $signal . " -> " . $handler);
				$this->getObjectById($objectName)->connect($signal, [$this, $handler]);
			}

			// se for child, procura por outros object
			elseif($key == "child") {
				$this->readXML($value);
			}
		}
	}

	/**
	 * recupera o objeto do builder
	 */
	public function getObjectById($objectId)
	{
		return $this->builderObject->get_object($objectId);
	}

	/**
	 * incializa a janela
	 */
	public function initialize()
	{

	}
}