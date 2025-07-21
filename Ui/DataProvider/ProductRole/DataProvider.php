<?php

namespace Ethnic\ProductRoleImage\Ui\DataProvider\ProductRole;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Ethnic\ProductRoleImage\Model\ResourceModel\ProductRole\CollectionFactory;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProviderInterface;

/**
 * DataProvider for Product Role listing UI component.
 */
class DataProvider extends AbstractDataProvider implements DataProviderInterface
{
    /**
     * @var \Ethnic\ProductRoleImage\Model\ResourceModel\ProductRole\Collection
     */
    protected $collection;

    /**
     * Constructor.
     *
     * @param CollectionFactory $collectionFactory
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Return data for UI grid
     *
     * @return array
     */
    public function getData(): array
    {
        if (!$this->collection->isLoaded()) {
            $this->collection->load();
        }

        $items = $this->collection->toArray()['items'] ?? [];

        return [
            'totalRecords' => count($items),
            'items' => $items,
        ];
    }
}
