<?php
/**
 * Control.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:VisualPaginator!
 * @subpackage	Components
 * @since		5.0
 *
 * @date		12.03.14
 */

namespace IPub\VisualPaginator\Components;

use Nette;
use Nette\Application;
use Nette\Localization;
use Nette\Utils;

use IPub;
use IPub\VisualPaginator;
use IPub\VisualPaginator\Exceptions;

/**
 * Visual paginator control
 *
 * @package		iPublikuj:VisualPaginator!
 * @subpackage	Components
 *
 * @method onShowPage(Control $self, $page)
 *
 * @property-read Application\UI\ITemplate $template
 */
class Control extends Application\UI\Control
{
	/**
	 * @persistent int
	 */
	public $page = 1;

	/**
	 * Events
	 *
	 * @var array
	 */
	public $onShowPage;

	/**
	 * @var Utils\Paginator
	 */
	protected $paginator;

	/**
	 * @var string
	 */
	protected $templatePath;

	/**
	 * @var Localization\ITranslator
	 */
	protected $translator;

	/**
	 * @var bool
	 */
	protected $useAjax = TRUE;

	/**
	 * @param Localization\ITranslator $translator
	 */
	public function injectTranslator(Localization\ITranslator $translator)
	{
		$this->translator = $translator;
	}

	/**
	 * Render control
	 */
	public function render()
	{
		// Check if control has template
		if ($this->template instanceof Nette\Bridges\ApplicationLatte\Template) {
			// Assign vars to template
			$this->template->steps		= $this->getSteps();
			$this->template->paginator	= $this->getPaginator();
			$this->template->handle		= 'showPage!';
			$this->template->useAjax	= $this->useAjax;

			// Check if translator is available
			if ($this->getTranslator() instanceof Localization\ITranslator) {
				$this->template->setTranslator($this->getTranslator());
			}

			// If template was not defined before...
			if ($this->template->getFile() === NULL) {
				// ...try to get base component template file
				$templatePath = !empty($this->templatePath) ? $this->templatePath : __DIR__ . DIRECTORY_SEPARATOR .'template'. DIRECTORY_SEPARATOR .'default.latte';
				$this->template->setFile($templatePath);
			}

			// Render component template
			$this->template->render();

		} else {
			throw new Exceptions\InvalidStateException('Visual paginator control is without template.');
		}
	}

	/**
	 * @return $this
	 */
	public function enableAjax()
	{
		$this->useAjax = TRUE;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function disableAjax()
	{
		$this->useAjax = FALSE;

		return $this;
	}

	/**
	 * @return Utils\Paginator
	 */
	public function getPaginator()
	{
		// Check if paginator is created
		if (!$this->paginator) {
			$this->paginator = new Utils\Paginator;
		}

		return $this->paginator;
	}

	/**
	 * Change default control template path
	 *
	 * @param string $templatePath
	 *
	 * @return $this
	 *
	 * @throws Exceptions\FileNotFoundException
	 */
	public function setTemplateFile($templatePath)
	{
		// Check if template file exists...
		if (!is_file($templatePath)) {
			// ...check if extension template is used
			if (is_file(__DIR__ . DIRECTORY_SEPARATOR .'template'. DIRECTORY_SEPARATOR . $templatePath)) {
				$templatePath = __DIR__ . DIRECTORY_SEPARATOR .'template'. DIRECTORY_SEPARATOR . $templatePath;

			} else {
				// ...if not throw exception
				throw new Exceptions\FileNotFoundException('Template file "'. $templatePath .'" was not found.');
			}
		}

		$this->templatePath = $templatePath;

		return $this;
	}

	/**
	 * @param Localization\ITranslator $translator
	 *
	 * @return $this
	 */
	public function setTranslator(Localization\ITranslator $translator)
	{
		$this->translator = $translator;

		return $this;
	}

	/**
	 * @return Localization\ITranslator|null
	 */
	public function getTranslator()
	{
		if ($this->translator instanceof Localization\ITranslator) {
			return $this->translator;
		}

		return NULL;
	}

	/**
	 * @return array
	 */
	public function getSteps()
	{
		// Get Nette paginator
		$paginator = $this->getPaginator();

		// Get actual paginator page
		$page = $paginator->page;

		if ($paginator->pageCount < 2) {
			$steps = [$page];

		} else {
			$arr = range(max($paginator->firstPage, $page - 3), min($paginator->lastPage, $page + 3));
			$count = 4;
			$quotient = ($paginator->pageCount - 1) / $count;

			for ($i = 0; $i <= $count; $i++) {
				$arr[] = round($quotient * $i) + $paginator->firstPage;
			}

			sort($arr);

			$steps = array_values(array_unique($arr));
		}

		return $steps;
	}

	/**
	 * Loads state information
	 *
	 * @param  array
	 *
	 * @return void
	 */
	public function loadState(array $params)
	{
		parent::loadState($params);

		$this->getPaginator()->page = $this->page;
	}

	/**
	 * @param int $page
	 */
	public function handleShowPage($page)
	{
		$this->onShowPage($this, $page);
	}
}