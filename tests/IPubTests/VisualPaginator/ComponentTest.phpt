<?php
/**
 * Test: IPub\VisualPaginator\Compiler
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
use Nette\Application;
use Nette\Application\Routers;
use Nette\Application\UI;
use Nette\Utils;

use Tester;
use Tester\Assert;

use IPub;
use IPub\VisualPaginator;

require __DIR__ . '/../bootstrap.php';

class ComponentTest extends Tester\TestCase
{
	/**
	 * @var Nette\Application\IPresenterFactory
	 */
	private $presenterFactory;

	/**
	 * @var \SystemContainer|\Nette\DI\Container
	 */
	private $container;

	/**
	 * Set up
	 */
	public function setUp()
	{
		parent::setUp();

		$this->container = $this->createContainer();

		// Get presenter factory from container
		$this->presenterFactory = $this->container->getByType('Nette\Application\IPresenterFactory');
	}

	public function testSetValidTemplate()
	{
		// Create test presenter
		$presenter = $this->createPresenter();

		// Create GET request
		$request = new Application\Request('Test', 'GET', array('action' => 'validTemplate'));
		// & fire presenter & catch response
		$response = $presenter->run($request);

		$dq = Tester\DomQuery::fromHtml((string) $response->getSource());

		Assert::true($dq->has('ul[class="pagination"]'));
	}

	/**
	 * @throws \IPub\VisualPaginator\Exceptions\FileNotFoundException
	 */
	public function testSetInvalidTemplate()
	{
		// Create test presenter
		$presenter = $this->createPresenter();

		// Create GET request
		$request = new Application\Request('Test', 'GET', array('action' => 'invalidTemplate'));
		// & fire presenter & catch response
		$presenter->run($request);
	}

	public function testGetPaginator()
	{
		$visualPaginator = new VisualPaginator\Components\Control();

		Assert::true($visualPaginator->getPaginator() instanceof Utils\Paginator);
	}

	/**
	 * @return Application\IPresenter
	 */
	protected function createPresenter()
	{
		// Create test presenter
		$presenter = $this->presenterFactory->createPresenter('Test');
		// Disable auto canonicalize to prevent redirection
		$presenter->autoCanonicalize = FALSE;

		return $presenter;
	}

	/**
	 * @return \SystemContainer|\Nette\DI\Container
	 */
	protected function createContainer()
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		VisualPaginator\DI\VisualPaginatorExtension::register($config);

		$config->addConfig(__DIR__ . '/files/presenters.neon', $config::NONE);

		return $config->createContainer();
	}
}

class TestPresenter extends UI\Presenter
{
	/**
	 * Use component trait
	 */
	use VisualPaginator\TVisualPaginator;

	public function actionValidTemplate()
	{
		// Set invalid template name
		$this['visualPaginator']->setTemplateFile('bootstrap.latte');

		// Get visual paginator components
		$visualPaginator = $this['visualPaginator'];
		// Get paginator form visual paginator
		$paginator = $visualPaginator->getPaginator();
		// Define items count per one page
		$paginator->itemsPerPage = 10;
		// Define total items in list
		$paginator->itemCount = 150;
	}

	public function actionInvalidTemplate()
	{
		// Set invalid template name
		$this['visualPaginator']->setTemplateFile('invalid.latte');
	}

	public function renderValidTemplate()
	{
		// Set template for component testing
		$this->template->setFile(__DIR__ . DIRECTORY_SEPARATOR .'templates'. DIRECTORY_SEPARATOR .'validTemplate.latte');
	}

	/**
	 * Create items paginator
	 *
	 * @return VisualPaginator\Components\Control
	 */
	protected function createComponentVisualPaginator()
	{
		// Init visual paginator
		$control = new VisualPaginator\Components\Control;

		return $control;
	}
}

class RouterFactory
{
	/**
	 * @return \Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new Routers\  RouteList();
		$router[] = new Routers\Route('<presenter>/<action>[/<id>]', 'Test:default');

		return $router;
	}
}

\run(new ComponentTest());