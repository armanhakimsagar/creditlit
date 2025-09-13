<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="shortcut icon" href="assets/images/logo-dark.svg" type="image/x-icon">
    <title>credilit</title>
    <!-- font awasome link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">

    <!-- bootstrap link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <!-- font link -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/styles.css">
    <style>  
    /* font-family: 'Open Sans', sans-serif; */

/* typography */

* {
    margin: 0;
    padding: 0;
    outline: 0;
    box-sizing: border-box;
}

body {
    background: #ffffff;
}

img {
    width: 100%;
}

.full-wrapper {
    width: 100%;
    height: 1122px;
    margin: 0 auto;
    position: relative;
}

.footer-area {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    width: 100%;
    bottom: 0;
}

.invoice-main-area {
    padding: 48px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
}

.logo {
    width: 150px;
}


.header-right-side h1 {
    font-size: 14px;
    font-weight: 800;
    font-family: Arial;
    color: #052B79;
    text-align: right;
    text-transform: uppercase;
    margin-bottom: 5px;
}

.header-right-side h2 {
    font-size: 10px;
    font-weight: 500;
    font-family: 'Open Sans', sans-serif;
    color: #000000;
    text-align: right;
    margin-bottom: 2px;
}


.invoice-header h1 {
    font-size: 16px;
    font-weight: bold;
    font-family: Arial;
    color: #052B79;
    text-align: center;
    margin-bottom: 0;
    text-transform: uppercase;
}

hr {
    margin-top: 5px;
    height: 3px;
    background: #052B79;
}

.invoice-details-header {
    background: #C8D9FD;
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 19px;
    margin-top: 30px;
    padding: 0 10px;

}

.invoice-details-header span {
    font-size: 10px;
    font-weight: 800;
    font-family: Arial;
    color: #052B79;
    text-transform: uppercase;
    width: 50%;
    text-align: left;
}

.invoice-details {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 50px;
}

.buyer-details {
    padding-left: 10px;
    width: 50%;
}

.invoice-details {
    margin-top: 7px;
}

.buyer-details h1 {
    font-size: 10px;
    font-weight: bold;
    font-family: 'Open Sans', sans-serif;
    color: #000000;
    text-align: left;
    margin-bottom: 0;
}

.buyer-details h2 {
    font-size: 10px;
    font-weight: 500;
    font-family: 'Open Sans', sans-serif;
    color: #000000;
    text-align: left;
    margin-bottom: 2px;
}

.bill-details {
    display: flex;
    justify-content: flex-start;
    align-items: flex-start;
    column-gap: 20px;
    width: 50%;
}

.bill-details h2 {
    font-size: 10px;
    font-weight: 500;
    font-family: 'Open Sans', sans-serif;
    color: #000000;
    text-align: left;
    margin-bottom: 2px;
}

.bill-details-title h2 {
    text-align: right;
}

/* bill table area start */
table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: #C8D9FD;
}

th {
    border: none;
    text-align: center;
    height: 21px;
    font-size: 10px;
    font-weight: 800;
    font-family: Arial;
    color: #052B79;

}

td {
    border: 1px solid black;
    font-size: 10px;
    font-weight: 500;
    font-family: 'Open Sans', sans-serif;
    color: #000000;
    padding: 0 5px;
    height: 21px;
    text-align: center;
}

tr td:nth-child(2) {
    text-align: left;
}

/* Customize column width and height */
colgroup {
    col:nth-child(1) {
        width: 5%;
    }

    col:nth-child(2) {
        width: 50%;
    }

    col:nth-child(3) {
        width: 15%;
    }

    col:nth-child(4) {
        width: 15%;
    }

    col:nth-child(5) {
        width: 15%;
    }
}

/* bill table area end */

.note {
    font-family: Arial;
    color: #052B79;
    font-size: 8px;
    font-weight: 500;
}

.calculation-area {
    display: flex;
    justify-content: flex-end;
    align-items: flex-start;
    column-gap: 10px;
}

.calculation-title {
    background: #C8D9FD;
    padding: 7px 5px 5px 15px;
}

.calculation-title h3 {
    font-size: 8px;
    font-weight: 800;
    font-family: Arial;
    color: #052B79;
    text-align: right;
    margin-bottom: 9px;
}

.calculation-title h1 {
    font-size: 11px;
    font-weight: 800;
    font-family: Arial;
    color: #052B79;
    text-align: right;
    margin-bottom: 4px;
}

.td-color>td {
    color: #052B79;
    text-align: center !important;
    
}

