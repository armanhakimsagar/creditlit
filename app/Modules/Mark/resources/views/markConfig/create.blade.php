@extends('Admin::layouts.master')

@section('body')
    <style>
        #generateButton {
            display: none;
        }
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('Mark::label.MARKS')</li>
        <li class="breadcrumb-item">@lang('Mark::label.MARK') @lang('Mark::label.CONFIG')</li>
        <li class="breadcrumb-item active">Add @lang('Mark::label.MARK') @lang('Mark::label.CONFIG')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i>@lang('Mark::label.MARK') @lang('Mark::label.CONFIG')
        </h1>
        <div class="col-xl-10">
            <a href="{{ route('mark.config.index') }}"
                class="btn btn-primary btn-sm pull-right m-l-10 float-right"> @lang('Mark::label.MARK')
                @lang('Mark::label.CONFIG') List</a>
        </div>
    </div>
    <div class="row clearfix">
        <div class="block-header block-header-2">
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel-content">
            {!! Form::open([
                'route' => 'mark.config.store',
                'files' => true,
                'name' => 'mark_config',
                'id' => 'markConfig',
                'class' => 'form-horizontal',
            ]) !!}
            <div class="card">
                <div class="card-body">
                    <div class="row" id="notPrintDiv">
                        <div class="col-md-3 form-data">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                        Academic Year</label>
                                    {!! Form::Select('academic_year_id', $academicYearList, !empty($currentYear) ? $currentYear->id : null, [
                                        'id' => 'academicYearId',
                                        'class' => 'form-control select2 academic-year-id',
                                    ]) !!}
                                    <span class="error" id="yearError"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 form-data">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="" class='col-form-label'>@lang('Certificate::label.SELECT')
                                        @lang('Certificate::label.CLASS')</label>
                                    {!! Form::Select('class_id', $classList, !empty($request->class_id) ? $request->class_id : null, [
                                        'id' => 'class_id',
                                        'class' => 'form-control class-id select2',
                                        'onchange' => 'getSubject();',
                                    ]) !!}
                                </div>
                                <span class="error" id="classError"></span>
                            </div>
                        </div>
                    </div>
                    <br>
                    <hr>
                    <div class="show-table">
                        <a href="#"id="viewConfig" class=" btn btn-info btn-sm" value=""  data-toggle="modal" data-target="#configModal" >View Config</a>
                        <br><hr>
                        <div>Select Subject Name</div>
                        <div class="row" id="subjectRow">

                        </div>
                        <br><hr>
                        <div>Select Exam Name</div>
                        <div class="row">
                            @if ($examList->isEmpty())
                              <div class="error">Data Not Found</div>
                            @else
                                <div class="col-md-2">
                                    <div class="custom-control custom-checkbox mt-3">
                                        <input type="checkbox" id="chkbxAll2" class="custom-control-input all-check-box"
                                            onclick="return checkAll2()">
                                            <label class="custom-control-label" for="chkbxAll2">Mark All</label>
                                    </div>
                                </div>
                                @foreach ($examList as $row)
                                <div class="col-md-2">
                                    <div class="custom-control custom-checkbox mt-3">
                                        <input type="checkbox" name="check_exam[]" value="{{ $row->id }}" class="custom-control-input allCheck2 all-check-box exam-id" keyValue="{{$row->id}}" id="examId_{{ $row->id}}" onclick="unCheck2(this.id);isChecked2();">
                                        <label class="custom-control-label" for="examId_{{ $row->id}}"> {{ $row->exam_name }}</label>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        <br><hr><br>
                        <table class="table table-bordered myTable" id="yajraTable">
                            <thead class="">
                                <tr>
                                    <th width="10%">Sl</th>
                                    <th width="10%" class=""><input type="checkbox" id="chkbxAll3" class="custom-control-input"
                                        onclick="return checkAll3()">
                                        <label class="custom-control-label" for="chkbxAll3">Check All</label>
                                    </th>
                                    <th width="20%" class="">Total Mark</th>
                                    <th width="20%" class="">Pass Mark</th>
                                    <th width="20%" class="">Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($markAttributeList->isEmpty())
                                    
                                @else
                                @foreach ($markAttributeList as $key => $value)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <div class="custom-control custom-checkbox mt-3">
                                            <input type="checkbox" name="check_mark_attribute[]" value="{{ $value->id }}" class="custom-control-input allCheck3 all-check-box attribute-id" keyValue="{{$value->id}}" id="markAttributeId_{{ $value->id}}" onclick="unCheck3(this.id);isChecked3();">
                                            <label class="custom-control-label" for="markAttributeId_{{ $value->id}}"> {{ $value->name }}</label>
                                        </div>
                                    </td>
                                    <td><input type="text" value=""class="form-control" id="total_{{ $value->id }}"  name="total[{{ $value->id }}][]"></div></td>
                                    <td><input type="text" value=""class="form-control" id="pass_{{ $value->id }}" name="pass[{{ $value->id }}][]"></div></td>
                                    <td><input type="text" value=""class="form-control" name="percentage[{{ $value->id }}][]"></div></td>
                                    
                                </tr> 
                                @endforeach
                                @endif
                                
                            </tbody>
                        </table>
                        <div class="col-md-12">
                            <div class=" float-right mt-5">
                                <button class="btn btn-primary btn-sm ml-auto waves-effect waves-themed" id="btnsm"
                                    type="submit">Save</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade default-example-modal-right-lg" id="configModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-center modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Config History</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                                    </button>
                                </div>
                                <div id="modalBody">
                
                                </div>
                            </div>
                        </div>
                    </div>
                    

                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    </body>
    <script>
        $(function() {
            $(".select2").select2();

            var elements = $("input[type!='submit'], textarea, select");
            elements.focus(function() {
                $(this).parents('li').addClass('highlight');
            });
            elements.blur(function() {
                $(this).parents('li').removeClass('highlight');
            });
            $(document).on('click', '.generate-button', function(e) {
                e.preventDefault();
                var countChecked = 0;
                $(".allCheck").each(function(index) {
                    if ($(this)[0].checked == true) {
                        countChecked++;
                    }
                });
                if (countChecked > 0) {
                    $("#examSeatGenerate").submit();
                    return (true);
                } else {
                    return (false);
                }
            });
        });
        $('body').on("click","#viewConfig", function(){
            let classId = $(this).val();
            $.get("view-config/"+classId, function(data){
               $("#modalBody").html(data);
            });
         });
        function getSubject() {
            var classId = $('#class_id').val();
            $("#viewConfig").val(classId);
            $('.show-table').css('display', 'block');
            if (classId != 0) {
                $.ajax({
                    url: "{{ url('get-subjects') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: '{!! csrf_token() !!}',
                        classId: classId,
                    },
                    // beforeSend: function() {
                    //     $('select[name="section_id"]').empty();
                    // },
                    success: function(response) {
                        $('#subjectRow').html(response.data);
                    }
                });
            }
        }

        function getSection() {
            var classId = $('#class_id').val()
            var yearId = $('#academicYearId').val();
            var html = '';
            console.log(`${classId} --- ${yearId}`);
            if (classId != 0 && yearId != 0) {
                $.ajax({
                    url: "{{ url('get-sections') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: '{!! csrf_token() !!}',
                        classId: classId,
                        yearId: yearId
                    },
                    beforeSend: function() {
                        $('select[name="section_id"]').empty();
                    },
                    success: function(response) {
                        $('select[name="section_id"]').append(
                            '<option value="0">Select Section</option>');
                        $.each(response, function(key, data) {
                            $('select[name="section_id"]').append(
                                '<option value="' + data
                                .id + '">' + data.name + '</option>');
                        });
                    }
                });
            }
        }

        function checkAll() {
            if ($('#chkbxAll').is(':checked')) {
                $(".allCheck").each(function(index) {
                    var key = $(this).attr('keyvalue');
                    if ($("#subjectId_" + key)[0].checked == false) {
                        $("#subjectId_" + key).prop('checked', true);
                    }
                });
            } else {
                $(".allCheck").each(function(index) {
                    var key = $(this).attr('keyvalue');
                    if ($("#subjectId_" + key)[0].checked == true) {
                        $("#subjectId_" + key).prop('checked', false);
                    }
                });
            }
        }
        function checkAll2() {
            if ($('#chkbxAll2').is(':checked')) {
                $(".allCheck2").each(function(index) {
                    var key = $(this).attr('keyvalue');
                    if ($("#examId_" + key)[0].checked == false) {
                        $("#examId_" + key).prop('checked', true);
                    }
                });
            } else {
                $(".allCheck2").each(function(index) {
                    var key = $(this).attr('keyvalue');
                    if ($("#examId_" + key)[0].checked == true) {
                        $("#examId_" + key).prop('checked', false);
                    }
                });
            }
        }
        function checkAll3() {
            if ($('#chkbxAll3').is(':checked')) {
                $(".allCheck3").each(function(index) {
                    var key = $(this).attr('keyvalue');
                    if ($("#markAttributeId_" + key)[0].checked == false) {
                        $("#markAttributeId_" + key).prop('checked', true);
                    }
                });
            } else {
                $(".allCheck3").each(function(index) {
                    var key = $(this).attr('keyvalue');
                    if ($("#markAttributeId_" + key)[0].checked == true) {
                        $("#markAttributeId_" + key).prop('checked', false);
                    }
                });
            }
        }

        function unCheck(id) {
            if ($('#' + id).is(':not(:checked)')) {
                $("#chkbxAll").prop("checked", false);
            }
        }

        function isChecked() {
            var countNotChecked = 0;
            $(".allCheck").each(function(index) {
                if ($(this)[0].checked == false) {
                    countNotChecked++;
                }
            });
            if (countNotChecked == 0) {
                $("#chkbxAll").prop("checked", true);
            }
        }
        function unCheck2(id) {
            if ($('#' + id).is(':not(:checked)')) {
                $("#chkbxAll2").prop("checked", false);
            }
        }

        function isChecked2() {
            var countNotChecked = 0;
            $(".allCheck2").each(function(index) {
                if ($(this)[0].checked == false) {
                    countNotChecked++;
                }
            });
            if (countNotChecked == 0) {
                $("#chkbxAll2").prop("checked", true);
            }
        }
        function unCheck3(id) {
            if ($('#' + id).is(':not(:checked)')) {
                $("#chkbxAll3").prop("checked", false);
            }
        }

        function isChecked3() {
            var countNotChecked = 0;
            $(".allCheck3").each(function(index) {
                if ($(this)[0].checked == false) {
                    countNotChecked++;
                }
            });
            if (countNotChecked == 0) {
                $("#chkbxAll3").prop("checked", true);
            }
        }

        function assignValues() {
            var getClassId = $("#class_id").val();
            $("#className").val(getClassId);
            var getAcademicId = $("#academicYearId").val();
            $("#yearId").val(getAcademicId);
            var getexamId = $("#examList").val();
            $("#examId").val(getexamId);
            var getprintId = $("#design_option").val();
            $("#printId").val(getprintId);
        }
        function validateConfig()
        {
            $('.check-validate').each(function() {
            if ($(this).prop('checked')) {
                $('#total').prop('required', true);
            }else{
                $('#total').prop('required', false);
            }
});
        }
        $(document).ready(function() {
            $('.attribute-id').click(function() {
            var checkboxId = $(this).val();

            if ($(this).is(':checked')) {
                $('#total_' + checkboxId).prop('required', true);
                $('#pass_' + checkboxId).prop('required', true);
            } else {
                $('#total_' + checkboxId).prop('required', false);
                $('#pass_' + checkboxId).prop('required', false);
            }
            });
            
            $('#btnsm').click(function(event) {
                var isAnySubjectChecked = false;

                $('.subject-id').each(function() {
                    if ($(this).is(':checked')) {
                    isAnySubjectChecked = true;
                    return false; // Exit the loop if any checkbox is checked
                    }
                });

                if (!isAnySubjectChecked) {
                    event.preventDefault(); // Prevent form submission
                    alert('Please select at least one subject.');
                }
                var isAnyexamChecked = false;

                $('.exam-id').each(function() {
                    if ($(this).is(':checked')) {
                    isAnyexamChecked = true;
                    return false; // Exit the loop if any checkbox is checked
                    }
                });

                if (!isAnyexamChecked) {
                    event.preventDefault(); // Prevent form submission
                    alert('Please select at least one exam.');
                }
                var isAnyAttributeChecked = false;

                $('.attribute-id').each(function() {
                    if ($(this).is(':checked')) {
                    isAnyAttributeChecked = true;
                    return false; 
                    }
                });

                if (!isAnyAttributeChecked) {
                    event.preventDefault(); 
                    alert('Please select at least one attribute.');
                }
                });
        });
    </script>
@endsection
