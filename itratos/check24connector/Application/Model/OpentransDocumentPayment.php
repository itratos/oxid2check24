<?php


namespace Itratos\Check24Connector\Application\Model;

class OpentransDocumentPayment
{

    /**#@+
     * Constants
     */
    /**
     * TERM_TYPE_UNECE
     *
     * Value is defined by UNECE convention 4279
     *
     * @var const string
     */
    const TERM_TYPE_UNECE = 'unece';

    /**
     * TERM_TYPE_OTHER
     *
     * @var const string
     */
    const TERM_TYPE_OTHER = 'other';

    /**
     * TERM_VALUE_UNECE_INVOICE
     *
     * @var const string
     */
    const TERM_VALUE_UNECE_INVOICE = '10';

    /**
     * TERM_VALUE_UNECE_PREPAID
     *
     * @var const string
     */
    const TERM_VALUE_UNECE_PREPAID = '25';

    /**
     * TERM_VALUE_UNECE_CASHONDELIVERY
     *
     * @var const string
     */
    const TERM_VALUE_UNECE_CASHONDELIVERY = '52';

    /**
     * TERM_VALUE_UNECE_BANKTRANSFER
     *
     * @var const string
     */
    const TERM_VALUE_UNECE_BANKTRANSFER = '54';

    /**
     * TERM_VALUE_UNECE_CREDITCARD
     *
     * @var const string
     */
    const TERM_VALUE_UNECE_CREDITCARD = '62';

    /**
     * TYPE_CARD
     *
     * Paymenttype is creditcard
     *
     * @var const string
     */
    const TYPE_CARD = 'card';

    /**
     * TYPE_ACCOUNT
     *
     * Payment is with bank account
     *
     * @var const string
     */
    const TYPE_ACCOUNT = 'account';

    /**
     * TYPE_DEBIT
     *
     * @var const string
     */
    const TYPE_DEBIT = 'debit';

    /**
     * TYPE_CHECK
     *
     * @var const string
     */
    const TYPE_CHECK = 'check';

    /**
     * TYPE_CASH
     *
     * @var const string
     */
    const TYPE_CASH = 'cash';

    /**
     * TYPE_OTHER
     *
     * @var const string
     */
    const TYPE_OTHER = 'other';

    /**
     * openTrans defined and valid payment types
     *
     * @var static array
     */
    static $valid_types = array(
        self::TYPE_CARD,
        self::TYPE_ACCOUNT,
        self::TYPE_DEBIT,
        self::TYPE_CHECK,
        self::TYPE_CASH,
        self::TYPE_OTHER
    );

    /**
     * @var string
     */
    protected $type = NULL;

    /**
     * @var string
     */
    protected $term_type = NULL;

    /**
     * @var string
     */
    protected $term_value = NULL;


    /**
     * Contains payment specific data like CC-Number for Creditcard,
     * Acocunt Number for direct debit...
     *
     * @var array
     */
    protected $payment_specific_data = array();


    /**
     * Construct a openTrans payment
     *
     * @param string type
     * @param string term_type
     * @param string term_value
     */
    public function __construct($type, $term_type = NULL, $term_value = NULL)
    {

        if (!is_string($type)) {
            throw new OpentransException('$type must be a string.');
        }

        if ($term_type !== NULL && !is_string($term_type)) {
            throw new OpentransException('$term_type must be a string.');
        }

        if ($term_value !== NULL && !is_string($term_value)) {
            throw new OpentransException('$term_value must be a string.');
        }

        if (!in_array($type, self::$valid_types)) {
            throw new OpentransException('Unsupported payment type "' . $type . '"');
        }

        $this->type = $type;
        $this->term_type = $term_type;
        $this->term_value = $term_value;

    }

    /**
     * Returns type
     *
     * @return string type
     */
    public function get_type()
    {
        return $this->type;
    }

    /**
     * Returns term_type
     *
     * @return string term_type
     */
    public function get_term_type()
    {
        return $this->term_type;
    }

    /**
     * Returns term_value
     *
     * @return string term_value
     */
    public function get_term_value()
    {
        return $this->term_value;
    }

    public function get_payment_specific_data()
    {
        return $this->payment_specific_data;
    }

    public function get_payment_specific_data_element($name)
    {

        if (isset($this->payment_specific_data[$name])) {
            return $this->payment_specific_data[$name];
        }

    }

    public function set_payment_specific_data_element($key, $value)
    {
        $this->payment_specific_data[$key] = $value;
    }

}