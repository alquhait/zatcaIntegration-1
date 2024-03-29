<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class SimpleInvoice extends Model
{
    private $schema21 = 'http://docs.oasis-open.org/ubl/os-UBL-2.1/xsd/maindoc/UBL-Invoice-2.1.xsd';
    private $schema22 = 'http://docs.oasis-open.org/ubl/os-UBL-2.2/xsd/maindoc/UBL-Invoice-2.2.xsd';
    private $schema;
    public static $base_dir = 'xmls';

    public function setSchema($ver='2.1') {
        if($ver=='2.1') {
            $this->schema = $this->schema21;
        }
        $this->schema = $this->schema22;
    }

    public function getSchema() {
        return $this->schema;
    }

    public function isValidSchema($dom=null) {
        if($dom && $dom->schemaValidate($this->getSchema())) {
            return true;
        }
        return false;
    }

    public function getSimpleInvoice() {
        // Address country
        $country = (new \NumNum\UBL\Country())
            ->setIdentificationCode('BE');

        // Full address
        $address = (new \NumNum\UBL\Address())
            ->setStreetName('Korenmarkt')
            ->setBuildingNumber(1)
            ->setCityName('Gent')
            ->setPostalZone('9000')
            ->setCountry($country);

        // Supplier company node
        $supplierCompany = (new \NumNum\UBL\Party())
            ->setName('Supplier Company Name')
            ->setPhysicalLocation($address)
            ->setPostalAddress($address);

        // Client contact node
        $clientContact = (new \NumNum\UBL\Contact())
            ->setName('Client name')
            ->setElectronicMail('email@client.com')
            ->setTelephone('0032 472 123 456')
            ->setTelefax('0032 9 1234 567');

        // Client company node
        $clientCompany = (new \NumNum\UBL\Party())
            ->setName('My client')
            ->setPostalAddress($address)
            ->setContact($clientContact);

        $legalMonetaryTotal = (new \NumNum\UBL\LegalMonetaryTotal())
            ->setPayableAmount(10 + 2)
            ->setAllowanceTotalAmount(0);

        // Tax scheme
        $taxScheme = (new \NumNum\UBL\TaxScheme())
            ->setId(0);

        // Product
        $productItem = (new \NumNum\UBL\Item())
            ->setName('Product Name')
            ->setDescription('Product Description')
            ->setSellersItemIdentification('SELLERID');

        // Price
        $price = (new \NumNum\UBL\Price())
            ->setBaseQuantity(1)
            ->setUnitCode(\NumNum\UBL\UnitCode::UNIT)
            ->setPriceAmount(10);

        // Invoice Line tax totals
        $lineTaxTotal = (new \NumNum\UBL\TaxTotal())
            ->setTaxAmount(2.1);

        // InvoicePeriod
        $invoicePeriod = (new \NumNum\UBL\InvoicePeriod())
            ->setStartDate(new \DateTime());

        // Invoice Line(s)
        $invoiceLines = [];

        $invoiceLines[] = (new \NumNum\UBL\InvoiceLine())
            ->setId(0)
            ->setItem($productItem)
            ->setInvoicePeriod($invoicePeriod)
            ->setPrice($price)
            ->setTaxTotal($lineTaxTotal)
            ->setInvoicedQuantity(1);

        $invoiceLines[] = (new \NumNum\UBL\InvoiceLine())
            ->setId(0)
            ->setItem($productItem)
            ->setInvoicePeriod($invoicePeriod)
            ->setPrice($price)
            ->setAccountingCost('Product 123')
            ->setTaxTotal($lineTaxTotal)
            ->setInvoicedQuantity(1);

        $invoiceLines[] = (new \NumNum\UBL\InvoiceLine())
            ->setId(0)
            ->setItem($productItem)
            ->setInvoicePeriod($invoicePeriod)
            ->setPrice($price)
            ->setAccountingCostCode('Product 123')
            ->setTaxTotal($lineTaxTotal)
            ->setInvoicedQuantity(1);

        // Total Taxes
        $taxCategory = (new \NumNum\UBL\TaxCategory())
            ->setId(0)
            ->setName('VAT21%')
            ->setPercent(.21)
            ->setTaxScheme($taxScheme);

        $taxSubTotal = (new \NumNum\UBL\TaxSubTotal())
            ->setTaxableAmount(10)
            ->setTaxAmount(2.1)
            ->setTaxCategory($taxCategory);


        $taxTotal = (new \NumNum\UBL\TaxTotal())
            ->addTaxSubTotal($taxSubTotal)
            ->setTaxAmount(2.1);

        // Invoice object
        $invoice = (new \NumNum\UBL\Invoice())
            ->setId(1234)
            ->setCopyIndicator(false)
            ->setIssueDate(new \DateTime())
            ->setAccountingSupplierParty($supplierCompany)
            ->setAccountingCustomerParty($clientCompany)
            ->setSupplierAssignedAccountID('10001')
            ->setInvoiceLines($invoiceLines)
            ->setLegalMonetaryTotal($legalMonetaryTotal)
            ->setTaxTotal($taxTotal);

        // Use \NumNum\UBL\Generator to generate an XML string
        $generator = new \NumNum\UBL\Generator();
        $outputXMLString = $generator->invoice($invoice);

        return $outputXMLString;
    }

    public function getSimpleInvoice2() {
        // Address country
        $country = (new \NumNum\UBL\Country())
            ->setIdentificationCode('BE');

        // Full address
        $address = (new \NumNum\UBL\Address())
            ->setStreetName('Korenmarkt')
            ->setBuildingNumber(1)
            ->setCityName('Gent')
            ->setPostalZone('9000')
            ->setCountry($country);

        // Supplier company node
        $supplierCompany = (new \NumNum\UBL\Party())
            ->setName('Supplier Company Name')
            ->setPhysicalLocation($address)
            ->setPostalAddress($address);

        // Client company node
        $clientCompany = (new \NumNum\UBL\Party())
            ->setName('My client')
            ->setPostalAddress($address);

        $legalMonetaryTotal = (new \NumNum\UBL\LegalMonetaryTotal())
            ->setPayableAmount(10 + 2)
            ->setAllowanceTotalAmount(0);

        // Tax scheme
        $taxScheme = (new \NumNum\UBL\TaxScheme())
            ->setId(0);

        // Product
        $productItem = (new \NumNum\UBL\Item())
            ->setName('Product Name')
            ->setDescription('Product Description');

        // Price
        $price = (new \NumNum\UBL\Price())
            ->setBaseQuantity(1)
            ->setUnitCode(\NumNum\UBL\UnitCode::UNIT)
            ->setPriceAmount(10);

        // Invoice Line tax totals
        $lineTaxTotal = (new \NumNum\UBL\TaxTotal())
            ->setTaxAmount(2.1);

        // Invoice Line(s)
        $invoiceLine = (new \NumNum\UBL\InvoiceLine())
            ->setId(0)
            ->setItem($productItem)
            ->setPrice($price)
            ->setTaxTotal($lineTaxTotal)
            ->setInvoicedQuantity(1);

        $invoiceLines = [$invoiceLine];

        // Total Taxes
        $taxCategory = (new \NumNum\UBL\TaxCategory())
            ->setId(0)
            ->setName('VAT21%')
            ->setPercent(.21)
            ->setTaxScheme($taxScheme);

        $taxSubTotal = (new \NumNum\UBL\TaxSubTotal())
            ->setTaxableAmount(10)
            ->setTaxAmount(2.1)
            ->setTaxCategory($taxCategory);

        $taxTotal = (new \NumNum\UBL\TaxTotal())
            ->addTaxSubTotal($taxSubTotal)
            ->setTaxAmount(2.1);

        // Invoice object
        $invoice = (new \NumNum\UBL\Invoice())
            ->setUBLVersionID('2.2')
            ->setId(1234)
            ->setCopyIndicator(false)
            ->setIssueDate(new \DateTime())
            ->setAccountingSupplierParty($supplierCompany)
            ->setAccountingCustomerParty($clientCompany)
            ->setInvoiceLines($invoiceLines)
            ->setLegalMonetaryTotal($legalMonetaryTotal)
            ->setTaxTotal($taxTotal);

        // Use \NumNum\UBL\Generator to generate an XML string
        $generator = new \NumNum\UBL\Generator();
        $outputXMLString = $generator->invoice($invoice);

        return $outputXMLString;
    }

    public function fileName($pre='SimpleInvoice') {
        $time = date('y_m_d_h_i');
        $filename = $pre.'_'.$time.'.xml';
        return $filename;
    }

    public function fileAnchor($filename) {
        return '<a href="'.url('xmls/'.$filename).'" target="_blank">'.$filename.'</a>';
    }
}
