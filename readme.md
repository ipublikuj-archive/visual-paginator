# Visual Paginator

[![Build Status](https://img.shields.io/travis/iPublikuj/visual-paginator.svg?style=flat-square)](https://travis-ci.org/iPublikuj/visual-paginator)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/iPublikuj/visual-paginator.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/visual-paginator/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/ipub/visual-paginator.svg?style=flat-square)](https://packagist.org/packages/ipub/visual-paginator)
[![Composer Downloads](https://img.shields.io/packagist/dt/ipub/visual-paginator.svg?style=flat-square)](https://packagist.org/packages/ipub/visual-paginator)

Visual paginator for [Nette Framework](http://nette.org/)

## Installation

The best way to install ipub/visual-paginator is using  [Composer](http://getcomposer.org/):

```json
{
	"require": {
		"ipub/visual-paginator": "dev-master"
	}
}
```

or

```sh
$ composer require ipub/visual-paginator:@dev
```

After that you have to register extension in config.neon.

```neon
extensions:
	visualPaginator: IPub\VisualPaginator\DI\VisualPaginatorExtension
```

## Usage

### Implementing into Presenter or Component

```php
use IPub\VisualPaginator\Components as VisualPaginator;

class SomePresenter extends Nette\Application\UI\Presenter
{
	/**
	 * @var Model
	 */
	private $dataModel;

	public function renderDefault()
	{
		$someItemsList = $this->dataModel->findAll();

		// Get visual paginator components
		$visualPaginator = $this['visualPaginator'];
		// Get paginator form visual paginator
		$paginator = $visualPaginator->getPaginator();
		// Define items count per one page
		$paginator->itemsPerPage = 10;
		// Define total items in list
		$paginator->itemCount = $someItemsList->count();
		// Apply limits to list
		$someItemsList->limit($paginator->itemsPerPage, $paginator->offset);
	}

	/**
	 * Create items paginator
	 *
	 * @return VisualPaginator\Control
	 */
	protected function createComponentVisualPaginator()
	{
		// Init visual paginator
		$control = new VisualPaginator\Control;

		return $control;
	}
}
```

### Enabling or disabling ajax support

This component bring ajax support. When ajax is enabled, then **ajax** class is inserted into links.

```php
use IPub\VisualPaginator\Components as VisualPaginator;

class SomePresenter extends Nette\Application\UI\Presenter
{
	/**
	 * Create items paginator
	 *
	 * @return VisualPaginator\Control
	 */
	protected function createComponentVisualPaginator()
	{
		// Init visual paginator
		$control = new VisualPaginator\Control;

		// Enable ajax (by default)
		$control->enableAjax();

		// Or disable ajax
		$control->disableAjax();

		return $control;
	}
}
```

And now you have to define events what to do when next or previous page is loaded via Ajax request:

```php
use IPub\VisualPaginator\Components as VisualPaginator;

class SomePresenter extends Nette\Application\UI\Presenter
{
	public function renderDefault()
	{
		$that = $this;

		//....

		// Define event for example to redraw snippets
		$this['visualPaginator']->onShowPage[] = (function ($component, $page) use ($that) {
			if ($that->isAjax()){
				$that->invalidateControl();
			}
		});
	}
}
```

### Using templates

This component come with two default templates. One is basic default template, with some basic classes and the second one is [Bootstrap FW](http://getbootstrap.com/) template. And also you can use your own template:

```php
use IPub\VisualPaginator\Components as VisualPaginator;

class SomePresenter extends Nette\Application\UI\Presenter
{
	/**
	 * Create items paginator
	 *
	 * @return VisualPaginator\Control
	 */
	protected function createComponentVisualPaginator()
	{
		// Init visual paginator
		$control = new VisualPaginator\Control;

		// To use bootstrap default template
		$control->setTemplateFile('bootstrap.latte');

		// To use your own template
		$control->setTemplateFile('path/to/your/latte/file.latte');

		return $control;
	}
}
```
