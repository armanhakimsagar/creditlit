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
        .print-me-card{
            padding: 80px 60px;
        }
        #print-me{
            display: none;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                font-size: 12px !important;
            }

            h5 {
                font-size: 12px;
                margin-top: 0px;
                margin-bottom: 0px;
            }

            h2 {
                font-size: 24px;
            }

            address{
                font-size: 18px;
            }

            h3.small {
                font-size: 65%;
                color: #777;
            }

            p{
                font-size: 16px;
            }

            @page {
                size: a4 portrait;
                margin: 0mm 0mm 0mm 2mm !important;
            }

            .pagebreak {
                page-break-after: always;
            }

            .page-break {
                page-break-before: always;
            }

            /* Avoid page break after the element with the class 'avoid-page-break' */
            .avoid-page-break {
                page-break-after: avoid;
            }

            #print-me{
                display: block;
            }

            .not-print,
            .breadcrumb{
                display: none;
            }
        }
    </style>
    <div class="col-xl-12">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Admin::label.EASCA_EDUCATION')</a></li>
            <li class="breadcrumb-item">@lang('Student::label.STUDENT') @lang('Student::label.INFORMATION')</li>
            <li class="breadcrumb-item active">@lang('Student::label.STUDENT') @lang('Student::label.PROFILE')</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>

        <div id="print-me">
            <div class="card print-me-card">
                <div class="row">
                    <div class="col-12">
                        <h2 class="text-center">{{$companyDetails->company_name}}</h2>
                        <address class="text-center">{{$companyDetails->address}}</address>
                        <h2 class="text-center">Student Profile</h2>
                    </div>
                    <div class="col-12">
                        <p>student Name and SID : {{$student->full_name}}({{$student->student_id}})</p>
                    </div>

                    <div class="col-3">
                        <p>Class : {{$student->class_name}}</p>
                    </div>
                    <div class="col-3">
                        <p>Section : {{$student->section_name}}</p>
                    </div>
                    <div class="col-3">
                        <p>Roll : {{$student->class_roll}}</p>
                    </div>
                    <div class="col-3">
                        <p>Shift : {{$student->shift_name}}</p>
                    </div>
                    <div class="col-3">
                        <p>Car User : {{$student->transport_name}}</p>
                    </div>
                    <div class="col-3">
                        <p>Gender : 
                            @if ($student->gender == 'male')
                                Boy
                            @else
                                Girl
                            @endif
                        </p>
                    </div>
                    <div class="col-3">
                        <p>Date Of Birth : {{$student->date_of_birth}}</p>
                    </div>
                    <div class="col-3">
                        <p>Admission Date : {{$student->admission_date}}</p>
                    </div>
                    <div class="col-3">
                        <p>Guardian Name : {{$student->guardian_name}}</p>
                    </div>
                    <div class="col-3">
                        <p>Guardian Mobile : {{$student->guardian_phone}}</p>
                    </div>
                    <div class="col-3">
                        <p>Status : {{$student->status}}</p>
                    </div>
                    <div class="col-12">
                        <p>Father Name & Mobile No : {{$student->father_name}}({{$student->father_phone}})</p>
                    </div>
                    <div class="col-12">
                        <p>Mother Name & Mobile No : {{$student->mother_phone}}({{$student->father_phone}})</p>
                    </div>
                    <div class="col-12">
                        <p>Present Address : {{$student->present_address}}, {{$student->present_upazila}}, {{$student->present_district}}, {{$student->present_division}}</p>
                    </div>
                    <div class="col-12">
                        <p>Parmanent Address : {{$student->permanent_address}}, {{$student->permanent_upazila}}, {{$student->permanent_district}}, {{$student->permanent_devision}}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row not-print">
            <div class="col-lg-4">
                <div class="box box-primary card">
                    <div class="box-body box-profile">
                        <div class="text-center print_div float-left ml-2">
                            <button class="btn btn-md avoid print" id="print"
                                style="margin: 1em 0; float: right;color: #fff;
                                background-color: #17a2b8; border-color: #17a2b8; z-index: 999;"
                                type="button" onclick="window.print()">
                                <i class="fas fa-print"></i>&nbsp;Print
                            </button>
                        </div>
                        <div class="float-right m-3"> <a href="{{route('student.edit',$id)}}" type="button" class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="Student Edit" target="__blank" ><i class="fas fa-edit"></i></a> </div>
                        <img class="profile-user-img img-responsive img-circle card-img"
                            src="{{ asset(config('app.asset') . 'backend/images/students/' . $student->student_photo) }}"
                            alt="User profile picture">
                        <h3 class="profile-username text-center">{{$student->full_name}}</h3>


                        <ul class="list-group">

                            <li class="list-group-item listnoback">
                                <b>SID</b> <a class="pull-right float-right">{{$student->student_id}}</a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b>GID</b> <a class="pull-right float-right">{{$student->guardian_sid}}</a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b>Class</b> <a class="pull-right float-right">{{$student->class_name}}</a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b>Section</b> <a class="pull-right float-right">{{$student->section_name}}</a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b>Roll Number</b> <a class="pull-right float-right">{{$student->class_roll}}</a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b>Group</b> <a class="pull-right float-right">{{$student->group_name}}</a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b>Version</b> <a class="pull-right float-right">{{$student->version_name}}</a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b>Gender</b> <a class="pull-right float-right">
                                    @if ($student->gender == 'male')
                                        Boy
                                    @else
                                        Girl
                                    @endif
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-profile-tab" data-toggle="pill" href="#pills-profile"
                                role="tab" aria-controls="pills-profile" aria-selected="true">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-payment-history"
                                role="tab" aria-controls="pills-contact" aria-selected="false">Payment History</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-profile" role="tabpanel"
                            aria-labelledby="pills-profile-tab">
                            <h3 class="header">Student Info</h3>
                            <div class="card-body">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td style="width: 50%">Admission No :</td>
                                            <td>{{$student->admission_no}}</td>
                                        </tr>
                                        <tr>
                                            <td>First Name :</td>
                                            <td>{{$student->first_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Last Name :</td>
                                            <td>{{$student->last_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Registration :</td>
                                            <td>{{$student->registration_no}}</td>
                                        </tr>
                                        <tr>
                                            <td>Academic Year :</td>
                                            <td>{{$student->academic_year_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Shift :</td>
                                            <td>{{$student->shift_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Student Type :</td>
                                            <td>{{$student->student_type_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Phone :</td>
                                            <td>{{$student->student_phone}}</td>
                                        </tr>
                                        <tr>
                                            <td>E-mail :</td>
                                            <td>{{$student->student_email}}</td>
                                            </tr>
                                        <tr>
                                            <td>Blood Group :</td>
                                            <td>{{$student->blood_group}}</td>
                                        </tr>
                                        <tr>
                                            <td>Nationality :</td>
                                            <td>{{$student->nationality}}</td>
                                        </tr>
                                        <tr>
                                            <td>Religion :</td>
                                            <td>{{$student->religion}}</td>
                                        </tr>
                                        <tr>
                                            <td>Date Of Birth :</td>
                                            <td>{{{$student->date_of_birth}}}</td>
                                        </tr>
                                        <tr>
                                            <td>Birth Certificate NO:</td>
                                            <td>{{$student->birth_certificate}}</td>
                                        </tr>
                                        <tr>
                                            <td>Transport Name:</td>
                                            <td>{{$student->transport_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Previous School Name:</td>
                                            <td>{{$student->preschool}}</td>
                                        </tr>
                                        <tr>
                                            <td>Admission Date:</td>
                                            <td>{{$student->admission_date}}</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <h3 class="header">Address Details</h3>
                            <div class="card-body">
                                <table class="table table-hover">
                                    <tbody>
                                        <tr>
                                            <td>Present Address:</td>
                                            <td>{{$student->present_address}}, {{$student->present_upazila}}, {{$student->present_district}}, {{$student->present_division}}</td>
                                        </tr>
                                        <tr>
                                            <td>Permanent Address :</td>
                                            <td>{{$student->permanent_address}}, {{$student->permanent_upazila}}, {{$student->permanent_district}}, {{$student->permanent_devision}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <h3 class="header">Father Details</h3>
                            <div class="card-body">
                                <table class="table table-hover">
                                    <tbody>
                                        <tr>
                                            <td>Father Name :</td>
                                            <td>{{$student->father_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Father Phone :</td>
                                            <td>{{$student->father_phone}}</td>
                                        </tr>
                                        <tr>
                                            <td>Father E-mail :</td>
                                            <td>{{$student->father_email}}</td>
                                        </tr>
                                        <tr>
                                            <td>Father Education :</td>
                                            <td>{{$student->father_education}}</td>
                                        </tr>
                                        <tr>
                                            <td>Father Occupation :</td>
                                            <td>{{$student->father_occupation}}</td>
                                        </tr>
                                        <tr>
                                            <td>Father Company :</td>
                                            <td>{{$student->father_company}}</td>
                                        </tr>
                                        <tr>
                                            <td>Father Income :</td>
                                            <td>{{$student->father_income}}</td>
                                        </tr>
                                        <tr>
                                            <td>Father NID NO:</td>
                                            <td>{{$student->father_nid}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <h3 class="header">Mother Details</h3>
                            <div class="card-body">
                                <table class="table table-hover">
                                    <tbody>
                                        <tr>
                                            <td>Mother Name :</td>
                                            <td>{{$student->mother_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Mother Phone :</td>
                                            <td>{{$student->mother_phone}}</td>
                                        </tr>
                                        <tr>
                                            <td>Mother E-mail :</td>
                                            <td>{{$student->mother_email}}</td>
                                        </tr>
                                        <tr>
                                            <td>Mother Education :</td>
                                            <td>{{$student->mother_education}}</td>
                                        </tr>
                                        <tr>
                                            <td>Mother Occupation :</td>
                                            <td>{{$student->mother_occupation}}</td>
                                        </tr>
                                        <tr>
                                            <td>Mother Company :</td>
                                            <td>{{$student->mother_company}}</td>
                                        </tr>
                                        <tr>
                                            <td>Mother Income :</td>
                                            <td>{{$student->mother_income}}</td>
                                        </tr>
                                        <tr>
                                            <td>Mother NID NO:</td>
                                            <td>{{$student->mother_nid}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <h3 class="header">Guardian Details</h3>
                            <div class="card-body">
                                <table class="table table-hover">
                                    <tbody>
                                        <tr>
                                            <td>Guardian Name :</td>
                                            <td>{{$student->guardian_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Guardian Phone :</td>
                                            <td>{{$student->guardian_phone}}</td>
                                        </tr>
                                        <tr>
                                            <td>Guardian Relation :</td>
                                            <td>{{$student->guardian_relation}}</td>
                                        </tr>
                                        <tr>
                                            <td>Guardian Address :</td>
                                            <td>{{$guardian_address->guardian_address}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-payment-history" role="tabpanel" aria-labelledby="pills-contact-tab">
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
                                                            <a href="{{ url('payment-receipt/'.$payment->id) }}" class="btn btn-primary  btn-sm" data-toggle="tooltip" data-placement="top" title="Payment receipt" target="__blank" ><i class="fas fa-receipt"></i></a>
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
