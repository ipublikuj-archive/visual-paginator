<?php
/**
 * VisualPaginatorExtension.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:VisualPaginator!
 * @subpackage	DI
 * @since		5.0
 *
 * @date		18.06.14
 */

namespace IPub\VisualPaginator\DI;

use Nette;
use Nette\DI;
use Nette\PhpGenerator as Code;

class VisualPaginatorExtension extends DI\CompilerExtension
{
	/**
	 * @var array
	 */
	protected $defaults = [
		'templateFile'	=> NULL
	];

	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		// Define components
		$paginator = $builder->addDefinition($this->prefix('paginator'))
			->setClass('IPub\VisualPaginator\Components\Control')
			->setImplement('IPub\VisualPaginator\Components\IControl')
			->setArguments([new Nette\PhpGenerator\PhpLiteral('$templateFile')])
			->setInject(TRUE)
			->addTag('cms.components');

		if ($config['templateFile']) {
			$paginator->addSetup('$service->setTemplateFile(?)', [$config['templateFile']]);
		}
	}

	/**
	 * @param Nette\Configurator $config
	 * @param string $extensionName
	 */
	public static function register(Nette\Configurator $config, $extensionName = 'visualPaginator')
	{
		$config->onCompile[] = function (Nette\Configurator $config, Nette\DI\Compiler $compiler) use ($extensionName) {
			$compiler->addExtension($extensionName, new VisualPaginatorExtension());
		};
	}

	/**
	 * Return array of directories, that contain resources for translator.
	 *
	 * @return string[]
	 */
	function getTranslationResources()
	{
		return array(
			__DIR__ . '/../Translations'
		);
	}
}
