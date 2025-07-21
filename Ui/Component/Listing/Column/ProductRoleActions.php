<?php

namespace Ethnic\ProductRoleImage\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class ProductRoleActions
 *
 * Adds action links (e.g., Delete) to the Product Role listing grid.
 */
class ProductRoleActions extends Column
{
    /**
     * URL path for delete action
     */
    private const URL_PATH_DELETE = 'productroleimage/index/delete';

    /**
     * @var UrlInterface
     */
    protected UrlInterface $urlBuilder;

    /**
     * Constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Adds Delete (and optionally Edit) action links to each row.
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['items']) || !is_array($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as &$item) {
            if (!isset($item['id'])) {
                continue;
            }

            $role = $item['role'] ?? __('Item');
            $actions = [];

            // Add Delete action, skip ID = 1 if it's protected
            if ((int)$item['id'] !== 1) {
                $actions['delete'] = [
                    'href' => $this->urlBuilder->getUrl(self::URL_PATH_DELETE, ['id' => $item['id']]),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete "%1"', $role),
                        'message' => __('Are you sure you want to delete the "%1" record?', $role),
                    ]
                ];
            }

            // Assign actions to column
            $item[$this->getData('name')] = $actions;
        }

        return $dataSource;
    }
}
