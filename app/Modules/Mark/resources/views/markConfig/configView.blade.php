<div class="modal-body">
    <div id='printMe'>

        <!------ Include the above in your HEAD tag ---------->
        <style>
            .custom-table {
                border-collapse: collapse;
                /* Collapse the borders */
                width: 100%;
                /* Set the width of the table */
            }

            .custom-table th,
            .custom-table td {
                padding: 8px;
                /* Add padding for better spacing */
            }

            tr:nth-child(even) {
                background-color: white;
            }
           .td-style td, th{
                border: 1px solid black;
            }
        </style>

        <div class="container dash-border">
            <div class="col-md-12 content text-center  mt-3">
                <h3 class="header">Mark Configuration History</h3>
                <h3 class="header">Class Name : {{ $className->name }}</h3>
            </div>
            <div class="card-body">
                <table class="table table-stripped table-responsive" class="custom-table">
                    <tbody class="">
                        @if ($data->isNotEmpty())
                            @php
                                $previousSubject = null;
                                $previousExam = null;
                            @endphp
                            @foreach ($data as $key => $item)
                                @php
                                    $configData = DB::table('mark_configs')
                                        ->where('id', $item->id)
                                        ->first();
                                    
                                    $checkData = DB::table('student_marks')
                                        ->join('student_marks_details', 'student_marks_details.student_info_id', 'student_marks.id')
                                        ->where('student_marks.academic_year_id', $configData->academic_year_id)
                                        ->where('student_marks.class_id', $configData->class_id)
                                        ->where('student_marks.subject_id', $configData->subject_id)
                                        ->where('student_marks.exam_id', $configData->exam_id)
                                        ->where('student_marks_details.attribute_mark_id', $configData->mark_attribute_id)
                                        ->first();
                                    
                                @endphp
                                @if ($previousExam !== $item->exam_name)
                                    <td colspan="6" style="border: 1px solid black;vertical-align: middle; text-align: center;background-color: #D6EEEE; font-size:16px;">{{ $item->exam_name }}</td>
                                    @php
                                        $previousExam = $item->exam_name;
                                    @endphp
                                    <tr>
                                        <th width="20%" class="text-center">Subject Name</th>
                                        <th width="20%" class="text-center">Attribute Name</th>
                                        <th width="20%" class="text-center">Total Mark</th>
                                        <th width="20%" class="text-center">Pass Mark</th>
                                        <th width="10%" class="text-center">Percentage</th>
                                        <th width="10%" class="text-center">Action</th>
                                    </tr>
                                @endif
                                <tr class="td-style">
                                    @php
                                         $dataCount = DB::table('mark_configs')
                                         ->where('mark_configs.status', 'active')
                                        ->where('mark_configs.class_id', $configData->class_id)
                                        ->where('mark_configs.subject_id',$configData->subject_id)
                                        ->where('mark_configs.exam_id',$configData->exam_id)
                                        ->count('mark_configs.mark_attribute_id');
                                    @endphp
                                    @if ($previousSubject !== $item->subject_name)
                                        <td rowspan="{{ $dataCount }}"style="vertical-align: middle; text-align: center;">{{ $item->subject_name }}</td>
                                        @php
                                            $previousSubject = $item->subject_name;
                                        @endphp
                                    @endif
                                    <td class="text-center">{{ $item->mark_attribute_name }}</td>
                                    <td class="text-center">{{ $item->total_mark }}</td>
                                    <td class="text-center">{{ $item->pass_mark }}</td>
                                    <td class="text-center">{{ $item->percentage }}</td>
                                    <td class="text-center">
                                        @if (empty($checkData))
                                            <button class="btn btn-danger btn-sm" id="delete-btn"
                                                data-id="{{ $item->id }}"><i class="fas fa-trash"></i></button>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
</div>

<script>
    $(document).on('click', '#delete-btn', function(event) {
        event.preventDefault();
        var dataId = $(this).data('id');

        $.ajax({
            url: 'view-config-delete/' + dataId,
            type: 'DELETE',
            dataType: 'json',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE'
            },
            success: function(response) {
                toastr.success(response.message, 'Success');
                $('#configModal').modal('hide');
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    });
</script>
