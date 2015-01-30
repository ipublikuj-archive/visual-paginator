<?php
/**
 * Test: IPub\VisualPaginator\Extension
 * @testCase
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:VisualPaginator!
 * @subpackage	Tests
 * @since		5.0
 *
 * @date		30.01.15
 */

namespace IPubTests\VisualPaginator;

use Nette;

use Tester;
use Tester\Assert;

use IPub;
use IPub\VisualPaginator;

require __DIR__ . '/../bootstrap.php';

class ExtensionTest extends Tester\TestCase
{
	/**
	 * @return \SystemContainer|\Nette\DI\Container
	 */
	protected function createContainer()
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		VisualPaginator\DI\VisualPaginatorExtension::register($config);

		return $config->createContainer();
	}

	public function testCompilersServices()
	{
		$dic = $this->createContainer();

		// Get component factory
		$factory = $dic->getService('visualPaginator.paginator');

		Assert::true($factory instanceof IPub\VisualPaginator\Components\IControl);
		Assert::true($factory->create() instanceof IPub\VisualPaginator\Components\Control);
	}
}

\run(new ExtensionTest());