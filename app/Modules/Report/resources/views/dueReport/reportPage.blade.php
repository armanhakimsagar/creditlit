<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Credit Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif;
        }

        body {
            background: #f8f9fa;
            padding: 20px;
        }

        /* **********
        Universal code
        ************ */

        h1 {
            font-family: Arial, Helvetica, sans-serif;
            color: #002060;
            font-size: 28px;
        }

        h2 {
            font-family: Arial, Helvetica, sans-serif;
            color: #002060;
            font-size: 24px;
        }

        h3 {
            font-family: Arial, Helvetica, sans-serif;
            color: #002060;
            font-size: 20px;
        }

        p {
            font-size: 14px;
            color: #002776;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 500;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td {
            padding: 10px;
            border: none;
            color: #000;
            vertical-align: top;
            font-size: 14px;
        }

        .report-container {
            background: #fff;
            max-width: 900px;
            margin: auto;
            border: 1px solid #ccc;
            padding: 20px 40px;
            border-radius: 6px;
        }

        /* *****************
        Header code
        ***************** */

        header {
            border-bottom: 2px solid #004080;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .header-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .header-container-item {
            text-align: right;
        }

        .header-container-item p {
            font-size: 16px;
        }

        .header-container-item p span {
            font-weight: 500;
            color: #000;
            font-size: 13px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logo img {
            width: 250px;
        }

        .company-info-container {
            display: grid;
            grid-template-columns: 3fr 2fr;
        }

        .company-info,
        .executive-summary,
        .credit-info {
            margin-bottom: 30px;
        }

        .heading-item {
            border-bottom: 3px solid #004080;
            margin-bottom: 20px;
            font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS", sans-serif;
            font-size: 28px;
        }

        .heading-item-subheading {
            border-bottom: 2px solid #454545;
            margin-bottom: 20px;
            font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS", sans-serif;
            font-size: 16px;
        }

        .summary-boxes-container {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            text-align: center;
            margin-top: 15px;
        }

        .summary-boxes-item {
            background: #ffffff;
            border: 1px solid #cce;
            border-radius: 6px;
            padding: 30px 60px;
        }

        .credit-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .credit-score .score-circle {
            width: 80px;
            height: 80px;
            background: #004080;
            color: white;
            font-size: 26px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
        }

        /* ********************************
            company-inquiry-details
        *********************************/

        .company-inquiry-details table tr th,
        .company-inquiry-details table tr td,
        .company-details table tr td,
        .registration-details table tr td,
        .share-capitals table tr td,
        .shareholders table tr td,
        .board-of-directors table tr td,
        .management-team table tr td,
        .financial-outlook table tr td,
        .economic-outlook table tr td,
        .corporate-affiliation table tr td,
        .property-asset table tr td,
        .company-outlook table tr td,
        .business-operation table tr td,
        .industry-information table tr td,
        .banking-information table tr td,
        .branch-information table tr td,
        .recruitments table tr td,
        .tender-information table tr td,
        .tax-rating table tr td,
        .payment-information table tr td,
        .subsidiary-company table tr td,
        .import-export-record table tr td,
        .legal-filings table tr td,
        .information-partners table tr td,
        .investigation-notes table tr td,
        .investigation-notes table tr td,
        .appendix table tr td {
            border: 1px solid #c8d9fd;
        }

        .board-of-directors p {
            color: #000;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 100;
        }

        .company-inquiry-details thead,
        .financial-outlook thead {
            background-color: #c8d9fd;
            color: #052b79;
            text-align: left;
        }

        .company-inquiry-details h4 {
            color: #052b79;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
            font-weight: 700;
        }

        .financial-outlook thead tr th {
            padding: 5px;
            width: 25%;
        }

        .financial-outlook table tr td {
            width: 25%;
        }

        .financial-outlook .no-border tr td {
            border: 0px solid #ffffff;
            font-weight: 700;
            padding: 2px;
        }

        footer {
            text-align: center;
            font-size: 14px;
            color: #777;
            border-top: 1px solid #ccc;
            padding-top: 15px;
        }

        /* ****************
        Economic Outlook
        *******************/

        .economic-outlook p {
            margin-bottom: 15px;
            color: #000;
            line-height: 20px;
        }

        .company-symbol-container {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
        }

        .company-symbol-container img {
            width: 200px;
        }

        .business-operation ul {
            list-style: none;
            padding-left: 20px;
        }

        .business-operation li {
            margin: 10px 0;
            padding-left: 10px;
            position: relative;
        }

        .business-operation li::before {
            content: "✔";
            position: absolute;
            left: -20px;
            color: #27ae60;
        }

        .business-operation p {
            margin: 3px 0 0 0;
            color: #555;
        }

        .import-export-record table tr th,
        .legal-filings table tr th {
            border: 1px solid #c8d9fd;
            font-size: 14px;
        }

        .background-border thead {
            background-color: #c8d9fd;
            color: #052b79;
            text-align: center;
        }

        .information-partners img {
            width: 100px;
        }

        .desclaimer p {
            margin-bottom: 20px;
            color: #000;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

        .desclaimer h4 {
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="report-container">
        <header>
            <div class="header-container">
                <div class="header-container-item">
                    <div class="logo">
                        <img src="https://credilit.com/wp-content/uploads/logo-dark.svg" alt="Credilit Logo">
                    </div>
                </div>
                <div class="header-container-item">
                    <div class="report-info">
                        <p>BUSINESS CREDIT RISK REPORT OF</p>
                        <h3>{{ $englishName }}</h3>
                        <p><span> Request date: {{ $completionDate }} </span></p>
                        <p><span> Delivery date: {{ $completionDate }} </span></p>
                        <p><span> Order no: {{ $orderId }} </span></p>
                    </div>
                </div>
            </div>

        </header>

        <section class="company-info">
            <h1>{{ $englishName }}</h1>
            <div class="company-info-container">
                <div class="company-info-item">
                    <table>
                        <tr>
                            <td width="160px">Registered Address</td>
                            <td>{{ $addressDetail }}
                            </td>
                        </tr>
                        <tr>
                            <td>City</td>
                            <td>N/A</td>
                        </tr>
                        <tr>
                            <td>Province</td>
                            <td>N/A</td>
                        </tr>
                        <tr>
                            <td>Postal Code</td>
                            <td>N/A</td>
                        </tr>
                        <tr>
                            <td>Country</td>
                            <td>China</td>
                        </tr>
                    </table>

                </div>
                <div class="company-info-item">
                    <table>
                        <tr>
                            <td>Mobile</td>
                            <td>N/A
                            </td>
                        </tr>
                        <tr>
                            <td>Telephone</td>
                            <td> {{ $telephone }} </td>
                        </tr>
                        <tr>
                            <td>Fax</td>
                            <td> N/A </td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td> {{ $email }} </td>
                        </tr>
                        <tr>
                            <td>Website</td>
                            <td>N/A</td>
                        </tr>
                    </table>
                </div>
            </div>
        </section>
        <br>


        <section class="company-summary">
            <h2 class="heading-item">Company Summary</h2>
            <div class="company-summary-item">
                <table>
                    <tr>
                        <td width="200px">Date Registered</td>
                        <td>{{ $timeOfEstablishment }}
                        </td>
                    </tr>
                    <tr>
                        <td>Registration Number </td>
                        <td>{{ $registrationNumber }}</td>
                    </tr>
                    <tr>
                        <td>Legal Form</td>
                        <td>{{ $theNatureOfTheEnterprise }}</td>
                    </tr>
                    <tr>
                        <td>Company Status</td>
                        <td>{{ $enterpriseState }}</td>
                    </tr>
                    <tr>
                        <td>Nature Of Business</td>
                        <td>{{ $legalScopeOfOperation }}</td>
                    </tr>
                </table>
            </div>
        </section>
        <br>

        <section class="executive-summary">
            <h2 class="heading-item">Executive Summary</h2>
            <div class="summary-boxes-container">
                <div class="summary-boxes-item">
                    <p>Turnover</p>
                    <h3>{{ $grossRevenue }}</h3>
                </div>
                <div class="summary-boxes-item">
                    <p>Total Asset</p>
                    <h3>{{ $totalAssets }}</h3>
                </div>
                <div class="summary-boxes-item">
                    <p>Shareholders' Equity</p>
                    <h3>{{ $shareholderEquity }}</h3>
                </div>
                <div class="summary-boxes-item">
                    <p>Authorized Capital</p>
                    <h3>{{ $registeredCapital }}</h3>
                </div>
                <div class="summary-boxes-item">
                    <p>Paid-Up Capital</p>
                    <h3>{{ $collectionOfCapital }}</h3>
                </div>
                <div class="summary-boxes-item">
                    <p>Number of Employees</p>
                    <h3>N/A</h3>
                </div>
            </div>
        </section>
        <br>

        <section class="credit-info">
            <h2 class="heading-item">Credit Information</h2>
            <div class="credit-details">
                <div class="credit-score">
                    <h3>CREDIT SCORE</h3>
                    <!-- <div class="score-circle">75</div> -->

                    <div id="container-speed" style="width: 300px; height: 300px; margin: 0 auto;"></div>
                </div>
                <div class="key-factors">
                    <p><strong>Rating:</strong> {{ $creditRating }}</p>
                    <p><strong>Obtain Score:</strong> {{ $creditScore }}</p>
                    <p><strong>Payment Trend:</strong> N/A</p>
                    <p><strong>Risk Level:</strong> N/A</p>
                    <p><strong>Financial Condition:</strong> N/A</p>
                    <p><strong>Credit Limit:</strong> {{ $basicCreditLine }}</p>
                </div>
            </div>
        </section>
        <br>

        <section class="company-inquiry-details">
            <h2 class="heading-item">Inquiry Details</h2>
            <div class="company-inquiry-details-item">
                <table>

                    <thead>
                        <tr>
                            <td colspan="4">
                                <h4>REFERENCES</h4>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Customer Name</td>
                            <td>MUTUAL TRUST BANK PLC</td>
                            <td>Delivery Date</td>
                            <td>December 24, 2024</td>
                        </tr>
                        <tr>
                            <td>Order Number</td>
                            <td>24122439</td>
                            <td>Customer Reference No</td>
                            <td>-</td>
                        </tr>
                    </tbody>

                </table>

                <br>

                <table>

                    <thead>
                        <tr>
                            <td colspan="2">
                                <h4>REFERENCES</h4>
                            </td>
                            <td>
                                <h4>REMARKS</h4>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Name</td>
                            <td>SEYAS ELECTRONICS CO. LTD.</td>
                            <td>Same As Verified Name</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>Address: No. 87 , Dongfu 4th Road, Dongfeng,
                                Zhongshan, Guangdong, China 528425</td>
                            <td>Incomplete Given Address</td>
                        </tr>
                        <tr>
                            <td>Country</td>
                            <td>China</td>
                            <td>Same As Verified Country Name</td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </section>
        <br>


        <section class="company-details">
            <h2 class="heading-item">Company Details</h2>
            <div class="company-inquiry-details-item">
                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Name</td>
                            <td>SEYAS ELECTRONICS CO., LTD.
                            </td>
                        </tr>
                        <tr>
                            <td>Chinees Name</td>
                            <td>广东西雅斯智能科技有限公司</td>
                        </tr>
                        <tr>
                            <td>Former Name</td>
                            <td>STABA ELECTRIC CO., LTD.</td>
                        </tr>
                        <tr>
                            <td>Registered Address</td>
                            <td>3, Zhi, 5th and 6th Floor, No.87, Dongfu No 4 Road
                                Jichang Village, Dongfeng Town/td>
                        </tr>
                        <tr>
                            <td>City</td>
                            <td>Zhongshan</td>
                        </tr>
                        <tr>
                            <td>Province</td>
                            <td>Guangdong</td>
                        </tr>
                        <tr>
                            <td>Postal Code</td>
                            <td>528425</td>
                        </tr>
                        <tr>
                            <td>Country</td>
                            <td>China</td>
                        </tr>
                        <tr>
                            <td>Mobile</td>
                            <td>+86 18718098845</td>
                        </tr>
                        <tr>
                            <td>Telephone</td>
                            <td>
                                <p>+86 076022579863</p>
                                <p>+86 076022579879</p>
                            </td>
                        </tr>
                        <tr>
                            <td>Fax</td>
                            <td>+86 076023752900
                            </td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>
                                <p>info@staba.hk</p>
                                <p>3360565161@qq.com</p>
                            </td>
                        </tr>
                        <tr>
                            <td>Website</td>
                            <td>www.stabapower.com</td>
                        </tr>
                        <tr>
                            <td>Key Person </td>
                            <td>Deng Huizhen (Supervisor)</td>
                        </tr>
                        <tr>
                            <td>Business Sector</td>
                            <td>General projects: automatic voltage stabilizer, uninterrupted power supply, industrial
                                voltage
                                regulator, automatic voltage converter, automatic voltage safeguard, rechargeable fan,
                                technical
                                services, technology development, technical consultation, technology exchange,
                                technology
                                transfer, technology promotion; transformer, rectifier and inductor manufacturing; smart
                                home
                                consumer device manufacturing; manufacturing of lighting fixtures; manufacturing of
                                distribution
                                switch control equipment; manufacturing of power transmission, distribution and control
                                equipment; hardware product manufacturing; electronic component manufacturing; electric
                                machine manufacturing; fans and fan manufacturing; manufacturing of plastic products;
                                sales of
                                electrical machinery and equipment; sales of smart home consumer devices; sales of
                                household
                                appliances; sales of lighting fixtures; sales of distribution switch control equipment;
                                sales of power
                                electronic components; sales of fans and fans; mold sales; sales of plastic products;
                                research and
                                development of household appliances; research and development of distribution switch
                                control
                                equipment; research and development of motors and their control systems; hardware
                                product
                                research and development; software development. (except for projects that require
                                approval
                                according to law, business activities can be carried out independently according to law
                                with a
                                business license) licensed projects: import and export of goods; technology import and
                                export.
                                (projects that require approval according to law can only carry out business activities
                                after being
                                approved by relevant departments. Specific business projects are subject to the approval
                                of
                                relevant departments, documents or licenses)</td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </section>
        <br>


        <section class="registration-details">
            <h2 class="heading-item">Registration Details</h2>
            <div class="company-inquiry-details-item">
                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Legal Form</td>
                            <td>Limited Liabilities Company</td>
                        </tr>
                        <tr>
                            <td>Registration Date</td>
                            <td>08-03-2016
                            </td>
                        </tr>
                        <tr>
                            <td>Registration No</td>
                            <td>442000001314138</td>
                        </tr>
                        <tr>
                            <td>Issuing Authority</td>
                            <td>Market Supervision Administration - Zhongshan City</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>Active</td>
                        </tr>


                    </tbody>

                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Legal Form</td>
                            <td>Limited Liabilities Company</td>
                        </tr>
                        <tr>
                            <td>Registration Date</td>
                            <td>08-03-2016
                            </td>
                        </tr>
                        <tr>
                            <td>Registration No</td>
                            <td>91442000MA4UMD9049</td>
                        </tr>
                        <tr>
                            <td>Issuing Authority</td>
                            <td>Market Supervision Administration - Zhongshan City</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>Active</td>
                        </tr>


                    </tbody>

                </table>
            </div>
        </section>
        <br>

        <section class="registration-details">
            <h2 class="heading-item">Registration Changes</h2>
            <div class="company-inquiry-details-item">
                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Date</td>
                            <td>Limited Liabilities Company</td>
                        </tr>
                        <tr>
                            <td>Item Changed </td>
                            <td>0Business scope
                            </td>
                        </tr>


                    </tbody>

                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Date</td>
                            <td>Limited Liabilities Company</td>
                        </tr>
                        <tr>
                            <td>Item Changed </td>
                            <td>0Business scope
                            </td>
                        </tr>


                    </tbody>

                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Date</td>
                            <td>Limited Liabilities Company</td>
                        </tr>
                        <tr>
                            <td>Item Changed </td>
                            <td>0Business scope
                            </td>
                        </tr>


                    </tbody>

                </table>


            </div>
        </section>
        <br>


        <section class="share-capitals">
            <h2 class="heading-item">Share Capitals</h2>
            <div class="share-capitals-item">

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Authorized Capital</td>
                            <td>CNY 10,000,000</td>
                        </tr>
                        <tr>
                            <td>Paid-Up Capital</td>
                            <td>CNY 10,000,000
                            </td>
                        </tr>


                    </tbody>

                </table>

            </div>
        </section>
        <br>


        <section class="shareholders">
            <h2 class="heading-item">Shareholders</h2>
            <div class="share-capitals-item">

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Name</td>
                            <td>Deng Huizhen</td>
                        </tr>
                        <tr>
                            <td>% of Shares</td>
                            <td>50.00 %</td>
                        </tr>
                        <tr>
                            <td>Capital Contributed</td>
                            <td>CNY 5,000,000</td>
                        </tr>
                        <tr>
                            <td>Investment Way</td>
                            <td>Capital</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>
                                <address>
                                    3, Zhi, 5th and 6th Floor, No.87, Dongfu No 4 Road
                                    Jichang Village, Dongfeng Town
                                    Zhongshan 528425
                                    Guangdong
                                    China
                                </address>
                            </td>
                        </tr>


                    </tbody>

                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Name</td>
                            <td>Deng Yuhua</td>
                        </tr>
                        <tr>
                            <td>% of Shares</td>
                            <td>50.00 %</td>
                        </tr>
                        <tr>
                            <td>Capital Contributed</td>
                            <td>CNY 5,000,000</td>
                        </tr>
                        <tr>
                            <td>Investment Way</td>
                            <td>Capital</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>
                                <address>
                                    3, Zhi, 5th and 6th Floor, No.87, Dongfu No 4 Road
                                    Jichang Village, Dongfeng Town
                                    Zhongshan 528425
                                    Guangdong
                                    China
                                </address>
                            </td>
                        </tr>


                    </tbody>

                </table>

            </div>
        </section>
        <br>


        <section class="board-of-directors">
            <h2 class="heading-item">Board of Directors
            </h2>
            <div class="share-capitals-item">

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Name</td>
                            <td>Deng Yuhua</td>
                        </tr>
                        <tr>
                            <td>Chinese Name</td>
                            <td>邓宇华</td>
                        </tr>
                        <tr>
                            <td>Board Title</td>
                            <td>Managing Director and General Manager</td>
                        </tr>
                        <tr>
                            <td>ID Number</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>Nationality</td>
                            <td>Chinese</td>
                        </tr>
                        <tr>
                            <td>Gender</td>
                            <td>Male</td>
                        </tr>
                        <tr>
                            <td>Other Directorship(s)</td>
                            <td>
                                <p>Guangdong Su Po Motor Science And Technology Co., LTD.</p>
                                <p>Staba Electric Co., Ltd.</p>
                                <p>Zhongshan Heng Ji Assets Investment Management Co., LTD.</p>
                                <p>Luo Li Software (Zhongshan) Co., LTD.</p>
                                <p>Zhongshan Shenxun Electronic Control Technology Co., Ltd.</p>
                                <p>Zhongshan Dong Feng Zhen Qu Ni De Café</p>
                                <p>Mai Ge Er Supply Chain Management (Shenzhen) Co., LTD.</p>
                                <p>Hei Tian Shi Business Management (Zhongshan) Co., LTD.</p>
                                <p>Zhongshan Hong Huo Trading Co., Ltd</p>

                            </td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>
                                3, Zhi, 5th and 6th Floor, No.87, Dongfu No 4 Road,<br>
                                Jichang Village, Dongfeng Town,<br>
                                Zhongshan 528425, Guangdong
                            </td>
                        </tr>
                    </tbody>
                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Name</td>
                            <td>Huang Tianying</td>
                        </tr>
                        <tr>
                            <td>Chinese Name</td>
                            <td>邓惠珍</td>
                        </tr>
                        <tr>
                            <td>Board Title</td>
                            <td>Director</td>
                        </tr>
                        <tr>
                            <td>ID Number</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>Nationality</td>
                            <td>Chinese</td>
                        </tr>
                        <tr>
                            <td>Gender</td>
                            <td>Male</td>
                        </tr>
                        <tr>
                            <td>Other Directorship(s)</td>
                            <td>
                                N/A
                            </td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>
                                3, Zhi, 5th and 6th Floor, No.87, Dongfu No 4 Road,<br>
                                Jichang Village, Dongfeng Town,<br>
                                Zhongshan 528425, Guangdong
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </section>
        <br>


        <section class="management-team">
            <h2 class="heading-item">Management Team
            </h2>
            <div class="share-capitals-item">

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Name</td>
                            <td>Deng Yuhua</td>
                        </tr>
                        <tr>
                            <td>Job Title</td>
                            <td>Managing Director and General Manager</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>
                                3, Zhi, 5th and 6th Floor, No.87, Dongfu No 4 Road<br>
                                Jichang Village, Dongfeng Town<br>
                                Zhongshan 528425<br>
                                Guangdong
                            </td>
                        </tr>
                    </tbody>
                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Name</td>
                            <td>Kenny Wang</td>
                        </tr>
                        <tr>
                            <td>Job Title</td>
                            <td>Sales Manager</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>
                                3, Zhi, 5th and 6th Floor, No.87, Dongfu No 4 Road<br>
                                Jichang Village, Dongfeng Town<br>
                                Zhongshan 528425<br>
                                Guangdong
                            </td>
                        </tr>
                    </tbody>
                </table>


            </div>
        </section>
        <br>


        <section class="financial-outlook">
            <h2 class="heading-item">Financial Outlook</h2>
            <h4 class="heading-item-subheading">INCOME STATEMENTS</h4>
            <div class="financial-outlook-item">
                <table class="no-border">
                    <tbody>
                        <tr>
                            <td>Currency</td>
                            <td>Chinese Yuan (CNY)</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>

                </table>

                <table>
                    <thead>
                        <tr>
                            <th>Date of accounts </th>
                            <th>31-12-2021</th>
                            <th>31-12-2022</th>
                            <th>31-12-2023</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Consolidation</td>
                            <td>Non-Consolidated</td>
                            <td>Non-Consolidated</td>
                            <td>Non-Consolidated</td>
                        </tr>
                        <tr>
                            <td>Period</td>
                            <td>12 months</td>
                            <td>12 months</td>
                            <td>12 months</td>
                        </tr>
                    </tbody>

                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td>Main income</td>
                            <td>49,910,000</td>
                            <td>59,230,000</td>
                            <td>100,240,000</td>
                        </tr>
                        <tr>
                            <td>Cost of sales</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>Gross profit (loss)</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>Total Profit</td>
                            <td>2,420,000</td>
                            <td>1,740,000</td>
                            <td>4,120,000</td>
                        </tr>
                        <tr>
                            <td>Profit (loss) before tax</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>Income tax</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Net profit (loss)</td>
                            <td>2,420,000</td>
                            <td>1,740,000</td>
                            <td>4,120,000</td>
                        </tr>
                    </tbody>

                </table>

                <br><br>
                <h4 class="heading-item-subheading">BALANCE SHEET</h4>

                <table class="no-border">
                    <tbody>
                        <tr>
                            <td>Currency</td>
                            <td>Chinese Yuan (CNY)</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>

                </table>

                <table>
                    <thead>
                        <tr>
                            <th>Date of accounts </th>
                            <th>31-12-2021</th>
                            <th>31-12-2022</th>
                            <th>31-12-2023</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Consolidation</td>
                            <td>Non-Consolidated</td>
                            <td>Non-Consolidated</td>
                            <td>Non-Consolidated</td>
                        </tr>
                        <tr>
                            <td>Period</td>
                            <td>12 months</td>
                            <td>12 months</td>
                            <td>12 months</td>
                        </tr>
                    </tbody>

                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td>Net trade accounts receivable</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>Inventories</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>Total assets</td>
                            <td>37,680,000</td>
                            <td>56,710,000</td>
                            <td>34,390,000</td>
                        </tr>
                        <tr>
                            <td>Total liabilities</td>
                            <td>30,050,000</td>
                            <td>47,340,000</td>
                            <td>21,530,000</td>
                        </tr>
                        <tr>
                            <td>Equity</td>
                            <td>7,630,000</td>
                            <td>9,370,000</td>
                            <td>12,860,000</td>
                        </tr>
                        <tr>
                            <td>Total Liabilities and Owner's Equity</td>
                            <td>37,680,000</td>
                            <td>56,710,000</td>
                            <td>34,390,000</td>
                        </tr>
                    </tbody>

                </table>


                <br><br>
                <h4 class="heading-item-subheading">GROWTH RATE OF MAJOR FINANCIAL</h4>

                <table>
                    <thead>
                        <tr>
                            <th>Financial Year </th>
                            <th>31-12-2021</th>
                            <th>31-12-2022</th>
                            <th>31-12-2023</th>
                        </tr>
                    </thead>
                </table>

                <br>

                <table class="no-border">
                    <tbody>
                        <tr>
                            <td></td>
                            <td>Growth(%)</td>
                            <td>Growth(%)</td>
                            <td>Growth(%)</td>
                        </tr>
                    </tbody>

                </table>

                <table>
                    <tbody>
                        <tr>
                        <tr>
                            <td>Total Assets</td>
                            <td>-</td>
                            <td>12.99</td>
                            <td>5.93</td>
                        </tr>
                        <tr>
                            <td>Total Liabilities</td>
                            <td>-</td>
                            <td>12.34</td>
                            <td>5.49</td>
                        </tr>
                        <tr>
                            <td>Total Owner’s Equity</td>
                            <td>-</td>
                            <td>13.50</td>
                            <td>6.27</td>
                        </tr>
                        <tr>
                            <td>Operating Income</td>
                            <td>-</td>
                            <td>25.56</td>
                            <td>26.01</td>
                        </tr>
                        <tr>
                            <td>Total Profit</td>
                            <td>-</td>
                            <td>427.59</td>
                            <td>26.32</td>
                        </tr>
                        <tr>
                            <td>Income Tax</td>
                            <td>-</td>
                            <td>0.00</td>
                            <td>200.00</td>
                        </tr>
                        <tr>
                            <td>Net Profit</td>
                            <td>-</td>
                            <td>400.00</td>
                            <td>22.58</td>
                        </tr>
                    </tbody>

                </table>


                <br><br>
                <h4 class="heading-item-subheading">KEY RATIOS</h4>

                <table>
                    <thead>
                        <tr>
                            <th>Financial Year </th>
                            <th>31-12-2021</th>
                            <th>31-12-2022</th>
                            <th>31-12-2023</th>
                        </tr>
                    </thead>
                </table>

                <br>

                <table class="no-border">
                    <tbody>
                        <tr>
                            <td>Profitability ratio</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>

                </table>

                <table>
                    <tbody>
                        <tr>
                            <td>Return on Assets (%)</td>
                            <td>31.72</td>
                            <td>18.57</td>
                            <td>32.04</td>
                        </tr>
                        <tr>
                            <td>Net profit margin (%)</td>
                            <td>6.42</td>
                            <td>3.07</td>
                            <td>11.98</td>
                        </tr>
                        <tr>
                            <td>Net profit margin (%)</td>
                            <td>4.85</td>
                            <td>2.94</td>
                            <td>4.11</td>
                        </tr>
                    </tbody>

                </table>

                <br>

                <table class="no-border">
                    <tbody>
                        <tr>
                            <td>Operation Capacity</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>

                </table>

                <table>
                    <tbody>
                        <td>Turnover of total assets</td>
                        <td>1.32</td>
                        <td>1.04</td>
                        <td>2.91</td>
                    </tbody>

                </table>


                <br>

                <table class="no-border">
                    <tbody>
                        <tr>
                            <td>Solvency</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>

                </table>

                <table>
                    <tbody>
                        <td>Liabilities to assets ratio (%)</td>
                        <td>79.75</td>
                        <td>83.48 </td>
                        <td>62.61</td>
                    </tbody>

                </table>



                <br>

                <table class="no-border">
                    <tbody>
                        <tr>
                            <td>Development Capacity</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>

                </table>

                <table>
                    <tbody>
                        <tr>
                            <td>Yearly Growth of Operating Income (%)</td>
                            <td>-</td>
                            <td>69.24</td>
                            <td>18.67</td>
                        </tr>

                        <tr>
                            <td>Yearly growth of total assets (%)</td>
                            <td>-</td>
                            <td>-39.36</td>
                            <td>50.50</td>
                        </tr>

                    </tbody>

                </table>








                <br>
            </div>
        </section>
        <br>


        <section class="economic-outlook">
            <h2 class="heading-item">Economic Outlook</h2>
            <div class="share-capitals-item">

                <table class="economic-outlook">
                    <tbody>
                        <tr>
                            <td width="20%">Overview</td>
                            <td>
                                <p>China has been one of the world's fastest-growing economies for several decades, with
                                    a focus on
                                    export-led growth, infrastructure development, and urbanization. Some key points to
                                    consider about
                                    China's economic outlook include:
                                </p>
                                <p>
                                    Growth Rate: China's economic growth had been gradually slowing down from the rapid
                                    doubledigit
                                    figures seen in the early 2000s to a more sustainable pace of around 6-7% in the
                                    years leading up
                                    to 2021. The government has been aiming to shift from high-speed growth to
                                    higher-quality growth
                                    with an emphasis on innovation, consumption, and services.
                                </p>
                                <p>
                                    Structural Reforms: The Chinese government has been implementing structural reforms
                                    to address
                                    issues like overreliance on exports, environmental concerns, and income inequality.
                                    These reforms
                                    aimed to rebalance the economy towards domestic consumption and services, rather
                                    than solely
                                    relying on manufacturing and exports.
                                </p>
                                <p>
                                    Debt Concerns: China's rapid economic growth was accompanied by a buildup of debt in
                                    various
                                    sectors, including government, corporate, and household debt. Managing and
                                    deleveraging this
                                    debt while maintaining economic stability has been a challenge for the Chinese
                                    government.
                                </p>
                                <p>
                                    Trade Relations: China's trade relationships with other countries, particularly the
                                    United States,
                                    have been a significant factor affecting its economic outlook. Trade tensions and
                                    tariff disputes
                                    between China and the US, along with concerns about intellectual property theft and
                                    market access,
                                    have added uncertainty to China's economic future.
                                </p>
                                <p>
                                    Technology and Innovation: China has been investing heavily in technology and
                                    innovation to
                                    transition from being a manufacturing hub to a global technology leader. Initiatives
                                    like "Made in
                                    China 2025" have highlighted the country's ambition to become a dominant player in
                                    sectors like
                                    artificial intelligence, robotics, and advanced manufacturing. © Credilit Limited
                                    2024. All rights
                                    reserved Page 113 Demographic Challenges: China faces demographic challenges due to
                                    an aging
                                    population and a shrinking workforce. The one-child policy that was in place for
                                    decades has led to
                                    a skewed age distribution, which could impact the labor market, social services, and
                                    economic
                                    growth.
                                </p>
                                <p>
                                    Environmental Concerns: Rapid industrialization and urbanization have led to severe
                                    environmental
                                    issues in China, including air and water pollution. The government has been
                                    increasingly focused
                                    on environmental sustainability and transitioning to cleaner energy sources.

                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>GDP</td>
                            <td>
                                <p>
                                    The economy of the People's Republic of China is a socialist market economy, or a
                                    mixed economy,
                                    incorporating industrial policies and strategic five-year plans. China has the
                                    world's largest economy when measured in terms of purchasing power parity (USD 37
                                    trillion in 2024), and the
                                    world's second-largest economy as measured by nominal GDP in USD terms (USD18.3
                                    trillion in
                                    2024). In per capita terms, GDP ranks 70th out of 192 countries measured -
                                    qualifying China as a
                                    developing, "newly industrialized", or "upper-middle income" country.
                                </p>
                                <p>
                                    China is also the world's largest manufacturing industrial economy and exporter of
                                    goods. China
                                    accounts for 17% of the world's population but 35% of global manufacturing output,
                                    and its
                                    production exceeds that of the nine next largest manufacturers combined. The
                                    nation's economy is
                                    characterized by the development and use of advanced technology - on many measures,
                                    China
                                    leads the world in research output and successful patent applications, a robust
                                    education system
                                    that produces high school students which lead the world in objective test scores on
                                    international
                                    rankings, an advanced infrastructure - China has the world's longest, densest and
                                    most extensive
                                    high-speed rail network spanning 46,000 kilometers and connecting 550 cities, and a
                                    substantially
                                    cashless society where digital payments heavily predominate as the primary mode of
                                    transaction.
                                </p>
                                <p>
                                    China's economy is well-integrated into the rest of the world, and China has the
                                    world's largest
                                    foreign-exchange reserves worth $3.1 trillion. If the foreign assets of China's
                                    state-owned
                                    commercial banks are included, then the value of China's reserves rises to nearly $4
                                    trillion
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>Reserve</td>
                            <td>
                                <p>
                                    The foreign exchange reserves of China are the state of foreign exchange reserves
                                    held by the
                                    People's Republic of China, comprising cash, bank deposits, bonds, and other
                                    financial assets
                                    denominated in currencies other than China's national currency (the renminbi). As of
                                    October 2024,
                                    China's foreign exchange reserves totaled US$3.261 trillion, which is the highest
                                    foreign exchange
                                    reserves of any country.

                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <br>


        <section class="corporate-affiliation">
            <h2 class="heading-item">Corporate Affiliations
            </h2>
            <div class="share-capitals-item">

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Name in English</td>
                            <td>Managing Director and General Manager</td>
                        </tr>
                        <tr>
                            <td>Name in Chinese</td>
                            <td>
                                湖南灏全新能源有限责任公司
                            </td>
                        </tr>
                        <tr>
                            <td>Registration No</td>
                            <td>
                                91430900MACYYLMQ5C
                            </td>
                        </tr>
                        <tr>
                            <td>Legal Form</td>
                            <td>Limited Liabilities Company</td>

                        </tr>
                        <tr>
                            <td>Type of Affiliations </td>
                            <td>
                                Directorship (Managing Director,Manager);Investment(80.00%)
                            </td>
                        </tr>
                        <tr>
                            <td>License Status </td>
                            <td>
                                Operational
                            </td>
                        </tr>
                        <tr>
                            <td>Date of Registration </td>
                            <td>
                                2006-11-30
                            </td>
                        </tr>
                        <tr>
                            <td>Fast Rating</td>
                            <td>
                                D
                            </td>
                        </tr>
                    </tbody>
                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Name in English</td>
                            <td>Managing Director and General Manager</td>
                        </tr>
                        <tr>
                            <td>Name in Chinese</td>
                            <td>
                                湖南灏全新能源有限责任公司
                            </td>
                        </tr>
                        <tr>
                            <td>Registration No</td>
                            <td>
                                91430900MACYYLMQ5C
                            </td>
                        </tr>
                        <tr>
                            <td>Legal Form</td>
                            <td>Limited Liabilities Company</td>

                        </tr>
                        <tr>
                            <td>Type of Affiliations </td>
                            <td>
                                Directorship (Managing Director,Manager);Investment(80.00%)
                            </td>
                        </tr>
                        <tr>
                            <td>License Status </td>
                            <td>
                                Operational
                            </td>
                        </tr>
                        <tr>
                            <td>Date of Registration </td>
                            <td>
                                2006-11-30
                            </td>
                        </tr>
                        <tr>
                            <td>Fast Rating</td>
                            <td>
                                D
                            </td>
                        </tr>
                    </tbody>
                </table>


            </div>
        </section>
        <br>


        <section class="property-asset">
            <h2 class="heading-item">Property & Assets
            </h2>
            <div class="share-capitals-item">

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Remarks</td>
                            <td>The registered office of the subject is situated at the aforementioned address.</td>
                        </tr>

                    </tbody>
                </table>


            </div>
        </section>
        <br>


        <section class="company-outlook">
            <h2 class="heading-item">Company Outlook</h2>
            <div class="share-capitals-item">

                <table class="economic-outlook">
                    <tbody>
                        <tr>
                            <td width="20%">Overview</td>
                            <td>
                                <p>SEYAS ELECTRONICS CO., LTD. is a prominent Chinese company specializing in the
                                    manufacturing, sales, and development of electrical, electronic, and smart home
                                    products. The
                                    company integrates advanced technology and engineering capabilities to deliver
                                    high-quality
                                    solutions for both industrial and consumer markets.
                                </p>
                                <p>
                                    The company exports products worldwide, including Europe, the Americas, the Middle
                                    East, Africa,
                                    and Hong Kong. Their offerings also Power management systems, smart home technology,
                                    electrical components, and manufacturing services. maintaining strong relationships
                                    with
                                    international clients through quality, competitive pricing, and excellent services.
                                </p>

                            </td>
                        </tr>
                        <tr>
                            <td>Company Symbol</td>
                            <td>
                                <div class="company-symbol-container">
                                    <div class="company-symbol-item">
                                        <img src="image/company_symbol_1.png" alt="">
                                    </div>
                                    <div class="company-symbol-item">
                                        <img src="image/company_symbol_2.png" alt="">
                                    </div>
                                    <div class="company-symbol-item">
                                        <img src="image/company_symbol_3.png" alt="">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Reserve</td>
                            <td>
                                <p>
                                    The foreign exchange reserves of China are the state of foreign exchange reserves
                                    held by the
                                    People's Republic of China, comprising cash, bank deposits, bonds, and other
                                    financial assets
                                    denominated in currencies other than China's national currency (the renminbi). As of
                                    October 2024,
                                    China's foreign exchange reserves totaled US$3.261 trillion, which is the highest
                                    foreign exchange
                                    reserves of any country.

                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <br>

        <section class="business-operation">
            <h2 class="heading-item">Business Operations</h2>
            <div class="share-capitals-item">

                <table class="economic-outlook">
                    <tbody>
                        <tr>
                            <td width="20%">Products</td>
                            <td>
                                <p>SEYAS ELECTRONICS CO., LTD. offers a diverse range of products across various
                                    categories.
                                    Below is an overview of their offerings:
                                </p>
                                <h3>Products</h3>

                                <h4>Voltage and Power Equipment</h4>
                                <ul>
                                    <li>
                                        <strong>Automatic Voltage Stabilizers:</strong>
                                        <p>Ensures stable voltage supply for electronic devices.</p>
                                    </li>
                                    <li>
                                        <strong>Uninterrupted Power Supplies (UPS):</strong>
                                        <p>Provides backup power to protect against power outages.</p>
                                    </li>
                                    <li>
                                        <strong>Industrial Voltage Regulators:</strong>
                                        <p>Maintains stable voltage for industrial applications.</p>
                                    </li>
                                    <li>
                                        <strong>Automatic Voltage Converters:</strong>
                                        <p>Converts voltage for compatibility across different regions.</p>
                                    </li>
                                    <li>
                                        <strong>Automatic Voltage Safeguards:</strong>
                                        <p>Protects devices from voltage fluctuations.</p>
                                    </li>
                                </ul>

                                <h4>Smart Home Devices</h4>
                                <ul>
                                    <li>
                                        <strong>Smart Home Consumer Devices:</strong>
                                        <p>Includes advanced household appliances and home automation solutions.</p>
                                    </li>
                                </ul>

                                <h4>Fans and Ventilation</h4>
                                <ul>
                                    <li>
                                        <strong>Rechargeable Fans:</strong>
                                        <p>Portable and energy-efficient fans with rechargeable batteries.</p>
                                    </li>
                                    <li>
                                        <strong>Fans and Fan Components:</strong>
                                        <p>Designed for industrial and household use.</p>
                                    </li>
                                </ul>

                                <h4>Transformers and Electrical Components</h4>
                                <ul>
                                    <li>
                                        <strong>Transformers, Rectifiers, and Inductors:</strong>
                                        <p>Essential components for power management and distribution.</p>
                                    </li>
                                    <li>
                                        <strong>Electronic Components:</strong>
                                        <p>High-quality parts for electrical and electronic systems.</p>
                                    </li>
                                </ul>

                                <h4>Lighting Fixtures</h4>
                                <ul>
                                    <li>
                                        <strong>Lighting Fixtures:</strong>
                                        <p>Includes energy-efficient and decorative lighting solutions.</p>
                                    </li>
                                </ul>

                                <h4>Distribution Equipment</h4>
                                <ul>
                                    <li>
                                        <strong>Distribution Switch Control Equipment:</strong>
                                        <p>Supports efficient management of electrical distribution systems.</p>
                                    </li>
                                </ul>

                                <h4>Distribution Equipment</h4>
                                <ul>
                                    <li>
                                        <strong>Distribution Switch Control Equipment:</strong>
                                        <p>Supports efficient management of electrical distribution systems.</p>
                                    </li>
                                </ul>

                                <h4>Plastic and Mold Products:</h4>
                                <ul>
                                    <li>
                                        <strong>Plastic Products:</strong>
                                        <p>Includes durable, custom-designed plastic items for various uses.</p>
                                    </li>
                                    <li>
                                        <strong>Molds:</strong>
                                        <p>High-precision molds for industrial manufacturing</p>
                                    </li>
                                </ul>

                                <h4>Research and Development</h4>
                                <ul>
                                    <li>
                                        <strong>Distribution Switch Control Equipment:</strong>
                                        <p>Advanced R&D in household appliances, motors, control systems, hardware, and
                                            software solutions.</p>
                                    </li>
                                </ul>


                                <h4>Licensed Activities:</h4>
                                <ul>
                                    <li>
                                        <strong>Import and Export:</strong>
                                        <p>Trade of electrical goods, technology, and related products internationally.
                                        </p>
                                    </li>
                                    <li>
                                        <strong>Technology Import and Export::</strong>
                                        <p>Transfer and sale of advanced technology solutions</p>
                                    </li>
                                </ul>

                            </td>
                        </tr>

                    </tbody>
                </table>
                <br>

                <table>
                    <tbody>
                        <td width="20%">Main Market</td>
                        <td>Asia, Europe, United States </td>
                    </tbody>

                </table>
                <table>
                    <tbody>
                        <td width="20%">Patent</td>
                        <td>
                            <span>Patent for utility models: 0</span><br>
                            <span> Invention patent: 1 </span>
                        </td>
                    </tbody>

                </table>
                <table>
                    <tbody>
                        <td width="20%">Copyright</td>
                        <td>Total Records: 4</td>
                    </tbody>

                </table>
                <table>
                    <tbody>
                        <td width="20%">Authentication Information</td>
                        <td>Total Records 4</td>
                    </tbody>

                </table>
                <table>
                    <tbody>
                        <td width="20%">Import/Export Permit</td>
                        <td>Yes</td>
                    </tbody>

                </table>


                <br>
                <h4 class="heading-item-subheading">SALES INFORMATION</h4>

                <table>
                    <tbody>
                        <tr>
                            <td width="30%">Region</td>
                            <td>Domestic</td>
                        </tr>
                        <tr>
                            <td>Products</td>
                            <td>
                                -
                            </td>
                        </tr>
                        <tr>
                            <td>Customer Type</td>
                            <td>
                                Manufacturing enterprises,traders,etc.
                            </td>
                        </tr>
                        <tr>
                            <td>Numbers Of Customers</td>
                            <td>-</td>

                        </tr>
                        <tr>
                            <td>Sales Areas</td>
                            <td>
                                All over China
                            </td>
                        </tr>
                        <tr>
                            <td>Percentage</td>
                            <td>
                                -
                            </td>
                        </tr>

                    </tbody>
                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="30%">Region</td>
                            <td>International</td>
                        </tr>
                        <tr>
                            <td>Products</td>
                            <td>
                                Of aluminium, not alloyed;Collapsible tubular containers;etc.
                            </td>
                        </tr>
                        <tr>
                            <td>Customer Type</td>
                            <td>
                                Manufacturing enterprises,traders,etc.
                            </td>
                        </tr>
                        <tr>
                            <td>Numbers Of Customers</td>
                            <td>-</td>

                        </tr>
                        <tr>
                            <td>Sales Areas</td>
                            <td>
                                Vietnam;Türkiye;Indonesia;Thailand;Haiti;etc
                            </td>
                        </tr>
                        <tr>
                            <td>Percentage</td>
                            <td>
                                -
                            </td>
                        </tr>

                    </tbody>
                </table>

                <br>
                <h4 class="heading-item-subheading">PURCHASE INFORMATION</h4>

                <table>
                    <tbody>
                        <tr>
                            <td width="30%">Region</td>
                            <td>Domestic</td>
                        </tr>
                        <tr>
                            <td>Main Products Purchased </td>
                            <td>
                                -
                            </td>
                        </tr>
                        <tr>
                            <td>Numbers Of Suppliers</td>
                            <td>More than 5</td>

                        </tr>
                        <tr>
                            <td>Purchase Areas</td>
                            <td>
                                All over China
                            </td>
                        </tr>
                        <tr>
                            <td>Percentage</td>
                            <td>
                                -
                            </td>
                        </tr>

                    </tbody>
                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="30%">Region</td>
                            <td>International</td>
                        </tr>
                        <tr>
                            <td>Main Products Purchased </td>
                            <td>
                                Other pure polyvinychlorid, in primary forms;etc.
                            </td>
                        </tr>
                        <tr>
                            <td>Numbers Of Suppliers</td>
                            <td>More than 10</td>

                        </tr>
                        <tr>
                            <td>Purchase Areas</td>
                            <td>
                                Vietnam;Türkiye;Indonesia;Thailand;Haiti;etc
                            </td>
                        </tr>
                        <tr>
                            <td>Percentage</td>
                            <td>
                                -
                            </td>
                        </tr>

                    </tbody>
                </table>

                <br>
                <h4 class="heading-item-subheading">SUPPLIERS</h4>

                <table>
                    <tbody>
                        <tr>
                            <td width="30%">Name</td>
                            <td>ANHUI FENGHUI METAL CO.,LTD.</td>
                        </tr>
                        <tr>
                            <td>Country</td>
                            <td>China</td>
                        </tr>


                    </tbody>
                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="30%">Name</td>
                            <td>ANHUI FENGHUI METAL CO.,LTD.</td>
                        </tr>
                        <tr>
                            <td>Country</td>
                            <td>China</td>
                        </tr>


                    </tbody>
                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="30%">Name</td>
                            <td>ANHUI FENGHUI METAL CO.,LTD.</td>
                        </tr>
                        <tr>
                            <td>Country</td>
                            <td>China</td>
                        </tr>


                    </tbody>
                </table>


                <br>
                <h4 class="heading-item-subheading">RATING BY CUSTOMS</h4>

                <table>
                    <tbody>
                        <tr>
                            <td width="30%">Rating</td>
                            <td>Registered and Filed Enterprise</td>
                        </tr>
                        <tr>
                            <td>Custom ID</td>
                            <td>
                                4309962023
                            </td>
                        </tr>
                        <tr>
                            <td>Business Category</td>
                            <td>Shipper & Consignee</td>

                        </tr>
                        <tr>
                            <td>Validity Period of Customs Declaration</td>
                            <td>
                                Long term
                            </td>
                        </tr>
                        <tr>
                            <td>Date Obtained</td>
                            <td>
                                2021-01-20
                            </td>
                        </tr>

                    </tbody>
                </table>

                <br>



            </div>
        </section>
        <br>


        <section class="industry-information">
            <h2 class="heading-item">Industry Information
            </h2>
            <div class="share-capitals-item">

                <table>
                    <tbody>
                        <tr>
                            <td width="30%">Industry Code and Title</td>
                            <td>2669, PRODUCTION OF OTHER SPECIALTY CHEMICALS</td>
                        </tr>
                        <tr>
                            <td>Short Description</td>
                            <td>634068841019</td>
                        </tr>

                    </tbody>
                </table>


            </div>
        </section>
        <br>

        <section class="banking-information">
            <h2 class="heading-item">Banking Information
            </h2>
            <div class="share-capitals-item">

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Bank Name</td>
                            <td>Bank of China, Zhongshan Dong Feng SubSub-branch</td>
                        </tr>
                        <tr>
                            <td>Account Number</td>
                            <td>634068841019</td>
                        </tr>
                        <tr>
                            <td>Vat/Tax Status</td>
                            <td>Active</td>
                        </tr>

                    </tbody>
                </table>


            </div>
        </section>
        <br>

        <section class="branch-information">
            <h2 class="heading-item">Branch Office
            </h2>
            <div class="share-capitals-item">

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Name</td>
                            <td>Shanghai Branch Office</td>
                        </tr>
                        <tr>
                            <td>Type</td>
                            <td>Operating Office Address</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>
                                <address>
                                    Room No 729, 7th Floor, Yuzhou Intl Phase 1
                                    No.333 Jingang Road, Pudong New Area
                                    Shanghai
                                    China
                                </address>
                            </td>
                        </tr>

                    </tbody>
                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Name</td>
                            <td>Harbin Branch Office</td>
                        </tr>
                        <tr>
                            <td>Type</td>
                            <td>Operating Office Address</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>
                                <address>
                                    Room 502, 5th Floor, Pufa Plaza
                                    209 Changjiang Road
                                    Nangang District
                                    Harbin
                                    China
                                </address>
                            </td>
                        </tr>

                    </tbody>
                </table>


            </div>
        </section>
        <br>


        <section class="recruitments">
            <h2 class="heading-item">Recruitments
            </h2>
            <div class="share-capitals-item">

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Position</td>
                            <td>Boiler</td>
                        </tr>
                        <tr>
                            <td>Working Place</td>
                            <td>Heshan District</td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td>
                                2017-12-25
                            </td>
                        </tr>

                    </tbody>
                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Position</td>
                            <td>Boiler</td>
                        </tr>
                        <tr>
                            <td>Working Place</td>
                            <td>Heshan District</td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td>
                                2017-12-25
                            </td>
                        </tr>

                    </tbody>
                </table>

            </div>
        </section>
        <br>


        <section class="tender-information">
            <h2 class="heading-item">Tender Information
            </h2>
            <div class="share-capitals-item">

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">No</td>
                            <td>01</td>
                        </tr>
                        <tr>
                            <td>Title</td>
                            <td>
                                <a href="">Hunan haosen glue industry co., ltd. annual output of 5000 tons of
                                    wet curing
                                    reactive
                                    polyurethane hot melt adhesive project name and scale change publicity</a>
                            </td>
                        </tr>
                        <tr>
                            <td>Publishing Date</td>
                            <td>
                                2024-02-28
                            </td>
                        </tr>

                    </tbody>
                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">No</td>
                            <td>01</td>
                        </tr>
                        <tr>
                            <td>Title</td>
                            <td>
                                <a href="">Hunan haosen glue industry co., ltd. annual output of 5000 tons of
                                    wet curing
                                    reactive
                                    polyurethane hot melt adhesive project name and scale change publicity</a>
                            </td>
                        </tr>
                        <tr>
                            <td>Publishing Date</td>
                            <td>
                                2017-12-25
                            </td>
                        </tr>

                    </tbody>
                </table>

            </div>
        </section>
        <br>


        <section class="tax-rating">
            <h2 class="heading-item">Tax Rating
            </h2>
            <div class="share-capitals-item">

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Year</td>
                            <td>2023</td>
                        </tr>
                        <tr>
                            <td>Taxpayer ID </td>
                            <td>
                                914309005507151909
                            </td>
                        </tr>
                        <tr>
                            <td>Taxpayer Credit Rating </td>
                            <td>
                                A
                            </td>
                        </tr>

                    </tbody>
                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Year</td>
                            <td>2022</td>
                        </tr>
                        <tr>
                            <td>Taxpayer ID </td>
                            <td>
                                914309005507151909
                            </td>
                        </tr>
                        <tr>
                            <td>Taxpayer Credit Rating </td>
                            <td>
                                A
                            </td>
                        </tr>

                    </tbody>
                </table>

            </div>
        </section>
        <br>

        <section class="payment-information">
            <h2 class="heading-item">Payment Information
            </h2>
            <div class="share-capitals-item">
                <h4>PURCHASE TERMS</h4>
                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Local</td>
                            <td>Cash
                                Credits 14-30 days</td>
                        </tr>
                        <tr>
                            <td>Imports</td>
                            <td>
                                Credits 30 days net
                            </td>
                        </tr>

                    </tbody>
                </table>

                <br>

                <h4>SALES TERMS</h4>
                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Local</td>
                            <td>Cash Credits 14-30 days net</td>
                        </tr>
                        <tr>
                            <td>Exports</td>
                            <td>
                                Credits 30 days net
                            </td>
                        </tr>
                        <tr>
                            <td>Debt Collections /
                                Judgements</td>
                            <td>
                                No negative information was found.
                            </td>
                        </tr>

                    </tbody>
                </table>
                <br>


                <h4>PAYMENT EXPERIENCES</h4>
                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Payment behavior:</td>
                            <td>As trade references were not supplied, the Subject's payment track record history CANNOT
                                BE
                                ACCURATELY DETERMINED, but payments are believed to be PROMPT.</td>
                        </tr>

                    </tbody>
                </table>

            </div>
        </section>
        <br>


        <section class="subsidiary-company">
            <h2 class="heading-item">Subsidiary/Sister Concern/ Associated Company
            </h2>
            <div class="share-capitals-item">

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Name in English</td>
                            <td>Foshan Ledefeng Nonferrous Metal Products Co., Ltd.</td>
                        </tr>
                        <tr>
                            <td>Name in Chinese</td>
                            <td>
                                佛山市乐德丰有色金属制品有限公司
                            </td>
                        </tr>
                        <tr>
                            <td>Registration No</td>
                            <td>
                                91440607796230265P
                            </td>
                        </tr>
                        <tr>
                            <td>Type</td>
                            <td>Holding Company</td>

                        </tr>
                        <tr>
                            <td>License Status </td>
                            <td>
                                Operational
                            </td>
                        </tr>
                        <tr>
                            <td>Date of Registration </td>
                            <td>
                                2006-11-30
                            </td>
                        </tr>
                        <tr>
                            <td>Authorized Capital </td>
                            <td>
                                CNY 1,500,000.00
                            </td>
                        </tr>
                        <tr>
                            <td>% Ownership</td>
                            <td>
                                100.00
                            </td>
                        </tr>
                        <tr>
                            <td>Operating Income </td>
                            <td>
                                100-200M
                            </td>
                        </tr>
                    </tbody>
                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Name in English</td>
                            <td>Foshan Ledefeng Nonferrous Metal Products Co., Ltd.</td>
                        </tr>
                        <tr>
                            <td>Name in Chinese</td>
                            <td>
                                佛山市乐德丰有色金属制品有限公司
                            </td>
                        </tr>
                        <tr>
                            <td>Registration No</td>
                            <td>
                                91440607796230265P
                            </td>
                        </tr>
                        <tr>
                            <td>Type</td>
                            <td>Holding Company</td>

                        </tr>
                        <tr>
                            <td>License Status </td>
                            <td>
                                Operational
                            </td>
                        </tr>
                        <tr>
                            <td>Date of Registration </td>
                            <td>
                                2006-11-30
                            </td>
                        </tr>
                        <tr>
                            <td>Authorized Capital </td>
                            <td>
                                CNY 1,500,000.00
                            </td>
                        </tr>
                        <tr>
                            <td>% Ownership</td>
                            <td>
                                100.00
                            </td>
                        </tr>
                        <tr>
                            <td>Operating Income </td>
                            <td>
                                100-200M
                            </td>
                        </tr>
                    </tbody>
                </table>


            </div>
        </section>
        <br>


        <section class="subsidiary-company">
            <h2 class="heading-item">Trademarks
            </h2>
            <div class="share-capitals-item">

                <table>
                    <tbody>
                        <tr>
                            <td width="30%">Name</td>
                            <td>乐诗芬娜</td>
                        </tr>
                        <tr>
                            <td>Image</td>
                            <td>
                                <img src="image/demo_logo.png" alt="">
                            </td>
                        </tr>
                        <tr>
                            <td>Application No</td>
                            <td>
                                91440607796230265P
                            </td>
                        </tr>
                        <tr>
                            <td>International Classification No</td>
                            <td>7</td>

                        </tr>

                    </tbody>
                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="30%">Name</td>
                            <td>ROSIFINA</td>
                        </tr>
                        <tr>
                            <td>Image</td>
                            <td>
                                <img src="image/demo_logo1.png" alt="">
                            </td>
                        </tr>
                        <tr>
                            <td>Application No</td>
                            <td>
                                47188774
                            </td>
                        </tr>
                        <tr>
                            <td>International Classification No</td>
                            <td>7</td>

                        </tr>

                    </tbody>
                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="30%">Name</td>
                            <td>乐诗芬娜</td>
                        </tr>
                        <tr>
                            <td>Image</td>
                            <td>
                                <img src="image/demo_logo.png" alt="">
                            </td>
                        </tr>
                        <tr>
                            <td>Application No</td>
                            <td>
                                91440607796230265P
                            </td>
                        </tr>
                        <tr>
                            <td>International Classification No</td>
                            <td>7</td>

                        </tr>

                    </tbody>
                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="30%">Name</td>
                            <td>ROSIFINA</td>
                        </tr>
                        <tr>
                            <td>Image</td>
                            <td>
                                <img src="image/demo_logo1.png" alt="">
                            </td>
                        </tr>
                        <tr>
                            <td>Application No</td>
                            <td>
                                47188774
                            </td>
                        </tr>
                        <tr>
                            <td>International Classification No</td>
                            <td>7</td>

                        </tr>

                    </tbody>
                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="30%">Name</td>
                            <td>乐诗芬娜</td>
                        </tr>
                        <tr>
                            <td>Image</td>
                            <td>
                                <img src="image/demo_logo.png" alt="">
                            </td>
                        </tr>
                        <tr>
                            <td>Application No</td>
                            <td>
                                91440607796230265P
                            </td>
                        </tr>
                        <tr>
                            <td>International Classification No</td>
                            <td>7</td>

                        </tr>

                    </tbody>
                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="30%">Name</td>
                            <td>ROSIFINA</td>
                        </tr>
                        <tr>
                            <td>Image</td>
                            <td>
                                <img src="image/demo_logo1.png" alt="">
                            </td>
                        </tr>
                        <tr>
                            <td>Application No</td>
                            <td>
                                47188774
                            </td>
                        </tr>
                        <tr>
                            <td>International Classification No</td>
                            <td>7</td>

                        </tr>

                    </tbody>
                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="30%">Name</td>
                            <td>乐诗芬娜</td>
                        </tr>
                        <tr>
                            <td>Image</td>
                            <td>
                                <img src="image/demo_logo.png" alt="">
                            </td>
                        </tr>
                        <tr>
                            <td>Application No</td>
                            <td>
                                91440607796230265P
                            </td>
                        </tr>
                        <tr>
                            <td>International Classification No</td>
                            <td>7</td>

                        </tr>

                    </tbody>
                </table>

                <br>


            </div>
        </section>
        <br>



        <section class="import-export-record">
            <h2 class="heading-item">Import And Export Year Record</h2>
            <h4 class="heading-item-subheading">IMPORT</h4>
            <div class="financial-outlook-item">
                <h4>IMPORT BY REGION -TOP5</h4>

                <table class="background-border">
                    <thead>
                        <tr>
                            <th width="33%">2024 </th>
                            <th width="33%">2023 </th>
                            <th width="33%">2022 </th>
                        </tr>
                    </thead>

                </table>

                <table>
                    <thead>
                        <tr>
                            <th>COUNTRY</th>
                            <th>IMPORT VOLUME</th>
                            <th>COUNTRY</th>
                            <th>IMPORT VOLUME</th>
                            <th>COUNTRY</th>
                            <th>IMPORT VOLUME</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Germany</td>
                            <td>1,759 </td>
                            <td>Germany</td>
                            <td>1,759 </td>
                            <td>Germany</td>
                            <td>1,759 </td>
                        </tr>
                        <tr>
                            <td>Germany</td>
                            <td>1,759 </td>
                            <td>Germany</td>
                            <td>1,759 </td>
                            <td>Germany</td>
                            <td>1,759 </td>
                        </tr>
                        <tr>
                            <td>Germany</td>
                            <td>1,759 </td>
                            <td>Germany</td>
                            <td>1,759 </td>
                            <td>Germany</td>
                            <td>1,759 </td>
                        </tr>
                        <tr>
                            <td>Germany</td>
                            <td>1,759 </td>
                            <td>Germany</td>
                            <td>1,759 </td>
                            <td>Germany</td>
                            <td>1,759 </td>
                        </tr>
                        <tr>
                            <td>Germany</td>
                            <td>1,759 </td>
                            <td>Germany</td>
                            <td>1,759 </td>
                            <td>Germany</td>
                            <td>1,759 </td>
                        </tr>

                    </tbody>

                </table>

                <br>

                <h4>IMPORT BY PRODUCTS -TOP5</h4>
                <table class="background-border">
                    <thead>
                        <tr>
                            <th width="33%">2024 </th>
                            <th width="33%">2023 </th>
                            <th width="33%">2022 </th>
                        </tr>
                    </thead>

                </table>

                <table>
                    <thead>
                        <tr>
                            <th>PRODUCT NAME </th>
                            <th>IMPORT VOLUME</th>
                            <th>PRODUCT NAME</th>
                            <th>IMPORT VOLUME</th>
                            <th>PRODUCT NAME</th>
                            <th>IMPORT VOLUME</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                        </tr>
                        <tr>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                        </tr>
                        <tr>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                        </tr>
                        <tr>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                        </tr>
                        <tr>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                        </tr>

                    </tbody>

                </table>
                <br>

                <h4>IMPORT BY PRODUCTS -TOP5</h4>
                <h3>Every Graph will add dinamically</h3>



                <br><br>
                <h4 class="heading-item-subheading">EXPORT</h4>

                <h4>EXPORT BY REGION -TOP5</h4>

                <table class="background-border">
                    <thead>
                        <tr>
                            <th width="33%">2024 </th>
                            <th width="33%">2023 </th>
                            <th width="33%">2022 </th>
                        </tr>
                    </thead>

                </table>

                <table>
                    <thead>
                        <tr>
                            <th>COUNTRY</th>
                            <th>EXPORT VOLUME</th>
                            <th>COUNTRY</th>
                            <th>EXPORT VOLUME</th>
                            <th>COUNTRY</th>
                            <th>EXPORT VOLUME</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Germany</td>
                            <td>1,759 </td>
                            <td>Germany</td>
                            <td>1,759 </td>
                            <td>Germany</td>
                            <td>1,759 </td>
                        </tr>
                        <tr>
                            <td>Germany</td>
                            <td>1,759 </td>
                            <td>Germany</td>
                            <td>1,759 </td>
                            <td>Germany</td>
                            <td>1,759 </td>
                        </tr>
                        <tr>
                            <td>Germany</td>
                            <td>1,759 </td>
                            <td>Germany</td>
                            <td>1,759 </td>
                            <td>Germany</td>
                            <td>1,759 </td>
                        </tr>
                        <tr>
                            <td>Germany</td>
                            <td>1,759 </td>
                            <td>Germany</td>
                            <td>1,759 </td>
                            <td>Germany</td>
                            <td>1,759 </td>
                        </tr>
                        <tr>
                            <td>Germany</td>
                            <td>1,759 </td>
                            <td>Germany</td>
                            <td>1,759 </td>
                            <td>Germany</td>
                            <td>1,759 </td>
                        </tr>

                    </tbody>

                </table>

                <br>

                <h4>EXPORT BY PRODUCTS -TOP5</h4>
                <table class="background-border">
                    <thead>
                        <tr>
                            <th width="33%">2024 </th>
                            <th width="33%">2023 </th>
                            <th width="33%">2022 </th>
                        </tr>
                    </thead>

                </table>

                <table>
                    <thead>
                        <tr>
                            <th>PRODUCT NAME </th>
                            <th>EXPORT VOLUME</th>
                            <th>PRODUCT NAME</th>
                            <th>EXPORT VOLUME</th>
                            <th>PRODUCT NAME</th>
                            <th>EXPORT VOLUME</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                        </tr>
                        <tr>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                        </tr>
                        <tr>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                        </tr>
                        <tr>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                        </tr>
                        <tr>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                            <td>Other pure
                                polyvinychlorid, in
                                primary forms.</td>
                            <td>1,759 </td>
                        </tr>

                    </tbody>

                </table>
                <br>

                <h4>EXPORT BY PRODUCTS -TOP5</h4>
                <h3>Every Graph will add dinamically</h3>










                <br>
            </div>
        </section>
        <br>

        <section class="subsidiary-company">
            <h2 class="heading-item">Subsidiary/Sister Concern/ Associated Company
            </h2>
            <div class="share-capitals-item">

                <h3>Every Graph will add dinamically</h3>


            </div>
        </section>
        <br>


        <section class="tax-rating">
            <h2 class="heading-item">Pledge Of Equity
            </h2>
            <div class="share-capitals-item">

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">File No</td>
                            <td>430900201808230002</td>
                        </tr>
                        <tr>
                            <td>Plegde Amount </td>
                            <td>
                                CNY 8 million
                            </td>
                        </tr>
                        <tr>
                            <td>File Date </td>
                            <td>
                                2018-08-23
                            </td>
                        </tr>
                        <tr>
                            <td>Status </td>
                            <td>
                                Invalid
                            </td>
                        </tr>

                    </tbody>
                </table>

                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">File No</td>
                            <td>430900201808230002</td>
                        </tr>
                        <tr>
                            <td>Plegde Amount </td>
                            <td>
                                CNY 8 million
                            </td>
                        </tr>
                        <tr>
                            <td>File Date </td>
                            <td>
                                2018-08-23
                            </td>
                        </tr>
                        <tr>
                            <td>Status </td>
                            <td>
                                Invalid
                            </td>
                        </tr>

                    </tbody>
                </table>
                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">File No</td>
                            <td>430900201808230002</td>
                        </tr>
                        <tr>
                            <td>Plegde Amount </td>
                            <td>
                                CNY 8 million
                            </td>
                        </tr>
                        <tr>
                            <td>File Date </td>
                            <td>
                                2018-08-23
                            </td>
                        </tr>
                        <tr>
                            <td>Status </td>
                            <td>
                                Invalid
                            </td>
                        </tr>

                    </tbody>
                </table>
                <br>

                <h4>SUBSIDIARY'S EQUITY BEING PLEDGED</h4>
                <table>
                    <tbody>
                        <tr>
                            <td width="20%">File No</td>
                            <td>430900201808230002</td>
                        </tr>
                        <tr>
                            <td>Plegde Amount </td>
                            <td>
                                CNY 8 million
                            </td>
                        </tr>
                        <tr>
                            <td>File Date </td>
                            <td>
                                2018-08-23
                            </td>
                        </tr>
                        <tr>
                            <td>Status </td>
                            <td>
                                Invalid
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </section>
        <br>

        <section class="legal-filings">
            <h2 class="heading-item">Legal Filings / Sanctions
            </h2>
            <div class="share-capitals-item">
                <h4>LEGAL FILINGS</h4>
                <table>
                    <tbody>
                        <tr>
                            <td width="30%">Bankruptcy filings</td>
                            <td>None Found.</td>
                        </tr>
                        <tr>
                            <td rowspan="14">Court judgements </td>
                            <td>Name</td>
                            <td>SEYAS ELECTRONICS CO., LTD.</td>
                        </tr>
                        <tr>
                            <td>File No.</td>
                            <td>(2017) Yue 2072 Min Chu 10446</td>
                        </tr>
                        <tr>
                            <td>Position</td>
                            <td>Plaintiff</td>
                        </tr>
                        <tr>
                            <td>Hearing Court </td>
                            <td>The Eighth Trial Division of the Second People's Court of Zhongshan City,
                                Guangdong Province
                            </td>
                        </tr>
                        <tr>
                            <td>Date Type</td>
                            <td>Trial Date</td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td>2017-11-3</td>
                        </tr>
                        <tr>
                            <td>Cause</td>
                            <td>Disputes over sales contracts</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Name</td>
                            <td>SEYAS ELECTRONICS CO., LTD.</td>
                        </tr>
                        <tr>
                            <td>File No.</td>
                            <td>(2017) Yue 2072 Min Chu 10446</td>
                        </tr>
                        <tr>
                            <td>Hearing Court </td>
                            <td>The Eighth Trial Division of the Second People's Court of Zhongshan City,
                                Guangdong Province
                            </td>
                        </tr>
                        <tr>
                            <td>Cause of Action</td>
                            <td>Disputes over sales contracts</td>
                        </tr>
                        <tr>
                            <td>Position</td>
                            <td>Plaintiff</td>
                        </tr>
                        <tr>
                            <td>Date of Closing</td>
                            <td>Name</td>
                        </tr>
                        <tr>
                            <td>Tax liens</td>
                            <td colspan="2">None Found.</td>
                        </tr>
                        <tr>
                            <td>Legal cases</td>
                            <td colspan="2">None Found.</td>
                        </tr>
                        <tr>
                            <td>Other</td>
                            <td colspan="2">None Found.</td>
                        </tr>

                    </tbody>
                </table>

                <br>

                <h4>SANCTIONS</h4>
                <table>
                    <tbody>
                        <tr>
                            <td width="30%">OFAC</td>
                            <td>None Found.</td>
                        </tr>
                        <tr>
                            <td>EU</td>
                            <td colspan="2">None Found.</td>
                        </tr>
                        <tr>
                            <td>UN</td>
                            <td colspan="2">None Found.</td>
                        </tr>
                        <tr>
                            <td>BIS</td>
                            <td colspan="2">None Found.</td>
                        </tr>
                        <tr>
                            <td>Other</td>
                            <td colspan="2">None Found.</td>
                        </tr>

                    </tbody>
                </table>
                <br>

                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Remarks</td>
                            <td>Searches covering the names of the Subject and its major corporate shareholders (holding
                                25% or
                                more of the Subject's share capital or votes) were conducted using Sanctions Lists
                                published by
                                the American Office of Foreign Assets Control (OFAC), the European Union (EU), the
                                United
                                Nations (UN), and the Bureau of Industry and Security (BIS).
                            </td>
                        </tr>

                    </tbody>
                </table>
                <br>


                <table class="background-border">
                    <thead>
                        <tr>
                            <th>Year </th>
                            <th>2023</th>
                            <th>Growth</th>
                            <th>Year </th>
                            <th>2022</th>
                            <th>Growth</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Score</td>
                            <td>42/100</td>
                            <td>Lower</td>
                            <td>Score</td>
                            <td>45/100</td>
                            <td>Upper</td>
                        </tr>
                        <tr>
                            <td>Rank</td>
                            <td>42/100</td>
                            <td>Lower</td>
                            <td>Rank</td>
                            <td>45/100</td>
                            <td>Upper</td>
                        </tr>
                    </tbody>

                </table>

                <br>



                <table>
                    <tbody>
                        <tr>
                            <td width="20%">Remarks</td>
                            <td>Searches covering the names of the Subject and its major corporate shareholders (holding
                                25% or
                                more of the Subject's share capital or votes) were conducted using Sanctions Lists
                                published by
                                the American Office of Foreign Assets Control (OFAC), the European Union (EU), the
                                United
                                Nations (UN), and the Bureau of Industry and Security (BIS).
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </section>
        <br>



        <section class="investigation-notes">
            <h2 class="heading-item">Investigation Notes
            </h2>
            <div class="share-capitals-item">

                <table>
                    <tbody>
                        <tr>
                            <td width="30%">Source</td>
                            <td>The subjects official, group, local business directories and government and business
                                information portals.</td>
                        </tr>

                    </tbody>
                </table>


            </div>
        </section>
        <br>


        <section class="information-partners">
            <h2 class="heading-item">Information Partners
            </h2>
            <div class="share-capitals-item">

                <table>
                    <tbody>
                        <tr>
                            <td width="30%">Overview</td>
                            <td>Our partnership with the five largest credit reporting agencies in the world underscores
                                our
                                understanding of the importance of your credit decisions. Through this alliance, we aim
                                to provide
                                you with risk-free, seamless, and optimistic credit decisions. Our partners possess a
                                robust global
                                network that ensures the accuracy of all our information. Here are our esteemed
                                partners:</td>
                        </tr>

                    </tbody>
                </table>

                <table>
                    <tbody>
                        <tr>
                            <td><img src="image/partner1.png" alt=""></td>
                            <td><img src="image/partner2.png" alt=""></td>
                            <td><img src="image/partner3.png" alt=""></td>
                            <td><img src="image/partner4.png" alt=""></td>
                            <td><img src="image/partner5.png" alt=""></td>
                        </tr>
                        <tr>
                            <td>CRIF Solutions (India) Private
                                Limited</td>
                            <td>Dynamic Business Information
                                Limited
                            </td>
                            <td>Gladtrust Management Co
                                Ltd
                            </td>
                            <td>Professional Partner SIA</td>
                            <td>MNS Credit Management
                                Group Private Limited</td>
                        </tr>

                    </tbody>
                </table>


            </div>
        </section>
        <br>


        <section class="appendix">
            <h2 class="heading-item">Appendix
            </h2>
            <div class="share-capitals-item">

                <p>The definitions of Credilit Limited credit ratings are given as follows:</p>
                <table class="background-border">
                    <thead>
                        <tr>
                            <th>RATING </th>
                            <th>SCORE</th>
                            <th>RISK LEVEL</th>
                            <th>SUGGESTION </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Excellent</td>
                            <td>90-100</td>
                            <td>Very Low</td>
                            <td>Extending very large or large credit can be done with
                                relatively lenient terms.</td>
                        </tr>
                        <tr>
                            <td>Good</td>
                            <td>75-89</td>
                            <td>Low</td>
                            <td>Fairly large credit can be extended with standard terms</td>
                        </tr>
                        <tr>
                            <td>Satisfactory</td>
                            <td>45-74 </td>
                            <td>Average</td>
                            <td>Moderate credit requires close monitoring.</td>
                        </tr>
                        <tr>
                            <td>Average</td>
                            <td>20-44</td>
                            <td>Higher than Average </td>
                            <td>Small credit or credit should be minimized whenever
                                possible.</td>
                        </tr>
                        <tr>
                            <td>Poor</td>
                            <td>10-19</td>
                            <td>High Risk</td>
                            <td>Credit is not recommended; transactions should be
                                conducted on a cash-on-delivery basis.</td>
                        </tr>
                        <tr>
                            <td>Not Rated</td>
                            <td>00-09</td>
                            <td>Undetermined</td>
                            <td>Additional information is required to propose a credit rating.</td>
                        </tr>
                    </tbody>

                </table>

                <br>

                <p>Credilit carefully considers five key dimensions to evaluate a company's credit rating, namely the
                    subject's financial strength,
                    company character, management competence, operation capacity, and external background. These factors
                    are taken into
                    comprehensive consideration to provide a thorough assessment of the creditworthiness of the company.
                </p>
                <br>

                <table class="background-border">
                    <thead>
                        <tr>
                            <th width="30%">RATING </th>
                            <th>DESCRIPTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Financial Strength</td>
                            <td>The comprehensive analysis incorporates several key factors, namely solvency,
                                profitability,
                                operational capacity, and development capacity. Furthermore, it involves a meticulous
                                comparison
                                between the subject and the industry average financial index. These aspects carry a
                                weight
                                ranging from 10% to 30% within the overall evaluation.
                            </td>
                        </tr>
                        <tr>
                            <td>Company Character</td>
                            <td>The comprehensive analysis includes an examination of the subject's character, which may
                                be
                                influenced by transaction records, judicial records, administrative supervision
                                information, tax records, and operational status, among others. This aspect carries a
                                weight of 20% to 25% in the
                                overall evaluation.</td>
                        </tr>
                        <tr>
                            <td>Management Competence</td>
                            <td>The comprehensive analysis encompasses an assessment of the subject's shareholder
                                background and strength, as well as the background and strength of related companies and
                                management experience. This aspect holds a weightage of 20% to 25% in the overall
                                evaluation.</td>
                        </tr>
                        <tr>
                            <td>Operation Capacity</td>
                            <td>The comprehensive analysis entails an examination of the subject's current operational
                                condition,
                                encompassing aspects such as products, history, staff scale, intellectual property, and
                                business
                                size. This analysis holds a weightage of 20% to 25% in the overall evaluation.</td>
                        </tr>
                        <tr>
                            <td>External Background</td>
                            <td>As part of the comprehensive analysis, an evaluation is conducted on the external
                                factors that
                                may impact the subject, encompassing the industry background, location background,
                                competitors, and other relevant aspects. This analysis carries a weightage of 10% to 15%
                                in the
                                overall assessment.
                            </td>
                        </tr>

                    </tbody>

                </table>

                <br>


                <section class="desclaimer">
                    <h2 class="heading-item">DISCLAIMER & CONFIDENTIALITY
                    </h2>
                    <div class="share-capitals-item">

                        <p>
                            The following information is provided by Credilit Limited at your request, and it is subject
                            to the terms and conditions outlined in your subscription contract.
                            This information is strictly confidential and should not be disclosed to any third parties.

                        </p>
                        <p>

                            This report is provided to the Subscriber in strict confidence and is intended for the
                            Subscriber's exclusive use as a factor to consider in credit and other
                            business decisions. The report contains information compiled from various sources that
                            Credilit does not control, and unless otherwise indicated in this report,
                            it has not been verified by Credilit. Therefore, Credilit does not assume responsibility for
                            the accuracy, completeness, or timeliness of the report. Credilit
                            disclaims any liability for any loss or damage arising from the use of the report's
                            contents. While Credilit has made every effort to ensure the accuracy.
                        </p>
                        <p>

                            This report is considered strictly confidential and proprietary to Credilit and/or its
                            information providers. Reproduction, publication, or disclosure of this report
                            to others without the express written authorization of Credilit is strictly prohibited. We
                            value your trust in Credilit Limited services. Should you have any
                            inquiries or require further assistance, please do not hesitate to contact us at
                            support@credilit.com.
                        </p>


                        <h4>—————————— END OF REPORT ——————————</h4>

                    </div>
                </section>
                <br>


            </div>
        </section>
        <br>








        <footer>
            <p>© Credilit Limited 2024. All rights reserved | Page 1 of 23</p>
        </footer>
    </div>
</body>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scoreValue = 58;

        const chart = Highcharts.chart('container-speed', {
            chart: {
                type: 'gauge',
                height: '110%',
            },

            title: {
                text: '',
                style: {
                    fontSize: '0px'
                }
            },

            pane: {
                startAngle: -90,
                endAngle: 90,
                background: [{
                        outerRadius: '100%',
                        innerRadius: '60%',
                        backgroundColor: '#94B4C1',
                        shape: 'arc',
                        from: 0,
                        to: 10
                    },
                    {
                        outerRadius: '100%',
                        innerRadius: '60%',
                        backgroundColor: '#C5172E',
                        shape: 'arc',
                        from: 10,
                        to: 20
                    },
                    {
                        outerRadius: '100%',
                        innerRadius: '60%',
                        backgroundColor: '#FF9B17',
                        shape: 'arc',
                        from: 20,
                        to: 45
                    },
                    {
                        outerRadius: '100%',
                        innerRadius: '60%',
                        backgroundColor: '#85A947',
                        shape: 'arc',
                        from: 45,
                        to: 70
                    },
                    {
                        outerRadius: '100%',
                        innerRadius: '60%',
                        backgroundColor: '#3E7B27',
                        shape: 'arc',
                        from: 70,
                        to: 90
                    },
                    {
                        outerRadius: '100%',
                        innerRadius: '60%',
                        backgroundColor: '#123524',
                        shape: 'arc',
                        from: 90,
                        to: 100
                    }
                ]
            },

            tooltip: {
                enabled: false
            },

            yAxis: {
                min: 0,
                max: 100,
                tickPositions: [],
                lineWidth: 0,
                labels: {
                    enabled: false
                }
            },

            plotOptions: {
                gauge: {
                    dial: {
                        radius: '100%',
                        backgroundColor: 'black',
                        baseWidth: 8,
                        topWidth: 1
                    },
                    pivot: {
                        radius: 6,
                        backgroundColor: '#000'
                    }
                }
            },

            series: [{
                name: 'Score',
                data: [scoreValue],
                dataLabels: {
                    enabled: true
                }
            }]
        });

        // Add static labels manually
        const renderer = chart.renderer;
        const centerX = chart.plotLeft + chart.plotWidth / 2;
        const centerY = chart.plotTop + chart.plotHeight * 0.85;
        const radius = 150;

        const labels = [{
                text1: 'Not Rated',
                angle: -100
            },
            {
                text1: 'Poor',
                angle: -50
            },
            {
                text1: 'Average',
                angle: -15
            },
            {
                text1: 'Satisfactory',
                angle: 20
            },
            {
                text1: 'Good',
                angle: 50
            },
            {
                text1: 'Excellent',
                angle: 75
            }
        ];

        labels.forEach(l => {
            const rad = l.angle * (Math.PI / 180);
            const x = centerX + radius * Math.cos(rad);
            const y = centerY + radius * Math.sin(rad);

            renderer.text(l.text1, x - 20, y - 5)
                .css({
                    fontSize: '11px',
                    color: '#000'
                })
                .attr({
                    align: 'left'
                })
                .add();
        });
    });
</script>





</html>
