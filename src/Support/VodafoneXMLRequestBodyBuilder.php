<?php


namespace RobustTools\SMS\Support;


use DOMAttr;
use DOMDocument;
use DOMElement;

final class VodafoneXMLRequestBodyBuilder
{

    /**
     * @var DOMDocument
     */
    private $domDocument;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $secureHash;

    /**
     * @var string
     */
    private $accountId;

    /**
     * @var string
     */
    private $senderName;

    /**
     * @var string
     */
    private $password;

    /**
     * @var array
     */
    private $recipients;


    /**
     * VodafoneXMLRequestBodyRenderer constructor.
     * @param string $accountId
     * @param string $password
     * @param string $senderName
     * @param string $secureHash
     * @param array $recipients
     * @param string $message
     */
    public function __construct (string $accountId, string $password, string $senderName, string $secureHash, array $recipients, string $message)
    {
        $this->secureHash = $secureHash;
        $this->accountId = $accountId;
        $this->password = $password;
        $this->senderName = $senderName;
        $this->message = $message;
        $this->domDocument = new DOMDocument();
        $this->domDocument->encoding = "utf-8";
        $this->domDocument->xmlVersion = "1.0";
        $this->domDocument->formatOutput = true;
        $this->recipients = $recipients;
    }


    /**
     * Build request XML Body
     *
     * @return string
     */
    public function build ()
    {

        $root = $this->rootElement();
        $this->setRootElementAttributes($root);
        $this->generateAccountIdElement($root);
        $this->generatePasswordElement($root);
        $this->generateSecureHashElement($root);

        foreach ($this->recipients as $recipient) {
            $this->generateSMSListElement($root, $recipient);
        }

        $this->domDocument->appendChild($root);
        return $this->domDocument->saveXML();
    }


    /**
     * Generate root node.
     *
     * @return DOMElement
     */
    private function rootElement () : DOMElement
    {
        return $this->domDocument->createElement('SubmitSMSRequest');
    }

    /**
     * Set root node attributes
     *
     * @param DOMElement $rootElement
     * @return void
     */
    private function setRootElementAttributes ($rootElement) : void
    {
        $attr_xmlns = new DOMAttr('xmlns:', "http://www.edafa.com/web2sms/sms/model/");
        $attr_xmlns_xsi = new DOMAttr("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $attr_xsi_schemaLocation = new DOMAttr("xsi:schemaLocation", "http://www.edafa.com/web2sms/sms/model/ SMSAPI.xsd");
        $attr_xsi_type = new DOMAttr("xsi:type", "SubmitSMSRequest");

        $rootElement->setAttributeNode($attr_xmlns);
        $rootElement->setAttributeNode($attr_xmlns_xsi);
        $rootElement->setAttributeNode($attr_xsi_schemaLocation);
        $rootElement->setAttributeNode($attr_xsi_type);
    }


    /**
     * Generate Account Id element.
     *
     * @param DOMElement $rootElement
     * @return void
     */
    private function generateAccountIdElement ($rootElement) : void
    {
        $rootElement->appendChild($this->domDocument->createElement('AccountId', $this->accountId));
    }

    /**
     * Generate Password element.
     *
     * @param DOMElement $rootElement
     * @return void
     */
    private function generatePasswordElement ($rootElement) : void
    {
        $rootElement->appendChild($this->domDocument->createElement('Password', $this->password));
    }

    /**
     * Generate SecureHash element.
     *
     * @param DOMElement $rootElement
     * @return void
     */
    private function generateSecureHashElement ($rootElement) : void
    {
        $rootElement->appendChild($this->domDocument->createElement('SecureHash', $this->secureHash));
    }

    /**
     * Generate SMSList element.
     *
     * @param DOMElement $rootElement
     * @param $recipient
     * @return void
     */
    private function generateSMSListElement ($rootElement, $recipient) : void
    {
        $sms_list_node = $rootElement->appendChild($this->domDocument->createElement("SMSList"));
        $sms_list_node->appendChild($this->domDocument->createElement("SenderName", $this->senderName));
        $sms_list_node->appendChild($this->domDocument->createElement("ReceiverMSISDN", $recipient));
        $sms_list_node->appendChild($this->domDocument->createElement("SMSText", $this->message));
    }
}
