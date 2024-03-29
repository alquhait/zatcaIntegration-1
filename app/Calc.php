<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Calc extends Model
{
	private $invoice;
	private $taxIncluded;
	private $taxes;

    public function getInvoice() {
        return $this->invoice;
    }

    public function setInvoice($invoice): Calc {
        $this->invoice = $invoice;
        return $this;
    }

    public function setTaxIncluded($taxIncluded): Calc {
        $this->taxIncluded = $taxIncluded;
        return $this;
    }

    public function setTaxes($taxes): Calc {
        $this->taxes = $taxes;
        return $this;
    }

    public function linesNetAmount() {
    	$total = 0;
    	foreach($invoice['line_items'] as $item) {
    		$total += $item['qty'] * $item['price'];
    	}
    	return $total;
    }

    public function getLineTaxRate($stock_item) {
        if (isset($stock_item->tax)) {
            if ($this->taxIncluded) {
                $rate = $stock_item->tax == '0' ? 0 : round(100 * $stock_item->tax / ($stock_item->price - $stock_item->tax), 0);
            } else {
                $rate = $stock_item->tax == '0' ? 0 : round(100 * $stock_item->tax / $stock_item->price, 0);
            }

            return $rate;
        }

        $taxList = $this->taxes;
        $item_tax_type_id = $stock_item->tax_type;
        $item_tax_name = $stock_item->tax_type_name;
        $taxKey = array_search($item_tax_type_id, array_column($taxList, 'id'));
        $taxes = $taxList[$taxKey]['taxes'] ?? null;

        if($taxes) {
            $row_key = array_search($item_tax_name, array_column($taxes, 'name'));
            if(isset($taxes[$row_key])) {
                return $taxes[$row_key]['rate'] ?? 0;
            }
        }
        return 0;
    }

    public function calculateItemTax($stock_item, $item_price) {
        $tax_rate = $this->getLineTaxRate($stock_item);
        $tax_rate = (floatval($tax_rate) / 100);
        $tax_amount = 0;

        if(!$this->taxIncluded) {
            $tax_amount = (($item_price) * $tax_rate);
        } else {
            $tax_amount = $item_price - ($item_price / (1 + $tax_rate));
        }
        return $tax_amount;
    }

    public function calcItemTotal($stock_item, $totalExcludedTax=true) {
    	$stock_item = (object) $stock_item;

    	list($total, $discount) = $this->getLineTotal($stock_item);
        $tax_amount = $this->calculateItemTax($stock_item, $total);

        if($totalExcludedTax) {
        	if($this->taxIncluded)
            	return ($total - $tax_amount);
           	else
           		return $total;
        }

        return ($this->taxIncluded) ? $total : ($total + $tax_amount);
    }

    public function getLineTotal($stock_item) {
        $quantity = $stock_item->qty_dispatched;

        $line_total = 0;
        $discount = 0;
        $discount = $quantity * $stock_item->price * $stock_item->discount;
        $line_total = $line_total + $quantity * $stock_item->price * (1 - $stock_item->discount);

        return [$line_total, $discount];
    }
}
