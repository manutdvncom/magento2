<?php
/**
 * Join table data interface
 */

namespace Fsoft\GiftCard\Api\Data;

/**
 * Stripe JoinModel interface.
 *
 * @api
 */
interface JoinModelInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID = 'history_id';
    /**#@-*/

    /**
     * Get ID.
     *
     * @return int|null
     */
    public function getId();


    public function setId($id);
}
