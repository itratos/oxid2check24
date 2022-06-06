<?php

namespace Itratos\Check24Connector\Application\Model;

class OpentransDocumentHeaderDocumentinfo
{

    /**#@+
     * Constants
     */
    /**
     * DELIVERY_DATE_TYPE_FIXED
     *
     * @var const string
     */
    const DELIVERY_DATE_TYPE_FIXED = 'fixed';

    /**
     * DELIVERY_DATE_TYPE_OPTIONAL
     *
     * @var const string
     */
    const DELIVERY_DATE_TYPE_OPTIONAL = 'optional';

    /**
     * REMARK_TYPE_DELIVERYNOTE
     *
     * @var const string
     */
    const REMARK_TYPE_DELIVERYNOTE = 'deliverynote';

    /**
     * REMARK_TYPE_DISPATCHNOTIFICATION
     *
     * @var const string
     */
    const REMARK_TYPE_DISPATCHNOTIFICATION = 'dispatchnotification';

    /**
     * REMARK_TYPE_GENERAL
     *
     * @var const string
     */
    const REMARK_TYPE_GENERAL = 'general';

    /**
     * REMARK_TYPE_INVOICE
     *
     * @var const string
     */
    const REMARK_TYPE_INVOICE = 'invoice';

    /**
     * REMARK_TYPE_ORDER
     *
     * @var const string
     */
    const REMARK_TYPE_ORDER = 'order';

    /**
     * REMARK_TYPE_ORDERCHANGE
     *
     * @var const string
     */
    const REMARK_TYPE_ORDERCHANGE = 'orderchange';

    /**
     * REMARK_TYPE_ORDERRESPONSE
     *
     * @var const string
     */
    const REMARK_TYPE_ORDERRESPONSE = 'orderresponse';

    /**
     * REMARK_TYPE_QUOTATION
     *
     * @var const string
     */
    const REMARK_TYPE_QUOTATION = 'quotation';

    /**
     * REMARK_TYPE_RECEIPTACKNOWLEDGEMENT
     *
     * @var const string
     */
    const REMARK_TYPE_RECEIPTACKNOWLEDGEMENT = 'receiptacknowledgement';

    /**
     * REMARK_TYPE_REMITTANCEADVICE
     *
     * @var const string
     */
    const REMARK_TYPE_REMITTANCEADVICE = 'remittanceadvice';

    /**
     * REMARK_TYPE_INVOICELIST
     *
     * @var const string
     */
    const REMARK_TYPE_INVOICELIST = 'invoicelist';

    /**
     * REMARK_TYPE_RFQ
     *
     * @var const string
     */
    const REMARK_TYPE_RFQ = 'rfq';

    /**
     * REMARK_TYPE_TRANSPORT
     *
     * @var const string
     */
    const REMARK_TYPE_TRANSPORT = 'transport';

    /**
     * openTrans defined and valid delivery date types
     *
     * @var static array
     */
    static $valid_delivery_date_types = array(
        self::DELIVERY_DATE_TYPE_FIXED,
        self::DELIVERY_DATE_TYPE_OPTIONAL
    );

    /**
     * @var string
     */
    protected $document_id = NULL;

    /**
     * @var string
     */
    protected $document_date = NULL;

    /**
     * @var array
     */
    protected $parties = array();

    /**
     * @var array
     */
    protected $idrefs = array();

    /**
     * @var string
     */
    protected $delivery_date_start = NULL;

    /**
     * @var string
     */
    protected $delivery_date_end = NULL;

    /**
     * @var string
     */
    protected $delivery_date_type = NULL;

    /**
     * @var array
     */
    protected $remarks = array();

    /**
     * Construct a openTrans document info
     *
     * @param string document_id
     * @param string document_date
     */
    public function __construct($document_id, $document_date)
    {
        if (!is_string($document_id)) {
            throw new OpentransException('$document_id must be a string.');
        }

        if (!is_string($document_date)) {
            throw new OpentransException('$document_date must be a string.');
        }

        $this->document_id = $document_id;
        $this->document_date = $document_date;
    }

    /**
     * Adds party and sets idref
     *
     * @param object $party OpentransDocumentParty
     * @return void
     */
    public function add_party($party)
    {
        if (!$party instanceof OpentransDocumentParty) {
            throw new OpentransException('$party must be type of OpentransDocumentParty.');
        }

        $this->parties[] = $party;
        $this->idrefs[] = new OpentransDocumentIdref($party->get_id()->get_id(), $party->get_role());
    }


    /**
     * Returns parties
     *
     * @return array parties
     */
    public function get_parties()
    {
        return $this->parties;
    }

    /**
     * Sets delivery_date_type, delivery_date_start, delivery_date_end
     *
     * @param string $start
     * @param string $end
     * @param string $type
     * @return void
     */
    public function set_delivery_date($start, $end, $type = NULL)
    {
        if (!is_string($start)) {
            throw new OpentransException('$start must be a string.');
        }

        if (!is_string($end)) {
            throw new OpentransException('$end must be a string.');
        }

        if ($type !== NULL && !is_string($type)) {
            throw new OpentransException('$type must be a string.');
        } else if (!in_array($type, self::$valid_delivery_date_types)) {
            throw new OpentransException('$type is no valid type.');
        }

        $this->delivery_date_type = $type;
        $this->delivery_date_start = $start;
        $this->delivery_date_end = $end;
    }

    /**
     * Adds remark
     *
     * @param string type
     * @param string value
     * @return void
     */
    public function add_remark($type, $value)
    {
        if (!is_string($type)) {
            throw new OpentransException('$type must be a string.');
        }

        if (!is_string($value)) {
            throw new OpentransException('$value must be a string.');
        }

        $this->remarks[$type] = $value;
    }

    /**
     * Returns document_id
     *
     * @return string document_id
     */
    public function get_document_id()
    {
        return $this->document_id;
    }

    /**
     * Returns document_date
     *
     * @return string document_date
     */
    public function get_document_date()
    {
        return $this->document_date;
    }

    /**
     * Returns remarks
     *
     * @return array remarks
     */
    public function get_remarks()
    {
        return $this->remarks;
    }


    /**
     * Returns value of idref
     *
     * @param string type
     * @return string
     */
    public function get_idref($type)
    {
        if (!is_string($type)) {
            throw new OpentransException('$type must be a string.');
        } else if (!in_array($type, OpentransDocumentIdref::$valid_types)) {
            throw new OpentransException('Unsupported idref type "' . $type . '".');
        }

        for ($i = 0; $i < count($this->idrefs); $i++) {

            if ($this->idrefs[$i]->get_type() == $type) {
                return $this->idrefs[$i]->get_id();
            }

        }

        return false;
    }
}