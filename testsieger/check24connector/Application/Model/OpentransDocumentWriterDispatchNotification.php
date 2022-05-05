<?php
/**
 * openTrans Dispatchnotification Document Writer standard 2.1
 *
 * Extense the standard openTrans Document Writer for standard 2.1 order
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

use TestSieger\Check24Connector\Application\Model\Config as OpentransConfig;

class OpentransDocumentWriterDispatchNotification extends OpentransDocumentWriter
{

    /**
     * Convert the Object from opentransdocument to XML for openTrans -
     * Standard 2.1 of DISPATCHNOTIFICATION-Files
     *
     * @param object $src
     * @return object simpleXMLElement
     */
    public function get_document_data_dispatchnotification($src)
    {

        if (!$src instanceof OpentransDocumentDispatchNotification) {
            throw new OpentransException('$src must be type of OpentransDocumentDispatchNotification.');
        }

        // start with order list, which could contain more then one order
        $xml = new \SimpleXMLElement('<DISPATCHNOTIFICATION xmlns="http://www.opentrans.org/XMLSchema/2.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentrans.org/XMLSchema/2.1 https://merchantcenter.check24.de/sdk/opentrans/schema-definitions/opentrans_2_1.xsd http://www.bmecat.org/bmecat/2005 https://merchantcenter.check24.de/sdk/opentrans/schema-definitions/bmecat_2005.xsd http://www.w3.org/2005/05/xmlmime https://merchantcenter.check24.de/sdk/opentrans/schema-definitions/xmlmime.xsd http://www.w3.org/2000/09/xmldsig# https://merchantcenter.check24.de/sdk/opentrans/schema-definitions/xmldsig-core-schema.xsd" xmlns:bmecat="http://www.bmecat.org/bmecat/2005" xmlns:xmime="http://www.w3.org/2005/05/xmlmime"></DISPATCHNOTIFICATION>');

        // Document
        $xml->addAttribute('version', '2.1');

        // Header
        $header = $xml->addChild('DISPATCHNOTIFICATION_HEADER');

        $NAMESPACE_BMECAT = OpentransConfig::NAMESPACE_BMECAT_URL;

        // Control info
        $sStopAutomaticProcessing = $src->get_header()->get_controlinfo()->get_stop_automatic_processing();
        $sGeneratorInfo = $src->get_header()->get_controlinfo()->get_generator_name();
        $sGenerationDate = $src->get_header()->get_controlinfo()->get_generation_date();

        if ($sStopAutomaticProcessing || $sGeneratorInfo || $sGenerationDate) {
            $info = $header->addChild('CONTROL_INFO');
            if ($sStopAutomaticProcessing) {
                $info->addChild('STOP_AUTOMATIC_PROCESSING', $sStopAutomaticProcessing);
            }
            if ($sGeneratorInfo) {
                $info->addChild('GENERATOR_INFO', $sGeneratorInfo);
            }
            if ($sGenerationDate) {
                $info->addChild('GENERATION_DATE', $sGenerationDate);
            }
        }

        // Order info
        $oinfo = $header->addChild('DISPATCHNOTIFICATION_INFO');
        $oDispatchNotificationInfo = $src->get_header()->get_dispatchnotificationinfo();
        $oinfo->addChild('DISPATCHNOTIFICATION_ID', $oDispatchNotificationInfo->get_dispatchnotification_id());
        if ($sDispatchNotificationID = $oDispatchNotificationInfo->get_dispatchnotification_date()) {
            $oinfo->addChild('DISPATCHNOTIFICATION_DATE', $sDispatchNotificationID);
        }

        // Order Parties
        $parties = $oinfo->addChild('PARTIES');
        $src_parties = $src->get_header()->get_dispatchnotificationinfo()->get_parties();

        for ($i = 0, $i_max = count($src_parties); $i < $i_max; ++$i) {
            $party = $parties->addChild('PARTY');
            $party_id = $party->addChild('PARTY_ID', $src_parties[$i]->get_id()->get_id(), $NAMESPACE_BMECAT);
            $party_id->addAttribute('type', $src_parties[$i]->get_id()->get_type());
            if ($sPartyRole = $src_parties[$i]->get_role()) {
                $party->addChild('PARTY_ROLE', $sPartyRole);
            }

            $src_address = $src_parties[$i]->get_address();

            $address = $party->addChild('ADDRESS');

            if ($sAddressName = $src_address->get_name()) {
                $address->addChild('NAME', str_replace('&', '&amp;', str_replace('&amp;', '&', $sAddressName)), $NAMESPACE_BMECAT);
            }
            if ($sAddressName2 = $src_address->get_name2()) {
                $address->addChild('NAME2', str_replace('&', '&amp;', str_replace('&amp;', '&', $sAddressName2)), $NAMESPACE_BMECAT);
            }
            if ($sAddressName3 = $src_address->get_name3()) {
                $address->addChild('NAME3', str_replace('&', '&amp;', str_replace('&amp;', '&', $sAddressName3)), $NAMESPACE_BMECAT);
            }
            if ($sAddressStreet = $src_address->get_street()) {
                $address->addChild('STREET', $sAddressStreet, $NAMESPACE_BMECAT);
            }
            if ($sAddressZip = $src_address->get_zip()) {
                $address->addChild('ZIP', $sAddressZip, $NAMESPACE_BMECAT);
            }
            if ($sAddressCity = $src_address->get_city()) {
                $address->addChild('CITY', $sAddressCity, $NAMESPACE_BMECAT);
            }
            if ($sAddressCountry = $src_address->get_country()) {
                $address->addChild('COUNTRY', $sAddressCountry, $NAMESPACE_BMECAT);
            }
            if ($sAddressCountryCoded = $src_address->get_country_coded()) {
                $address->addChild('COUNTRY_CODED', $sAddressCountryCoded, $NAMESPACE_BMECAT);
            }

            $src_phone = $src_address->get_phone();
            if (count($src_phone) > 0) {
                foreach ($src_phone as $phone_type => $phone_number) {
                    $phone = $address->addChild('PHONE', $phone_number, $NAMESPACE_BMECAT);
                }
                if ($phone_type) {
                    $phone->addAttribute('type', $phone_type);
                }
            }

            $src_fax = $src_address->get_fax();
            if (count($src_fax) > 0) {
                foreach ($src_fax as $fax_type => $fax_number) {
                    $address->addChild('FAX', $fax_number, $NAMESPACE_BMECAT)->addAttribute('type', $fax_type);
                }
            }

            $src_email = $src_address->get_emails();
            if (count($src_email) > 0) {
                $address->addChild('EMAIL', $src_email[0], $NAMESPACE_BMECAT);
            }

            $src_address_remarks = $src_address->get_address_remarks();
            if (count($src_address_remarks) > 0) {
                foreach ($src_address_remarks as $address_remarks_delivery_type => $address_remarks_packstation_postnumber) {
                    $address_remarks_packstation_postnumber = str_replace('&', '&amp;', str_replace('&amp;', '&', $address_remarks_packstation_postnumber));
                    $address->addChild('ADDRESS_REMARKS', $address_remarks_packstation_postnumber, $NAMESPACE_BMECAT)->addAttribute('type', $address_remarks_delivery_type);
                }
            }

        }

        // creating IDREFs for Parties
        $src_parties_reference_delivery_idref = $src->get_header()->get_dispatchnotificationinfo()->get_idref(OpentransDocumentIdref::TYPE_DELIVERY_IDREF);
        $src_parties_reference_supplier_idref = $src->get_header()->get_dispatchnotificationinfo()->get_idref(OpentransDocumentIdref::TYPE_SUPPLIER_IDREF);

        if ($src_parties_reference_supplier_idref) {
            $parties_reference_supplier_idref = $oinfo->addChild('SUPPLIER_IDREF', $src_parties_reference_supplier_idref, $NAMESPACE_BMECAT);
            $parties_reference_supplier_idref->addAttribute('type', OpentransDocumentPartyid::TYPE_CHECK24);
        }

        $parties_reference_delivery_idref = $oinfo->addChild('SHIPMENT_PARTIES_REFERENCE')->addChild('DELIVERY_IDREF', $src_parties_reference_delivery_idref);
        $parties_reference_delivery_idref->addAttribute('type', OpentransDocumentPartyid::TYPE_CHECK24);

        if ($sDispatchNotificationShipmentID = $oDispatchNotificationInfo->get_shipment_id()) {
            $oinfo->addChild('SHIPMENT_ID', $sDispatchNotificationShipmentID);
        }

        if ($sTrackingTracingUrl = $oDispatchNotificationInfo->get_tracking_url()) {
            $oinfo->addChild('TRACKING_TRACING_URL', $sTrackingTracingUrl);
        }

        // Remarks
        $src_remarks = $src->get_header()->get_dispatchnotificationinfo()->get_remarks();
        if (count($src_remarks) > 0) {
            foreach ($src_remarks as $type => $value) {
                $oinfo->addChild('REMARKS', $value)->addAttribute('type', $type);
            }
        }

        // Items
        $items = $xml->addChild('DISPATCHNOTIFICATION_ITEM_LIST');

        $src_items = $src->get_item_list();

        for ($i = 0, $i_max = count($src_items); $i < $i_max; ++$i) {

            $item = $items->addChild('DISPATCHNOTIFICATION_ITEM');

            $sLineItemId = $src_items[$i]->get_line_item_id() !== NULL ? $src_items[$i]->get_line_item_id() : $i;
            $item->addChild('LINE_ITEM_ID', $sLineItemId);

            $product_id = $item->addChild('PRODUCT_ID');

            if ($sSupplierPID = $src_items[$i]->get_product_id()->get_supplier_pid()) {
                $product_id->addChild('SUPPLIER_PID', $sSupplierPID, $NAMESPACE_BMECAT);
            }

            if ($sDescriptionShort = $src_items[$i]->get_product_id()->get_description_short()) {
                $product_id->addChild('DESCRIPTION_SHORT', $sDescriptionShort, $NAMESPACE_BMECAT);
            }

            if ($sDescriptionLong = $src_items[$i]->get_product_id()->get_description_long()) {
                $product_id->addChild('DESCRIPTION_LONG', $sDescriptionLong);
            }

            $item->addChild('QUANTITY', $src_items[$i]->get_quantity());
            $item->addChild('ORDER_UNIT', $src_items[$i]->get_order_unit(), $NAMESPACE_BMECAT);

            //removed price line amount
            /*if ($fPriceLineAmount = $src_items[$i]->get_price_line_amount()) {
                $item->addChild('PRICE_LINE_AMOUNT', $fPriceLineAmount);
            }*/

            $order_reference = $item->addChild('ORDER_REFERENCE');
            $order_reference->addChild('ORDER_ID', $src->get_header()->get_dispatchnotificationinfo()->get_order_id());
            $order_reference->addChild('LINE_ITEM_ID', $sLineItemId);


            if ($src_parties_reference_delivery_idref) {
                $parties_reference_delivery_idref = $item->addChild('SHIPMENT_PARTIES_REFERENCE')->addChild('DELIVERY_IDREF', $src_parties_reference_delivery_idref);
                $parties_reference_delivery_idref->addAttribute('type', OpentransDocumentPartyid::TYPE_CHECK24);
            }

            // Remarks from items
            $src_items_remarks = $src_items[$i]->get_remarks();
            if (count($src_items_remarks) > 0) {
                $src_items_remarks_sum = 0;
                foreach ($src_items_remarks as $type => $value) {
                    $item->addChild('REMARKS', str_replace('&', '&amp;', str_replace('&amp;', '&', $value)))->addAttribute('type', $type);
                    if ($type == 'recycling' || $type == 'installation') {
                        $src_items_remarks_sum += $value;
                    }
                }
            }
        }

        // Order Summary
        $summary = $xml->addChild('DISPATCHNOTIFICATION_SUMMARY');
        $summary->addChild('TOTAL_ITEM_NUM', $src->get_summary()->get_total_item_num());

        //removed total_amount
        // Order amount total is the sum of shipping costs, additional_costs, addons and orderpositions amount
        /*$order_amount_total = $src->get_summary()->get_total_amount();

        if (isset($src_remarks['shipping_fee'])) {
            $order_amount_total += $src_remarks['shipping_fee'];
        }
        if (isset($src_remarks['additional_costs'])) {
            $order_amount_total += $src_remarks['additional_costs'];
        }
        if (isset($src_remarks['services_1_man'])) {
            $order_amount_total += $src_remarks['services_1_man'];
        }
        if (isset($src_remarks['services_2_man'])) {
            $order_amount_total += $src_remarks['services_2_man'];
        }
        if (isset($src_items_remarks_sum)) {
            $order_amount_total += $src_items_remarks_sum;
        }

        $summary->addChild('TOTAL_AMOUNT', $order_amount_total);*/

        // Output

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $domnode = dom_import_simplexml($xml);
        $domnode = $dom->importNode($domnode, true);
        $dom->appendChild($domnode);
        return $dom->saveXML();
    }

}