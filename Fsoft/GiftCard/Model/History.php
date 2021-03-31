<?php

namespace Fsoft\GiftCard\Model;

use Fsoft\GiftCard\Api\Data\JoinModelInterface;
use Fsoft\GiftCard\Model\ResourceModel\History as ResourceModel;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class History extends AbstractModel implements JoinModelInterface, IdentityInterface
{
    /**
     * No route page id.
     */
    const NOROUTE_ENTITY_ID = 'no-route';
    /**
     * Stripe JoinModel cache tag.
     */
    const CACHE_TAG = 'giftcard_history_model';

    /**
     * @var string
     */
    protected $_cacheTag = 'giftcard_history_model';
    /**
     * @var string
     */
    protected $_eventPrefix = 'giftcard_history_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    public function load($id, $field = null)
    {
        if ($id === null) {
            return $this->noRouteReasons();
        }

        return parent::load($id, $field);
    }

    /**
     * Load No-Route JoinModel.
     *
     * @return History
     */
    public function noRouteReasons(): History
    {
        return $this->load(self::NOROUTE_ENTITY_ID, $this->getIdFieldName());
    }

    /**
     * Get identities.
     *
     * @return array
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get ID.
     *
     * @return int
     */
    public function getId(): int
    {
        return parent::getData(self::ENTITY_ID);
    }

    /**
     * Set ID.
     *
     * @param int $id
     *
     * @return JoinModelInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }
}
