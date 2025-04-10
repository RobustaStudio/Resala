<?php

namespace RobustTools\Resala\Support;

use DOMAttr;
use DOMDocument;
use DOMElement;

final class VodafonePayloadBuilder
{
    private DOMDocument $domDocument;

    private string $message;

    private string $secureHash;

    private string $accountId;

    private string $senderName;

    private string $password;

    /** @var string|array */
    private $recipients;

    public function __construct(
        string $accountId,
        string $password,
        string $senderName,
        string $secureHash,
        $recipients,
        string $message
    ) {
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

    public function build(): string
    {
        $root = $this->rootElement();
        $this->setRootElementAttributes($root);
        $this->generateAccountIdElement($root);
        $this->generatePasswordElement($root);
        $this->generateSecureHashElement($root);

        is_array($this->recipients)
            ? array_map(fn ($recipient) => $this->generateSMSListElement($root, $recipient), $this->recipients)
            : $this->generateSMSListElement($root, $this->recipients);

        $this->domDocument->appendChild($root);

        return $this->domDocument->saveXML();
    }

    private function rootElement(): DOMElement
    {
        return $this->domDocument->createElement('SubmitSMSRequest');
    }

    private function setRootElementAttributes(DOMElement $rootElement): void
    {
        $attr_xmlns = new DOMAttr('xmlns:', "http://www.edafa.com/web2sms/sms/model/");
        $attr_xmlns_xsi = new DOMAttr("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");

        $attr_xsi_schemaLocation = new DOMAttr(
            "xsi:schemaLocation",
            "http://www.edafa.com/web2sms/sms/model/ SMSAPI.xsd"
        );

        $attr_xsi_type = new DOMAttr("xsi:type", "SubmitSMSRequest");

        $rootElement->setAttributeNode($attr_xmlns);
        $rootElement->setAttributeNode($attr_xmlns_xsi);
        $rootElement->setAttributeNode($attr_xsi_schemaLocation);
        $rootElement->setAttributeNode($attr_xsi_type);
    }

    private function generateAccountIdElement(DOMElement $rootElement): void
    {
        $rootElement->appendChild($this->domDocument->createElement('AccountId', $this->accountId));
    }

    private function generatePasswordElement(DOMElement $rootElement): void
    {
        $rootElement->appendChild($this->domDocument->createElement('Password', $this->password));
    }

    private function generateSecureHashElement(DOMElement $rootElement): void
    {
        $rootElement->appendChild($this->domDocument->createElement('SecureHash', $this->secureHash));
    }

    private function generateSMSListElement(DOMElement $rootElement, string $recipient): void
    {
        $sms_list_node = $rootElement->appendChild($this->domDocument->createElement("SMSList"));
        $sms_list_node->appendChild($this->domDocument->createElement("SenderName", $this->senderName));
        $sms_list_node->appendChild($this->domDocument->createElement("ReceiverMSISDN", $recipient));
        $sms_list_node->appendChild($this->domDocument->createElement("SMSText", $this->message));
    }
}
