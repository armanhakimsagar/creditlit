@extends('Admin::layouts.master')
@section('body')
    <style>
        .profile-user-img {
            display: block;
            width: 100px;
            margin: 5px auto;
        }

        .nav-pills .nav-link {
            color: #000;
        }

        .nav-pills .nav-link.active {
            background-color: #fff;
            color: #00008B;
            border-bottom: 2px solid #00008B;
        }

        tr:nth-child(even) {
            background-color: #fff;
        }

        .header {
            padding: 10px 20px;
            color: #fff;
            background-color: #75a0cb;
            margin: 0;
        }
        /* td{
            width: 50%;
        } */
    </style>
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Student::label.STUDENT') @lang('Student::label.INFORMATION')</li>
            <li class="breadcrumb-item active">@lang('Student::label.STUDENT') @lang('Student::label.PROFILE')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>

        <div class="row">
            <div class="col-lg-4">
                <div class="box box-primary card">
                    <div class="box-body box-profile">

                        <div class="float-right m-3"> <a href="{{route('customer.edit',$id)}}" type="button" class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="Customer Edit" target="__blank" ><i class="fas fa-edit"></i></a> </div>
                        <img class="profile-user-img img-responsive img-circle card-img"
                            src="{{ asset(config('app.asset') . 'backend/images/students/') }}"
                            alt="User profile picture">
                        <h3 class="profile-username text-center">{{$customerData->full_name}}</h3>
                        <ul class="list-group">

                            <li class="list-group-item listnoback">
                                <b>Customer ID</b><a class="pull-right float-right">{{!empty($customerData->contact_id) ? $customerData->contact_id : ''}}</a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b>Contact Number</b><a class="pull-right float-right">{{$customerData->cp_phone_no}}</a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b>Email</b><a class="pull-right float-right">{{$customerData->cp_email}}</a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b>Address</b><a class="pull-right float-right">{{$customerData->address}}</a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b>Gender</b> <a class="pull-right float-right">
                                   {{ $customerData->gender  }}
                                      
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item ">
                            <a class="nav-link active" id="pills-contact-tab" data-toggle="pill" href="#pills-payment-history"
                                role="tab" aria-controls="pills-contact" aria-selected="false">Payment History</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-payment-history" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <h3 class="header">Payment History</h3>
                            <div class="card-body">
                            <div class="panel-container show">
                                <div class="panel-content">
                                <div class="frame-wrap">
                                    <div class="table-responsive">
                                        <table  class="table table-bordered table-striped w-100" id="dt-basic-example">
                                            <thead>
                                                <tr>
                                                    <th width="5%"> SL</th>
                                                    <th width="10%"> Invoice No</th>
                                                    <th width="10%"> Invoice Date</th>
                                                    <th width="10%"> Grand Total</th>
                                                    <th width="10%"> Paid Ammount</th>
                                                    <th width="10%" class="text-center"> Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($payments as $key => $payment)
                                                    <tr>
                                                        <td> {{  $key + 1 }} </td>
                                                        <td>{{$payment->sales_invoice_no}}</td>
                                                        <td>{{$payment->sales_invoice_date}}</td>
                                                        <td>{{$payment->grand_total}}</td>
                                                        <td>{{$payment->paid_amount}}</td>
                                                        <td class="text-center"><a href="#"id="viewPayment" class=" btn btn-info btn-sm " data-id="{{$payment->id}}"  data-toggle="modal" data-target="#paymentModal" ><i class="fas fa-eye"></i></a>
                                                            <a href="{{ url('customer-payment-receipt/'.$payment->id) }}" class="btn btn-primary  btn-sm" data-toggle="tooltip" data-placement="top" title="Payment receipt" target="__blank" ><i class="fas fa-receipt"></i></a>
                                                            </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
     <!-- Modal Large -->
     <div class="modal fade default-example-modal-right-lg" id="paymentModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-right modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment History</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div id="modalBody">

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('body').on("click","#viewPayment", function(){
            let paymentId = $(this).data('id');
            $.get("payment-history/"+paymentId, function(data){
               $("#modalBody").html(data);
            });
         });
      </script>
@endsection
