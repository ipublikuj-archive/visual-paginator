<?php
/**
 * IControl.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:VisualPaginator!
 * @subpackage	Components
 * @since		5.0
 *
 * @date		18.06.14
 */

namespace IPub\VisualPaginator\Components;

interface IControl
{
	/**
	 * @param string|NULL $templateFile
	 * @return mixed
	 */
	public function create($templateFile = NULL);
}
