<?php

namespace App\Modules\Company\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Company extends Model
{
    protected $table='companies';

    protected $fillable = [
        'company_name',
        'address',
        'phone',
        'email',
        'image',
        'dealer_id',
        'status',
        'tax_settings',
        'smsphone',
        'brand_image',
        'serial_settings',
        'warranty_settings',
        'height',
        'fontsize',
        'displaychecked',
        'mrpDisable',
        'amountDisable',
        'discountDisable',
        'bookingEnable',
        'conditionEnable',
        'headerDisable',
        'invoiceHieght',
        'CustomerIdEnable',
        'ReportFontSizeEnable',
        'ReportfontSize',
        'allreportfontsize',
        'termsConditionEnable',
        'termsconditionField',
        'deliveryDateEnable',
        'signatureFooterEnable',
        'peparedByEnable',
        'productCodeEnable',
        'invoicefontSize',
        'invoiceHeaderTitle',
        'workorderEnable',
        'StockStatusEnable',
        'discountbellowEnable',
        'customizequotationEnable',
        'automobileEnable',
        'scheduleEnable',
        'numberOfSchedule',
        'scheduleGap',
        'TaxCTerritoryEnable',
        'salesListTaxEnable',
        'variantEnable',
        'productVatTaxEnable',
        'parentPrice',
        'partialInvoiceEnable',
        'enableDisplayName',
        'oldBikeEnable',
        'selectedInvoicePage',
        'selectedCategory',
        'enableInstallmentCal',
        'header',
        'footer',
        'signature',
        'attachment',
        'quotationPreloaderEnable',
        'allowNegativeStockEnable',
        'enableNiTax',
        'adjustment_chart_id',
        'category_wise_product',
        'enable_barcode',
        'enablePackageProductRel',
        'enableSalesMeasure',
        'packaging_expense',
        'enable_data_import',
        'disc_first_level',
        'disc_second_level',
        'disc_third_level',
        'disc_first_cal',
        'disc_second_cal',
        'disc_third_cal',
        'disc_first_enable',
        'disc_second_enable',
        'disc_third_enable',
        'binNumberEnable',
        'commission_level',
        'isCustomerDueEnable',
        'visibleProductElement',
        'enable_advance_due_payment_date',
        'enable_due_payment_date',
        'partial_sales_label',
        'full_sales_label',
        'enableCustomerNumber',
        'enableCustomerAddress',
        'enableSalesPurchasePrice',
        'salesLable',
        'selectedPaymentMethod',
        'enableHalfPageInvoice',
        'selectedDefaultSalesType',
        'defaultCustomerId',
        'selectedQuotationPage',
        'selectedChallanPage',
        'grandTotalDefaultForInstallment',
        'defaultVatEnable',
        'defaultReceiveVat',
        'selectedVatType',
        'enable_invoice_verification',
        'verifier_details',
        'enable_pos_print',
        'studentDefaultItem',
        'studentReadmissionDefaultItem',
        'repeatedDefaultItem',
        'ReportFontSize',
        'principal_details_in_certificate',
        'salary_system',
        'water_pressure_photo',
        'water_pressure_height',
        'water_pressure_margin_top',
        'water_pressure_margin_left',
        'principle_signature',
        'admit_card_design',
        'principle_signature_height',
        'rollEnableOnAdmitCard',
        'ownerMobileNumber',
        'late_fine_system',
        'enable_multiple_payment',
        'DefaultPaymentMethod',
        'enable_student_type_in_receipt',
        'DefaultPaymentMethod',
        'dollarExhangeRateBDT',
        'invoiceVatPercent',
        'accountTitle',
        'AccountNo',
        'bankName',
        'branchName',
    ];

// Relations
    public function relOutlet(){
        return $this->hasOne('App\Modules\Product\Models\Outlet', 'id', 'outlet_id');
    }


    // TODO :: boot
    // boot() function used to insert logged user_id at 'created_by' & 'updated_by'
    public static function boot(){
        parent::boot();
        static::creating(function($query){
            if(Auth::check()){
                $query->created_by = Auth::user()->id;
            }
        });
        static::updating(function($query){
            if(Auth::check()){
                $query->updated_by = Auth::user()->id;
            }
        });
    }
}
