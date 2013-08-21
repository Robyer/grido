<?php

/**
 * Test: Grid "setters"
 *
 * @author     Petr Bugyík
 * @package    Grido
 */

require_once __DIR__ . '/../bootstrap.php';

use \Tester\Assert,
    \Grido\Grid,
    \Grido\Components\Columns\Column,
    \Grido\Components\Filters\Filter;

test(function() //Grid::setModel()
{
    $grid = new Grid;

    $grid->setModel(mock('Grido\DataSources\IDataSource'));
    Assert::type('Grido\DataSources\IDataSource', $grid->model);

    $grid->setModel(mock('\DibiFluent'));
    Assert::type('Grido\DataSources\Model', $grid->model);

    $grid->setModel(mock('\DibiFluent'), TRUE);
    Assert::type('Grido\DataSources\Model', $grid->model);

    $grid->setModel(mock('\Nette\Database\Table\Selection'));
    Assert::type('Grido\DataSources\Model', $grid->model);

    $grid->setModel(mock('\Doctrine\ORM\QueryBuilder'));
    Assert::type('Grido\DataSources\Model', $grid->model);

    $grid->setModel(array('TEST' => 'TEST'));
    Assert::type('Grido\DataSources\Model', $grid->model);

    $grid->setModel(mock('Grido\DataSources\IDataSource'), TRUE);
    Assert::type('Grido\DataSources\Model', $grid->model);

    Assert::exception(function() use ($grid) {
        $grid->setModel(mock('BAD'));
    }, 'InvalidArgumentException');

    Assert::exception(function() use ($grid) {
        $grid->setModel(mock('BAD'), TRUE);
    }, 'InvalidArgumentException');
});

test(function() //Grid::setDefaultPerPage()
{
    $grid = new Grid;

    $perPage = 11;
    $perPageList = $grid->perPageList;
    $perPageList[] = $perPage;
    sort($perPageList);

    $grid->setDefaultPerPage((string) $perPage);
    Assert::same($perPage, $grid->defaultPerPage);
    Assert::same($perPageList, $grid->perPageList);
});

test(function() //Grid::setDefaultSort()
{
    $grid = new Grid;

    $grid->setDefaultSort(array('a' => 'ASC', 'b' => 'desc', 'c' => 'Asc', 'd' => Column::DESC));
    Assert::same(array('a' => Column::ASC, 'b' => Column::DESC, 'c' => Column::ASC, 'd' => Column::DESC), $grid->defaultSort);

    Assert::exception(function() use ($grid) {
        $grid->setDefaultSort(array('a' => 'up'));
    }, 'InvalidArgumentException');
});

test(function() //Grid::setPerPageList()
{
    $grid = new Grid;

    $grid->addFilter('test', 'Test');

    $a = array(10, 20);
    $grid->setPerPageList($a);
    Assert::same($a, $grid->perPageList);
    Assert::same(array_combine($a, $a), $grid['form']['count']->items);
});

test(function() //Grid::setFilterRenderType()
{
    $grid = new Grid;

    $type = Filter::RENDER_INNER;
    $grid->setFilterRenderType($type);
    Assert::same($type, $grid->filterRenderType);

    $type = Filter::RENDER_OUTER;
    $grid->setFilterRenderType($type);
    Assert::same($type, $grid->filterRenderType);

    $grid->setFilterRenderType('OUTER');
    Assert::same($type, $grid->filterRenderType);

    Assert::exception(function() use ($grid) {
        $grid->setFilterRenderType('INNERR');
    }, 'InvalidArgumentException');
});

test(function() //Grid::setTemplateFile()
{
    $grid = new Grid;

    $template = __FILE__;
    $grid->setTemplateFile($template);
    Assert::same($template, $grid->template->getFile());
});

test(function() //Grid::setClientSideOptions()
{
    $grid = new Grid;

    $options = array('key' => 'value');
    $grid->setClientSideOptions($options);
    Assert::same($grid->tablePrototype->data['grido-options'], json_encode($options));
});