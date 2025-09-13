@if (!$editTitle)
    <div class="row">
        <div class="col-md-6">
            <button type="button" name="padd"
                class="btn btn-success btn-sm ml-auto mt-3 waves-effect waves-themed padd" onclick="addExam()">+ Add
                More</button>
        </div>
    </div>
@endif
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('', 'Select Session', ['class' => 'col-form-label']) !!}<span class="required"> *</span>
                {!! Form::select('academic_year_id', $academic_year, !empty($currentYear) ? $currentYear->id : null, [
                    'id' => '',
                    'class' => 'form-control ayear_select2',
                ]) !!}
                <label class="error" id="academicId" for="academic_year_id">{!! $errors->first('academic_year_id') !!}</label>
            </div>

        </div>
    </div>


    {{-- @if ($editTitle == false)
        <div class="col-md-4">
            <div class="form-group">
                <div class="form-line">
                    {!! Form::label('', 'Class Name', ['class' => 'col-form-label']) !!}<span class="required"> *</span>
                    {!! Form::select('class_id[]', $class_list, isset($exam->id) ? $exam->class_id : null, [
                        'id' => 'classId',
                        'class' => 'form-control class_select2',
                        'multiple' => true,
                    ]) !!}
                    <label class="error" id="classId" for="class_id">{!! $errors->first('class_id') !!}</label>
                </div>
            </div>
        </div>
    @else
        <div class="col-md-4">
            <div class="form-group">
                <div class="form-line">
                    {!! Form::label('', 'Class Name', ['class' => 'col-form-label']) !!}<span class="required"> *</span>
                    {!! Form::select('class_id', $class_list, isset($exam->id) ? $exam->class_id : null, [
                        'id' => '',
                        'class' => 'form-control class_select2',
                    ]) !!}
                    <label class="error" id="classId" for="class_id">{!! $errors->first('class_id') !!}</label>
                </div>

            </div>
        </div>
    @endif --}}


    <div class="col-md-4">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('', 'Exam Type Name', ['class' => 'col-form-label']) !!}<span class="required"> *</span>
                {!! Form::select('exam_type_id', $exam_type_list, isset($exam->id) ? $exam->exam_type_id : null, [
                    'id' => '',
                    'class' => 'form-control type_select2',
                ]) !!}
                <label class="error" id="typeId" for="exam_type_id">{!! $errors->first('exam_type_id') !!}</label>
            </div>

        </div>
    </div>

    <div class="col-md-4">

        <div class="form-group">
            <div class="form-line">
                {!! Form::label('status', 'Status', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                {!! Form::Select(
                    'status',
                    ['active' => 'Active', 'inactive' => 'Inactive', 'cancel' => 'Cancel'],
                    old('status'),
                    ['id' => 'status', 'class' => 'form-control selectheight'],
                ) !!}
                <span class="error"> {!! $errors->first('status') !!}</span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('exam_name', 'Exam Name', ['class' => 'col-form-label']) !!} <span class="required"> *</span>
                {!! Form::text('exam_name[]', old('exam_name'), [
                    'id' => 'exam_name',
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Enter Exam Name',
                ]) !!}
                <span class="error"> {!! $errors->first('exam_name') !!}</span>
            </div>
        </div>
    </div>

    {{-- @if ($editTitle == false)
        <div class="col-md-4">
            <div class="form-group">
                <div class="form-line">
                    {!! Form::label('percentage_for_final', 'Percentage for final', ['class' => 'col-form-label']) !!}
                    {!! Form::text('percentage_for_final[]', old('percentage_for_final'), [
                        'id' => '',
                        'class' => 'form-control',
                        'placeholder' => 'Enter Percentage for final',
                    ]) !!}
                    <span class="error"> {!! $errors->first('percentage_for_final') !!}</span>
                </div>
            </div>
        </div>
    @else
        <div class="col-md-4">
            <div class="form-group">
                <div class="form-line">
                    {!! Form::label('percentage_for_final', 'Percentage for final', ['class' => 'col-form-label']) !!}
                    {!! Form::text('percentage_for_final[]', old('percentage_for_final'), [
                        'id' => '',
                        'class' => 'form-control',
                        'placeholder' => 'Enter Percentage for final',
                    ]) !!}
                    <span class="error"> {!! $errors->first('percentage_for_final') !!}</span>
                </div>
            </div>
        </div>
    @endif --}}
{{-- </div> --}}
    <input type="hidden" id="count" value="0" name="">
    <div class="col-md-12">
        <div id="addMore">

        </div>
    </div>
    <div class="col-md-6">

        <div class=" float-right mt-5">
            <button class="btn btn-primary btn-sm ml-auto waves-effect waves-themed" onclick="Validator();"
                id="btnsm" type="submit">Save</button>
        </div>
    </div>
</div>




</div>

<script>
    $(function() {

        $("#exam").validate({
            rules: {
                exam_name: {
                    required: true,
                },
                status: {
                    required: true
                },
                class_id: {
                    required: true
                },
                exam_type_id: {
                    required: true
                },
                academic_year_id: {
                    required: true
                }

            },
            messages: {
                exam_name: 'Please enter exam name',
                status: 'Please choose status',
                exam_type_id: 'Please choose exam type',
                academic_year_id: 'Please choose academic year',
            }
        });

        $(".type_select2").select2();
        $(".ayear_select2").select2();

        $(".type_select2").change(function() {
            $('#typeId').hide();

        });
        $(".ayear_select2").change(function() {
            $('#academicId').hide();

        });
    });

    var k = 1;

    function addExam() {

        k = parseInt(k) + parseInt($('#count').val());
        var html = '';
        html +=
            '<div id="examRow_' + k + '" class="new_data">' +
            '<div class="row">' +
            '<div class="col-md-6">\n' +
            '    <div class="form-group">\n' +
            '        <div class="form-line">\n' +
            '            <label for="exam_name" class="col-form-label">Exam name</label><span class="required" aria-required="true"> *</span>\n' +
            '            <input name="exam_name[]" type="text" class="form-control" placeholder="Enter Exam name" onInput="checkDuplicate(this.id)" id=' +
            k + '  autocomplete="off" aria-required="true">\n' +
            '            <span class="error" id="nameError_' + k + '"> </span>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '</div>' +
            // '<div class="col-md-5">\n' +
            // '    <div class="form-group">\n' +
            // '        <div class="form-line">\n' +
            // '            <label for="percentage_for_final" class="col-form-label">Percentage for final</label>\n' +
            // '            <input name="percentage_for_final[]" type="text" class="form-control" placeholder="Enter Percentage for final" autocomplete="off" aria-required="true">\n' +
            // '        </div>\n' +
            // '    </div>\n' +
            // '</div>' +
            '<div class="col-md-1">' +
            '<button type="button" name="remove" onclick="removeExam(' + k +
            ')" class="btn btn-danger btn-sm mt-6 remove">Delete</button>' +
            '</div>' +
            '</div>' +
            '</div>';


        $("#addMore").append(html);

        k++;

    }

    function checkDuplicate(id) {

        var values = [];
        $("input[type='text']").each(function() {
            values.push($(this).val());

        });
        examArray = values.sort();
        var duplicate = 0;
        for (let i = 0; i < examArray.length; i++) {
            if (examArray[i + 1] == examArray[i]) {
                duplicate = examArray[i];
            }
            if (duplicate != 0) {
                $('#nameError_' + id).html('This name already exist!');
                $("#btnsm").attr("disabled", true);
                $(".padd").attr("disabled", true);
                $(".remove").attr("disabled", true);
                $('#' + id).css("background-color", "yellow");
            } else {
                $('#nameError_' + id).html('');
                $("#btnsm").attr("disabled", false);
                $(".padd").attr("disabled", false);
                $(".remove").attr("disabled", false);
                $('#' + id).css("background-color", "#fff");

            }
        }

    }

    function removeExam(examRow) {
        ($('#examRow_' + examRow).remove());
    }
</script>
