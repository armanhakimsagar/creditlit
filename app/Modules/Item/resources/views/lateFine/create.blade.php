@extends('Admin::layouts.master')
@section('body')
    <style>
        tr>th,
        tr>td {
            text-align: center;
        }

        tr:nth-child(even),
        tr:nth-child(odd) {
            background-color: #fff;
        }

        /* for switch button */
        .switch {
            display: inline-block;
            position: relative;
            width: 50px;
            height: 25px;
            border-radius: 20px;
            background: #dfd9ea;
            transition: background 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            vertical-align: middle;
            cursor: pointer;
        }

        .switch::before {
            content: '';
            position: absolute;
            top: 1px;
            left: 2px;
            width: 22px;
            height: 22px;
            background: #fafafa;
            border-radius: 50%;
            transition: left 0.28s cubic-bezier(0.4, 0, 0.2, 1), background 0.28s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .switch:active::before {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.28), 0 0 0 20px rgba(128, 128, 128, 0.1);
        }

        input:checked+.switch {
            background: #7453A6;
        }

        input:checked+.switch::before {
            left: 27px;
            background: #fff;
        }

        input:checked+.switch:active::before {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.28), 0 0 0 20px rgba(0, 150, 136, 0.2);
        }

        .custom-file label {
        cursor: pointer;
        color: #fff;
        margin-top: 15px;
        background-color: #25316D;
        padding: 6px 8px;
    }

    .fileupload {
        display: none;
    }

    #searchInput {
        height: 35px;
        width: 200px;
        border-radius: 20px;
        padding: 0 0 0 20px;
    }
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item active">@lang('Company::label.INSTITUTION') @lang('Company::label.SETTINGS')</li>
        <li class="breadcrumb-item active">{{ $pageTitle }}</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> {{ $pageTitle }}
        </h1>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        {{ $pageTitle }}</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        {!! Form::open([
                            'route' => 'late.fine.store',
                            'files' => true,
                            'name' => 'lateFine',
                            'id' => 'lateFine',
                            'class' => 'form-horizontal',
                        ]) !!}

                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="frame-wrap">

                                    <div class="row">

                                        <div class="col-md-5 form-data">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                                        @lang('Certificate::label.SESSION')</label>
                                                    {!! Form::Select('academic_year_id', $academicYearList, !empty($currentYear) ? $currentYear->id : null, [
                                                        'id' => 'academicYearId',
                                                        'class' => 'form-control select2 academic-year-id',
                                                    ]) !!}
                                                    <span class="error"
                                                        id="yearError">{{ $errors->first('academic_year_id') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-5 form-data">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                                        @lang('Payment::label.MONTH')</label>
                                                    {!! Form::Select('month_id', $enumMonth, !empty($currentMonth) ? $currentMonth->id : null, [
                                                        'id' => 'monthId',
                                                        'class' => 'form-control select2 month-id',
                                                    ]) !!}
                                                    <span class="error"
                                                        id="yearError">{{ $errors->first('month_id') }}</span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-2 form-data">
                                            <div class="form-group">
                                                <div class="form-line mt-1">
                                                    <div class="text-left">
                                                        <button class="btn btn-primary btn-sm ml-auto waves-effect waves-themed mt-5" id="btnsm" type="submit">Generate</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}

                        
                        <div class="row clearfix" style="margin-top: 100px;">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if(!$generatedData->isEmpty())
                                <div class="card">
                                    <div class="body">
                                        <div class="table-responsive p-5">
                                            <input type="text" id="searchInput" placeholder="Search by Name or SID">
                                            <br><br>
                                            <label for="" class="h4">Total: <span id="resultCount">{{$totalStudent}}</span></label>
                                            <br><br>
                                            <table class="table table-hover" id="testTable">
                                                <thead class="thead-themed">
                                                    <tr>
                                                        <th>SL No</th>
                                                        <th>Class Name</th>
                                                        <th>Generated Month</th>
                                                        <th>Year</th>
                                                        <th>Late Fine Generated Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $total_rows = 0;
                                                    ?>
                                                    @foreach($generatedData as $key => $values)
                                                    <tr>
                                                        <td>{{ ++$total_rows }}</td>
                                                        <td>{{ $values->class_name }}</td>
                                                        <td>{{ $values->month_name }}</td>
                                                        <td>{{ $values->year_name }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($values->created_at)) }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                
            </div>
        </div>
    </div>
    <script>
        // To check date
        function validateDay(input) {
            var day = parseInt(input.value);
            var dataKey = input.getAttribute('data-key');
            var errorSpanId = 'date_error_' + dataKey;
            var errorSpan = document.getElementById(errorSpanId);
            var btn = document.getElementById('btnsm');

            if (day < 1 || day > 31) {
                errorSpan.textContent = "Invalid day. Please enter a value between 1 and 31.";
                btn.disabled = true;
            } else {
                errorSpan.textContent = "";
                btn.disabled = false;
            }
        }




        $(document).ready(function() {
            $('#visibleProductElement2').select2({
                tags: false,
                width: '100%'
            });
            $('.select2').select2({
                tags: false,
                width: '100%'
            });
        });


        $(document).ready(function() {
        // Trigger the search function whenever a key is released in the search input
            $('#searchInput').keyup(function() {
                searchTable($(this).val());
            });

            // Function to search the table based on the given query
            function searchTable(query) {
                // Convert lowercase for case-insensitive search
                query = query.toLowerCase();
                var visibleRows = 0; 

                // Iterate over each table row in the tbody
                $('#testTable tbody tr').each(function() {
                    var className = $(this).find('td:nth-child(2)').text()
                        .toLowerCase();
                    var monthName = $(this).find('td:nth-child(3)').text()
                        .toLowerCase();
                    var yearName = $(this).find('td:nth-child(4)').text()
                        .toLowerCase();

                    // Check if the className or monthName or yearName contains the query
                    if (className.includes(query) || monthName.includes(query) || yearName.includes(query)) {
                        $(this).show();
                        visibleRows++;
                    } else {
                        $(this).hide();
                    }
                });
                $('#resultCount').text(visibleRows);
            }
        });
    </script>
@endsection
