    <style>
        .nav-pills .nav-item a{
            border: 1px solid #886AB5;
            margin-left: 5px;
        }
        .text-white small{
            font-size: 18px;
            font-weight: 600;
        }
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
        {{-- <li class="breadcrumb-item">Application Intel</li> --}}
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-chart-area'></i><span class='fw-300'>Dashboard</span>
        </h1>

    </div>

    
    <div class="card">
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="today-data-tab" data-toggle="pill" href="#today-data"
                role="tab" aria-controls="today-data" aria-selected="true">Today</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="week-data-tab" data-toggle="pill" href="#week-data"
                role="tab" aria-controls="week-data" aria-selected="false">This Week</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="month-data-tab" data-toggle="pill" href="#month-data"
                role="tab" aria-controls="month-data" aria-selected="false">This Month</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="year-data-tab" data-toggle="pill" href="#year-data"
                role="tab" aria-controls="year-data" aria-selected="false">This Year</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="all-data-tab" data-toggle="pill" href="#all-data"
                role="tab" aria-controls="all-data" aria-selected="false">All Time</a>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
     
        <div class="tab-pane fade show active" id="today-data" role="tabpanel" aria-labelledby="today-data-tab">
            <div class="card-body">
                <div class="row">

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-primary-300 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">
                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$todayTotalOrders}}
                                    <small class="m-0 l-h-n">Total Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                                style="font-size:6rem"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-info-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$todayPendingOrders}}
                                    <small class="m-0 l-h-n">Pending Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15  mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-danger-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$todayProcessingOrders}}
                                    <small class="m-0 l-h-n">Processing Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-money-bill position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-success-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$todayQueryOrders}}
                                    <small class="m-0 l-h-n">Query Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fal fa-credit-card position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-success-300 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">
                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$todayCompletedOrders}}
                                    <small class="m-0 l-h-n">Complete Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                                style="font-size:6rem"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-primary-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$todayCancelOrders}}
                                    <small class="m-0 l-h-n">Cancel Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15  mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-info-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$todayDeliveredOrders}}
                                    <small class="m-0 l-h-n">Delivered Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-money-bill position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-danger-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$todayTotalSale}}
                                    <small class="m-0 l-h-n">Total Sale</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fal fa-credit-card position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-primary-300 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">
                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    50
                                    <small class="m-0 l-h-n">Total Expense</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                                style="font-size:6rem"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-info-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$todayTotalInvoice}}
                                    <small class="m-0 l-h-n">Total Invoice</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15  mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-danger-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$todayDueInvoice}}
                                    <small class="m-0 l-h-n">Due Invoice</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-money-bill position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-success-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$todayPaidInvoice}}
                                    <small class="m-0 l-h-n">Paid Invoice</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fal fa-credit-card position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="tab-pane fade show" id="week-data" role="tabpanel" aria-labelledby="week-data-tab">
            <div class="card-body">
                <div class="row">

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-primary-300 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">
                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisWeekTotalOrders}}
                                    <small class="m-0 l-h-n">Total Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                                style="font-size:6rem"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-info-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisWeekPendingOrders}}
                                    <small class="m-0 l-h-n">Pending Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15  mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-danger-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisWeekProcessingOrders}}
                                    <small class="m-0 l-h-n">Processing Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-money-bill position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-success-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisWeekQueryOrders}}
                                    <small class="m-0 l-h-n">Query Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fal fa-credit-card position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-success-300 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">
                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisWeekCompletedOrders}}
                                    <small class="m-0 l-h-n">Complete Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                                style="font-size:6rem"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-primary-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisWeekCancelOrders}}
                                    <small class="m-0 l-h-n">Cancel Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15  mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-info-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisWeekDeliveredOrders}}
                                    <small class="m-0 l-h-n">Delivered Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-money-bill position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-danger-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisWeekTotalSale}}
                                    <small class="m-0 l-h-n">Total Sale</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fal fa-credit-card position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-primary-300 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">
                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    50
                                    <small class="m-0 l-h-n">Total Expense</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                                style="font-size:6rem"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-info-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisWeekTotalInvoice}}
                                    <small class="m-0 l-h-n">Total Invoice</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15  mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-danger-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisWeekDueInvoice}}
                                    <small class="m-0 l-h-n">Due Invoice</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-money-bill position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-success-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisWeekPaidInvoice}}
                                    <small class="m-0 l-h-n">Paid Invoice</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fal fa-credit-card position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>



        <div class="tab-pane fade show" id="month-data" role="tabpanel" aria-labelledby="month-data-tab">
            <div class="card-body">
                <div class="row">

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-primary-300 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">
                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisMonthTotalOrders}}
                                    <small class="m-0 l-h-n">Total Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                                style="font-size:6rem"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-info-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisMonthPendingOrders}}
                                    <small class="m-0 l-h-n">Pending Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15  mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-danger-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisMonthProcessingOrders}}
                                    <small class="m-0 l-h-n">Processing Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-money-bill position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-success-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisMonthQueryOrders}}
                                    <small class="m-0 l-h-n">Query Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fal fa-credit-card position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-success-300 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">
                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisMonthCompletedOrders}}
                                    <small class="m-0 l-h-n">Complete Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                                style="font-size:6rem"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-primary-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisMonthCancelOrders}}
                                    <small class="m-0 l-h-n">Cancel Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15  mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-info-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisMonthDeliveredOrders}}
                                    <small class="m-0 l-h-n">Delivered Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-money-bill position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-danger-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisMonthTotalSale}}
                                    <small class="m-0 l-h-n">Total Sale</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fal fa-credit-card position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-primary-300 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">
                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    50
                                    <small class="m-0 l-h-n">Total Expense</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                                style="font-size:6rem"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-info-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisMonthTotalInvoice}}
                                    <small class="m-0 l-h-n">Total Invoice</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15  mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-danger-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisMonthDueInvoice}}
                                    <small class="m-0 l-h-n">Due Invoice</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-money-bill position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-success-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisMonthPaidInvoice}}
                                    <small class="m-0 l-h-n">Paid Invoice</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fal fa-credit-card position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>




        <div class="tab-pane fade show" id="year-data" role="tabpanel" aria-labelledby="year-data-tab">
            <div class="card-body">
                <div class="row">

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-primary-300 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">
                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisYearTotalOrders}}
                                    <small class="m-0 l-h-n">Total Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                                style="font-size:6rem"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-info-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisYearPendingOrders}}
                                    <small class="m-0 l-h-n">Pending Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15  mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-danger-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisYearProcessingOrders}}
                                    <small class="m-0 l-h-n">Processing Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-money-bill position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-success-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisYearQueryOrders}}
                                    <small class="m-0 l-h-n">Query Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fal fa-credit-card position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-success-300 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">
                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisYearCompletedOrders}}
                                    <small class="m-0 l-h-n">Complete Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                                style="font-size:6rem"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-primary-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisYearCancelOrders}}
                                    <small class="m-0 l-h-n">Cancel Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15  mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-info-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisYearDeliveredOrders}}
                                    <small class="m-0 l-h-n">Delivered Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-money-bill position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-danger-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisYearTotalSale}}
                                    <small class="m-0 l-h-n">Total Sale</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fal fa-credit-card position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-primary-300 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">
                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    50
                                    <small class="m-0 l-h-n">Total Expense</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                                style="font-size:6rem"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-info-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisYearTotalInvoice}}
                                    <small class="m-0 l-h-n">Total Invoice</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15  mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-danger-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisYearDueInvoice}}
                                    <small class="m-0 l-h-n">Due Invoice</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-money-bill position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-success-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$thisYearPaidInvoice}}
                                    <small class="m-0 l-h-n">Paid Invoice</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fal fa-credit-card position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>




        <div class="tab-pane fade show" id="all-data" role="tabpanel" aria-labelledby="all-data-tab">
            <div class="card-body">
                <div class="row">

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-primary-300 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">
                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$allTimeTotalOrders}}
                                    <small class="m-0 l-h-n">Total Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                                style="font-size:6rem"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-info-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$allTimePendingOrders}}
                                    <small class="m-0 l-h-n">Pending Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15  mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-danger-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$allTimeProcessingOrders}}
                                    <small class="m-0 l-h-n">Processing Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-money-bill position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-success-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$allTimeQueryOrders}}
                                    <small class="m-0 l-h-n">Query Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fal fa-credit-card position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-success-300 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">
                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$allTimeCompletedOrders}}
                                    <small class="m-0 l-h-n">Complete Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                                style="font-size:6rem"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-primary-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$allTimeCancelOrders}}
                                    <small class="m-0 l-h-n">Cancel Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15  mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-info-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$allTimeDeliveredOrders}}
                                    <small class="m-0 l-h-n">Delivered Order</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-money-bill position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-danger-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$allTimeTotalSale}}
                                    <small class="m-0 l-h-n">Total Sale</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fal fa-credit-card position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-primary-300 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">
                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    50
                                    <small class="m-0 l-h-n">Total Expense</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                                style="font-size:6rem"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-info-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$allTimeTotalInvoice}}
                                    <small class="m-0 l-h-n">Total Invoice</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15  mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-danger-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$allTimeDueInvoice}}
                                    <small class="m-0 l-h-n">Due Invoice</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fas fa-money-bill position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="p-3 bg-success-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                            <div class="">
                                <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                    {{$allTimePaidInvoice}}
                                    <small class="m-0 l-h-n">Paid Invoice</small>
                                </h3>
                            </div>
                            <a href="" class="stretched-link"><i class="fal fa-credit-card position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                                style="font-size: 6rem;"></i></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        {{-- Always same section --}}
        <div class="card-body">
            <div class="row">

                <div class="col-sm-6 col-xl-3">
                    <div class="p-3 bg-primary-300 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">
                        <div class="">
                            <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                {{$totalBank}}
                                <small class="m-0 l-h-n">Total Bank</small>
                            </h3>
                        </div>
                        <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                            style="font-size:6rem"></i></a>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="p-3 bg-info-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                        <div class="">
                            <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                {{$totalBranch}}
                                <small class="m-0 l-h-n">Total Branch</small>
                            </h3>
                        </div>
                        <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15  mb-n1 mr-n4"
                            style="font-size: 6rem;"></i></a>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="p-3 bg-danger-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                        <div class="">
                            <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                {{$totalCompany}}
                                <small class="m-0 l-h-n">Total Company</small>
                            </h3>
                        </div>
                        <a href="" class="stretched-link"><i class="fas fa-money-bill position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                            style="font-size: 6rem;"></i></a>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="p-3 bg-success-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                        <div class="">
                            <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                {{$totalSupplier}}
                                <small class="m-0 l-h-n">Total Supplier</small>
                            </h3>
                        </div>
                        <a href="" class="stretched-link"><i class="fal fa-credit-card position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                            style="font-size: 6rem;"></i></a>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="p-3 bg-success-300 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">
                        <div class="">
                            <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                {{$totalEmployee}}
                                <small class="m-0 l-h-n">Total Employee</small>
                            </h3>
                        </div>
                        <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                            style="font-size:6rem"></i></a>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="p-3 bg-primary-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                        <div class="">
                            <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                {{$totalUser}}
                                <small class="m-0 l-h-n">Total User</small>
                            </h3>
                        </div>
                        <a href="" class="stretched-link"><i class="fal fa-credit-card position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                            style="font-size: 6rem;"></i></a>
                    </div>
                </div>


                <div class="col-sm-6 col-xl-3">
                    <div class="p-3 bg-info-200 rounded overflow-hidden position-relative text-white mb-g shadow" data-toggle="tooltip" data-placement="top" title="Click for view details">

                        <div class="">
                            <h3 class="display-4 d-block l-h-n m-0 fw-500">
                                {{$totalKeyPersonnel}}
                                <small class="m-0 l-h-n">Total KeyPersonnel</small>
                            </h3>
                        </div>
                        <a href="" class="stretched-link"><i class="fas fa-users position-absolute pos-right pos-bottom opacity-15  mb-n1 mr-n4"
                            style="font-size: 6rem;"></i></a>
                    </div>
                </div>

            </div>


            <div class="row">
                @if (count($lastTenPendingOrder)>0)   
                    <div class="col-lg-6">
                        <button type="button" class="btn btn-primary mb-3">
                            Last 10 Pending Order:
                        </button>
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">SL</th>
                                <th scope="col">Order ID</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Order Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($lastTenPendingOrder as $key => $item)
                                <tr>
                                    <th scope="row">{{++$key}}</th>
                                    <td>{{$item->order_invoice_no}}</td>
                                    <td>{{$item->customer_name}}</td>
                                    <td>
                                        @php
                                            $dateString = ($item->order_date);
                                            $timestamp = strtotime($dateString);
                                            $formattedDate = date('jS F, Y', $timestamp);
                                        @endphp
                                        {{$formattedDate}}
                                    </td>
                                </tr>
                            @endforeach
                            
                            </tbody>
                        </table>
                    </div>
                @endif
                
                @if (count($lastTenDueInvoice)>0)  
                    <div class="col-lg-6">
                        <button type="button" class="btn btn-primary mb-3">
                            Last 10 Due Invoice:
                        </button>
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">SL</th>
                                <th scope="col">Invoice No</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Invoice Amount($)</th>
                                <th scope="col">Date</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($lastTenDueInvoice as $key => $item)
                                    <tr>
                                        <th scope="row">{{++$key}}</th>
                                        <td>{{$item->invoiceNo}}</td>
                                        <td>{{$item->customer_name}}</td>
                                        <td>{{$item->total_amount}}</td>
                                        <td>{{$item->invoiceDate}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>


    </div>
   
</div>


