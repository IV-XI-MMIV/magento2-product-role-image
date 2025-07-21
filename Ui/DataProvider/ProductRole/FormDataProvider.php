<?php
namespace Ethnic\ProductRoleImage\Ui\DataProvider\ProductRole;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Ethnic\ProductRoleImage\Model\ResourceModel\ProductRole\CollectionFactory;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProviderInterface;

/**
 * Form DataProvider for Product Role entity.
 */
class FormDataProvider extends AbstractDataProvider implements DataProviderInterface
{
    /**
     * @var array
     */
    protected array $loadedData = [];

    /**
     * @var \Ethnic\ProductRoleImage\Model\ResourceModel\ProductRole\Collection
     */
    protected $collection;

    /**
     * Constructor.
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Return form data array for UI form.
     *
     * @return array
     */
    public function getData(): array
    {
        if (!empty($this->loadedData)) {
            return $this->loadedData;
        }

        foreach ($this->collection->getItems() as $item) {
            $this->loadedData[$item->getId()] = $item->getData();
        }

        return $this->loadedData;
    }
}
