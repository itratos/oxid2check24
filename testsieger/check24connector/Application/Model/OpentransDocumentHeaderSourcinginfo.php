<?php
/**
 * Opentrans Document Header Sourcinginfo
 *
 * @copyright Testsieger Portal AG
 * @license GPL 3:
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Testsieger.de OpenTrans Connector
 */

namespace TestSieger\Check24Connector\Application\Model;

class OpentransDocumentHeaderSourcinginfo
{

    /**
     * @var string
     */
    private $quotation_id = NULL;

    /**
     * @var array
     */
    private $catalog_reference = array();

    /**
     * Construct a openTrans sourcinginfo
     *
     * @param string $quotation_id
     */
    public function __construct($quotation_id = NULL)
    {

        if ($quotation_id !== NULL && !is_string($quotation_id)) {
            throw new OpentransException('$quotation_id must be a string.');
        }

        $this->quotation_id = $quotation_id;
    }

}