.td-bold {
    font-weight: 800;
    font-size: 11px;

}

.calculation-table {
    width: 30%;
}

.footer-head {
    margin-top: 50px;
}

.margin-bottom>h2 {
    margin-bottom: 7px !important;
}

.account-details {
    margin-left: 30px;
}

.special-note {
    font-size: 10px;
    font-weight: 900;
    font-family: 'Open Sans', sans-serif;
    color: #000000;
}

.no-need-signeture {
    font-size: 9px;
    font-weight: 500;
    font-family: Arial;
    color: #052B79;
    text-align: center;
    margin: 64px 0 20px 0;

}

.company-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 50px;
    margin-bottom: 45px;
}

.contact-icon {
    width: 30px;
    height: 30px;
    color: #ffffff;
    font-size: 15px;
    background: #052B79;
    text-align: center;
}

.contact-item {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    row-gap: 10px;
}

.contact-item p {
    font-size: 12px;
    font-weight: 600;
    font-family: Arial;
    color: #000000;
    margin-bottom: 0;
    text-align: center;
}





/* page one end */


/* page two start */
.statment-header {
    display: flex;
    justify-content: left;
    align-items: center;
    column-gap: 10px;
    
}

.statment-header>span:nth-child(1) {
    background: #C8D9FD;
    font-size: 10px;
    font-weight: 800;
    font-family: Arial;
    color: #052B79;
    width: 15%;
    text-align: right;
    padding: 3px 10px;

}

.statment-header>span:nth-child(2) {
    font-size: 10px;
    font-weight: 800;
    font-family: Arial;
    color: #000000;
    padding: 3px 10px;

}
.statment th{
    border: 1px solid #000000;
}
.statment colgroup {
    col:nth-child(1) {
        width: 5%;
    }

    col:nth-child(2) {
        width: 10%;
    }

    col:nth-child(3) {
        width: 10%;
    }

    col:nth-child(4) {
        width: 20%;
    }

    col:nth-child(5) {
        width: 30%;
    }

    col:nth-child(6) {
        width: 15%;
    }

    col:nth-child(7) {
        width: 10%;
    }
}

.statment-total-amount {
    display: flex;
    justify-content: flex-end;
    align-items: flex-start;
}

.statment-total-amount span {
    font-size: 11px;
    font-weight: 800;
    font-family: Arial;
    color: #000000;
    width: 10%;
    text-align: center;

}

.statment-total-amount span:nth-child(1) {
    background: #C8D9FD;
    width: 15%;
}  
        @media print {
    header {
        position: fixed;
        top: 0;
    }
    footer-area {
        position: fixed;
        bottom: 0;
    }
}
      </style>
</head>

