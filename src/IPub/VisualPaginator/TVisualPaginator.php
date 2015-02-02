<?php
/**
 * TVisualPaginator.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:VisualPaginator!
 * @subpackage	common
 * @since		5.0
 *
 * @date		01.02.15
 */

namespace IPub\VisualPaginator;

use Nette;
use Nette\Application;

use IPub;
use IPub\VisualPaginator\Components;

trait TVisualPaginator
{
	/**
	 * @var Components\IControl
	 */
	protected $visualPaginatorFactory;

	/**
	 * @param Components\IControl $visualPaginatorFactory
	 */
	public function injectVisualPaginator(Components\IControl $visualPaginatorFactory) {
		$this->visualPaginatorFactory = $visualPaginatorFactory;
	}
}