<d>
    <div class="full-wrapper">
        <div class="container-fluid p-0">
            <div class="invoice-main-area">
                <!-- header area start -->
                <div class="header">
                    <div class="header-left-side">
                        <div class="logo">
                            <a href="#">
                                <img src="assets/images/logo-dark.svg" alt="logo">
                            </a>
                        </div>
                    </div>
                    <div class="header-right-side">
                        <h1>CREDILIT LIMITED</h1>
                        <h2>3rd Floor, 37/A, Central Road</h2>
                        <h2>Dhanmondi, Dhaka 1205</h2>
                        <h2>Bangladesh</h2>
                    </div>
                </div>

                <div class="invoice-header">
                    <h1>INVOICE SUMMERY</h1>
                    <hr>
                </div>
                <div class="invoice-details-area">
                    <div class="invoice-details-header">
                        <span>BILL TO </span>
                        <span>INVOICE DETAILS </span>
                    </div>
                    <!-- buyer details area start -->
                    <div class="invoice-details">
                        <div class="buyer-details">
                            <h2>Attn: Bank/Branch Key Personal Name</h2>
                            <h2>Head Office Branch</h2>
                            <h1>The City Bank Limited</h1>
                            <h2>City Bank Center, 28 Gulshan Avenue</h2>
                            <h2>Dhaka 1212, Bangladesh</h2>
                        </div>
                        <div class="bill-details">
                            <div class="bill-details-title">
                                <h2>Product</h2>
                                <h2>Invoice Date</h2>
                                <h2>Invoice No</h2>
                                <h2>Billing Period</h2>

                            </div>
                            <div class="bill-details-content">
                                <h2>Business Credit Risk Report</h2>
                                <h2>07 December 2023</h2>
                                <h2>2023128645</h2>
                                <h2>November 2023</h2>

                            </div>
                        </div>
                    </div>
                    <!-- buyer details area end -->
                </div>
                <!-- header area end -->

                <div class="main-bill-table">
                    <table>
                        <colgroup>
                            <col style="height: 30px;">
                            <col style="height: 30px;">
                            <col style="height: 30px;">
                            <col style="height: 30px;">
                            <col style="height: 30px;">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>BRANCH NAME</th>
                                <th>NO OF REPORT</th>
                                <th>TOTAL (USD)</th>
                                <th>TOTAL (BDT)</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>01</td>
                                <td>Gulshan Avenue</td>
                                <td>10</td>
                                <td>100</td>
                                <td>10,000</td>
                            </tr>
                            <tr>
                                <td>01</td>
                                <td>Gulshan Avenue</td>
                                <td>10</td>
                                <td>100</td>
                                <td>10,000</td>
                            </tr>
                            <tr>
                                <td>01</td>
                                <td>Gulshan Avenue</td>
                                <td>10</td>
                                <td>100</td>
                                <td>10,000</td>
                            </tr>
                            <tr>
                                <td>01</td>
                                <td>Gulshan Avenue</td>
                                <td>10</td>
                                <td>100</td>
                                <td>10,000</td>
                            </tr>
                            <tr>
                                <td>01</td>
                                <td>Gulshan Avenue</td>
                                <td>10</td>
                                <td>100</td>
                                <td>10,000</td>
                            </tr>
                            <tr>
                                <td>01</td>
                                <td>Gulshan Avenue</td>
                                <td>10</td>
                                <td>100</td>
                                <td>10,000</td>
                            </tr>
                            <tr>
                                <td>01</td>
                                <td>Gulshan Avenue</td>
                                <td>10</td>
                                <td>100</td>
                                <td>10,000</td>
                            </tr>
                            <tr>
                                <td>01</td>
                                <td>Gulshan Avenue</td>
                                <td>10</td>
                                <td>100</td>
                                <td>10,000</td>
                            </tr>
                            <tr>
                                <td>01</td>
                                <td>Gulshan Avenue</td>
                                <td>10</td>
                                <td>100</td>
                                <td>10,000</td>
                            </tr>
                            <tr>
                                <td>01</td>
                                <td>Gulshan Avenue</td>
                                <td>10</td>
                                <td>100</td>
                                <td>10,000</td>
                            </tr>
                            <tr>
                                <td>01</td>
                                <td>Gulshan Avenue</td>
                                <td>10</td>
                                <td>100</td>
                                <td>10,000</td>
                            </tr>

                        </tbody>
                    </table>
                    <p class="note">USD to BDT Exchange Rate = 104.67</p>

                    <div class="calculation-area">
                        <div class="calculation-title">
                            <h3>VAT (15%)</h3>
                            <h3 style="font-weight: 700;">REBATE</h3>
                            <h1>TOTAL PAYABLE</h1>
                        </div>
                        <div class="calculation-table">
                            <table>
                                <tbody>
                                    <tr class="td-color">
                                        <td>180</td>
                                        <td>180</td>
                                    </tr>
                                    <tr class="td-color">
                                        <td></td>
                                        <td>200</td>
                                    </tr>
                                    <tr class="td-color ">
                                        <td class="td-bold">11,000</td>
                                        <td class="td-bold">94,500</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- bill footer area start -->
                <div class="footer-head">
                    <div class="invoice-details-header">
                        <span>PAYMENT INSTRUCTION</span>
                    </div>

                    <div class="bill-details account-details">
                        <div class="bill-details-title margin-bottom">
                            <h2>Account title</h2>
                            <h2>Account no</h2>
                            <h2>Bank</h2>
                            <h2>Branch</h2>

                        </div>
                        <div class="bill-details-content margin-bottom">
                            <h2>Credilit Limited</h2>
                            <h2>65432134567890</h2>
                            <h2>Dutch Bangla Bank Limited </h2>
                            <h2>Kawran Bazar Branch</h2>

                        </div>
                    </div>
                    <h1 class="special-note">Please write all cheques in favor of Credilit Limited</h1>

                    <p class="no-need-signeture">This is a computer-generated invoice and needs no signature.</p>
                </div>
                <!-- bill footer area end -->
            </div>

            <div class="footer-area">
                <div class="invoice-header">
                    <hr>
                </div>

                <div class="company-details">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div class="contact-way">
                            <p>+880 964 990 9990</p>
                            <p>+880 132 973 4171</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <div class="contact-way">
                            <p>support@credilit.com</p>
                            <p>www.credilit.com</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div class="contact-way">
                            <p>3rd Floor, 37/A, Central Road</p>
                            <p>Dhaka 1205 Bangladesh
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- statment section start -->

    <div class="full-wrapper">
        <div class="container-fluid p-0">
            <div class="invoice-main-area">
                <!-- header area start -->
                <div class="header">
                    <div class="header-left-side">
                        <div class="logo">
                            <a href="#">
                                <img src="assets/images/logo-dark.svg" alt="logo">
                            </a>
                        </div>
                    </div>
                    <div class="header-right-side">
                        <h1>CREDILIT LIMITED</h1>
                        <h2>Invoice No: 2023128645</h2>
                        <h2>Invoice Date: 07 December 2023 </h2>
                        <h2>Billing Period: November 2023 </h2>
                    </div>
                </div>

                <div class="invoice-header">
                    <h1>DETAILED STATEMENT</h1>
                    <hr>
                </div>
                <br>
                <div class="invoice-details-area">
                    <div class="statment-header">
                        <span>BRANCH:</span>
                        <span>Dhanmandi Mohila Branch</span>
                    </div>
                    <div class="main-bill-table statment">
                        <table>
                            <colgroup>
                                <col style="height: 30px;">
                                <col style="height: 30px;">
                                <col style="height: 30px;">
                                <col style="height: 30px;">
                                <col style="height: 30px;">
                                <col style="height: 30px;">
                                <col style="height: 30px;">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>DATE</th>
                                    <th>ORDER ID</th>
                                    <th>REFERENCE NO</th>
                                    <th>INQUIRY NAME</th>
                                    <th>COUNTRY</th>
                                    <th>PRICE ($)</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>01</td>
                                    <td>14-12-2023</td>
                                    <td>12121212</td>
                                    <td>MBL/MHK/CR/2023/0316</td>
                                    <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                    <td>Switzerland</td>
                                    <td>100</td>
                                </tr>
                                <tr>
                                    <td>02</td>
                                    <td>14-12-2023</td>
                                    <td>12121212</td>
                                    <td>MBL/MHK/CR/2023/0316</td>
                                    <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                    <td>Switzerland</td>
                                    <td>100</td>
                                </tr>
                                <tr>
                                    <td>03</td>
                                    <td>14-12-2023</td>
                                    <td>12121212</td>
                                    <td>MBL/MHK/CR/2023/0316</td>
                                    <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                    <td>Switzerland</td>
                                    <td>100</td>
                                </tr>
                                <tr>
                                    <td>04</td>
                                    <td>14-12-2023</td>
                                    <td>12121212</td>
                                    <td>MBL/MHK/CR/2023/0316</td>
                                    <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                    <td>Switzerland</td>
                                    <td>100</td>
                                </tr>
                                <tr>
                                    <td>05</td>
                                    <td>14-12-2023</td>
                                    <td>12121212</td>
                                    <td>MBL/MHK/CR/2023/0316</td>
                                    <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                    <td>Switzerland</td>
                                    <td>100</td>
                                </tr>
                                <tr>
                                    <td>06</td>
                                    <td>14-12-2023</td>
                                    <td>12121212</td>
                                    <td>MBL/MHK/CR/2023/0316</td>
                                    <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                    <td>Switzerland</td>
                                    <td>100</td>
                                </tr>
                                <tr>
                                    <td>07</td>
                                    <td>14-12-2023</td>
                                    <td>12121212</td>
                                    <td>MBL/MHK/CR/2023/0316</td>
                                    <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                    <td>Switzerland</td>
                                    <td>100</td>
                                </tr>
                                <tr>
                                    <td>08</td>
                                    <td>14-12-2023</td>
                                    <td>12121212</td>
                                    <td>MBL/MHK/CR/2023/0316</td>
                                    <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                    <td>Switzerland</td>
                                    <td>100</td>
                                </tr>
                                <tr>
                                    <td>09</td>
                                    <td>14-12-2023</td>
                                    <td>12121212</td>
                                    <td>MBL/MHK/CR/2023/0316</td>
                                    <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                    <td>Switzerland</td>
                                    <td>100</td>
                                </tr>



                            </tbody>
                        </table>

                        <div class="statment-total-amount">
                            <span>Branch Total</span>
                            <span>900</span>
                        </div>

                    </div>
                    <div class="statment-header">
                        <span>BRANCH:</span>
                        <span>Dhanmandi Mohila Branch</span>
                    </div>
                    <div class="main-bill-table statment">
                        <table>
                            <colgroup>
                                <col style="height: 30px;">
                                <col style="height: 30px;">
                                <col style="height: 30px;">
                                <col style="height: 30px;">
                                <col style="height: 30px;">
                                <col style="height: 30px;">
                                <col style="height: 30px;">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>DATE</th>
                                    <th>ORDER ID</th>
                                    <th>REFERENCE NO</th>
                                    <th>INQUIRY NAME</th>
                                    <th>COUNTRY</th>
                                    <th>PRICE ($)</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>01</td>
                                    <td>14-12-2023</td>
                                    <td>12121212</td>
                                    <td>MBL/MHK/CR/2023/0316</td>
                                    <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                    <td>Switzerland</td>
                                    <td>100</td>
                                </tr>
                                <tr>
                                    <td>02</td>
                                    <td>14-12-2023</td>
                                    <td>12121212</td>
                                    <td>MBL/MHK/CR/2023/0316</td>
                                    <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                    <td>Switzerland</td>
                                    <td>100</td>
                                </tr>
                                <tr>
                                    <td>03</td>
                                    <td>14-12-2023</td>
                                    <td>12121212</td>
                                    <td>MBL/MHK/CR/2023/0316</td>
                                    <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                    <td>Switzerland</td>
                                    <td>100</td>
                                </tr>
                                <tr>
                                    <td>04</td>
                                    <td>14-12-2023</td>
                                    <td>12121212</td>
                                    <td>MBL/MHK/CR/2023/0316</td>
                                    <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                    <td>Switzerland</td>
                                    <td>100</td>
                                </tr>
                                <tr>
                                    <td>05</td>
                                    <td>14-12-2023</td>
                                    <td>12121212</td>
                                    <td>MBL/MHK/CR/2023/0316</td>
                                    <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                    <td>Switzerland</td>
                                    <td>100</td>
                                </tr>
                                <tr>
                                    <td>06</td>
                                    <td>14-12-2023</td>
                                    <td>12121212</td>
                                    <td>MBL/MHK/CR/2023/0316</td>
                                    <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                    <td>Switzerland</td>
                                    <td>100</td>
                                </tr>
                                <tr>
                                    <td>07</td>
                                    <td>14-12-2023</td>
                                    <td>12121212</td>
                                    <td>MBL/MHK/CR/2023/0316</td>
                                    <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                    <td>Switzerland</td>
                                    <td>100</td>
                                </tr>
                                <tr>
                                    <td>08</td>
                                    <td>14-12-2023</td>
                                    <td>12121212</td>
                                    <td>MBL/MHK/CR/2023/0316</td>
                                    <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                    <td>Switzerland</td>
                                    <td>100</td>
                                </tr>
                                <tr>
                                    <td>09</td>
                                    <td>14-12-2023</td>
                                    <td>12121212</td>
                                    <td>MBL/MHK/CR/2023/0316</td>
                                    <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                    <td>Switzerland</td>
                                    <td>100</td>
                                </tr>



                            </tbody>
                        </table>

                        <div class="statment-total-amount">
                            <span>Branch Total</span>
                            <span>900</span>
                        </div>

                    </div>
                    <div class="statment-header">
                        <span>BRANCH:</span>
                        <span>Dhanmandi Mohila Branch</span>
                    </div>
                    <div class="main-bill-table statment">
                        <table>
                            <colgroup>
                                <col style="height: 30px;">
                                <col style="height: 30px;">
                                <col style="height: 30px;">
                                <col style="height: 30px;">
                                <col style="height: 30px;">
                                <col style="height: 30px;">
                                <col style="height: 30px;">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>DATE</th>
                                    <th>ORDER ID</th>
                                    <th>REFERENCE NO</th>
                                    <th>INQUIRY NAME</th>
                                    <th>COUNTRY</th>
                                    <th>PRICE ($)</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>01</td>
                                    <td>14-12-2023</td>
                                    <td>12121212</td>
                                    <td>MBL/MHK/CR/2023/0316</td>
                                    <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                    <td>Switzerland</td>
                                    <td>100</td>
                                </tr>
                                <tr>
                                    <td>02</td>
                                    <td>14-12-2023</td>
                                    <td>12121212</td>
                                    <td>MBL/MHK/CR/2023/0316</td>
                                    <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                    <td>Switzerland</td>
                                    <td>100</td>
                                </tr>

                            </tbody>
                        </table>


                    </div>


                    <div class="footer-area">
                        <div class="invoice-header">
                            <hr>
                        </div>

                        <div class="company-details">
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                                <div class="contact-way">
                                    <p>+880 964 990 9990</p>
                                    <p>+880 132 973 4171</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fa-regular fa-envelope"></i>
                                </div>
                                <div class="contact-way">
                                    <p>support@credilit.com</p>
                                    <p>www.credilit.com</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div class="contact-way">
                                    <p>3rd Floor, 37/A, Central Road</p>
                                    <p>Dhaka 1205 Bangladesh
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- page three start -->
    <div class="full-wrapper">
        <div class="container-fluid p-0">
            <div class="invoice-main-area">
                <!-- header area start -->
                <div class="header">
                    <div class="header-left-side">
                        <div class="logo">
                            <a href="#">
                                <img src="assets/images/logo-dark.svg" alt="logo">
                            </a>
                        </div>
                    </div>
                    <div class="header-right-side">
                        <h1>CREDILIT LIMITED</h1>
                        <h2>Invoice No: 2023128645</h2>
                        <h2>Invoice Date: 07 December 2023 </h2>
                        <h2>Billing Period: November 2023 </h2>
                    </div>
                </div>
                <div class="main-bill-table statment">
                    <table>
                        <colgroup>
                            <col style="height: 30px;">
                            <col style="height: 30px;">
                            <col style="height: 30px;">
                            <col style="height: 30px;">
                            <col style="height: 30px;">
                            <col style="height: 30px;">
                            <col style="height: 30px;">
                        </colgroup>

                        <tbody>


                            <tr>
                                <td>03</td>
                                <td>14-12-2023</td>
                                <td>12121212</td>
                                <td>MBL/MHK/CR/2023/0316</td>
                                <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                <td>Switzerland</td>
                                <td>100</td>
                            </tr>
                            <tr>
                                <td>04</td>
                                <td>14-12-2023</td>
                                <td>12121212</td>
                                <td>MBL/MHK/CR/2023/0316</td>
                                <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                <td>Switzerland</td>
                                <td>100</td>
                            </tr>
                            <tr>
                                <td>05</td>
                                <td>14-12-2023</td>
                                <td>12121212</td>
                                <td>MBL/MHK/CR/2023/0316</td>
                                <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                <td>Switzerland</td>
                                <td>100</td>
                            </tr>
                            <tr>
                                <td>06</td>
                                <td>14-12-2023</td>
                                <td>12121212</td>
                                <td>MBL/MHK/CR/2023/0316</td>
                                <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                <td>Switzerland</td>
                                <td>100</td>
                            </tr>
                            <tr>
                                <td>07</td>
                                <td>14-12-2023</td>
                                <td>12121212</td>
                                <td>MBL/MHK/CR/2023/0316</td>
                                <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                <td>Switzerland</td>
                                <td>100</td>
                            </tr>
                            <tr>
                                <td>08</td>
                                <td>14-12-2023</td>
                                <td>12121212</td>
                                <td>MBL/MHK/CR/2023/0316</td>
                                <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                <td>Switzerland</td>
                                <td>100</td>
                            </tr>
                            <tr>
                                <td>09</td>
                                <td>14-12-2023</td>
                                <td>12121212</td>
                                <td>MBL/MHK/CR/2023/0316</td>
                                <td>WOEN HAUR TEXTILE PARTS INSTRUMENTS CO. LTD,</td>
                                <td>Switzerland</td>
                                <td>100</td>
                            </tr>



                        </tbody>
                    </table>
                </div>


                <div class="statment-total-amount">
                    <span>Branch Total$</span>
                    <span>900</span>
                </div>
                <div class="statment-total-amount">
                    <span>Grand Total$</span>
                    <span>2700</span>
                </div>



                <div class="footer-area">
                    <div class="invoice-header">
                        <hr>
                    </div>

                    <div class="company-details">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div class="contact-way">
                                <p>+880 964 990 9990</p>
                                <p>+880 132 973 4171</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fa-regular fa-envelope"></i>
                            </div>
                            <div class="contact-way">
                                <p>support@credilit.com</p>
                                <p>www.credilit.com</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="contact-way">
                                <p>3rd Floor, 37/A, Central Road</p>
                                <p>Dhaka 1205 Bangladesh
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- fontawasome js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"
        integrity="sha512-GWzVrcGlo0TxTRvz9ttioyYJ+Wwk9Ck0G81D+eO63BaqHaJ3YZX9wuqjwgfcV/MrB2PhaVX9DkYVhbFpStnqpQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </body>

</html>