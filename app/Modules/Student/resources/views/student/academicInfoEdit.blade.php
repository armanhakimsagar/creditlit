<style>
    .pic-header {
        margin-left: 30px;
    }

    .profile-images-card {
        display: table;
        margin-left: 30px;
    }

    .profile-images {
        width: 130px;
        height: 130px;
        overflow: hidden;
    }

    .profile-images img {
        width: 100%;
        height: 100%;
        object-fit: cov
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
        width: 250px;
        padding: 0 0 0 20px;
    }

    .totalCount {
        font-size: 14px;
        font-weight: 700;
    }
</style>
<div class="table-responsive">
    <input type="text" id="searchInput" placeholder="Search by Name or SID">
    <br>
    <label for="" class="totalCount mt-2">Total: <span id="resultCount">{{ $totalStudent }}</span></label>
    <br>
    <table class="table table-bordered table-striped ytable" id="dt-basic-example">
        <thead class="thead-themed">
            <tr>
                <th> Sl</th>
                <th style="width: 10%;" class="table-checkbox-header-center">
                    <span>Check All</span>
                    <input type="checkbox" class="all-check-box" id="chkbxAll" onclick="return checkAll()">
                </th>
                {{-- <th> Photo</th> --}}
                <th> SID</th>
                <th> Student Name</th>
                <th> Roll No</th>
                <th> Shift</th>
                <th> Section</th>
                <th width="20%"> Upload Image</th>
            </tr>
        </thead>
        <tbody>
            @if (!$students->isEmpty())
                @foreach ($students as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="table-checkbox-column">
                            <input type="checkbox" class="allCheck all-check-box" id="student_{{ $student->id }}"
                                name="students[{{ $student->id }}]" value="{{ $student->id }}"
                                keyValue="{{ $student->id }}" onclick="unCheck(this.id);isChecked()">
                        </td>
                        {{-- <td>
                            @if (isset($student->photo))
                                <img src="{{ asset(config('app.asset') . 'backend/images/students/' . $student->photo) }}"
                                    name="student_picture" id="uploadImg_{{ $student->id }}" style="height: 50px;">
                            @else
                                <img src="{{ asset(config('app.asset') . 'backend/images/students/profile.png') }}"
                                    name="student_picture" id="uploadImg_{{ $student->id }}" style="height: 50px;">
                            @endif
                        </td>
                        <td><a href="{{route(app('SID'),$student->id)}}" target="_blank" style="text-decoration: none;">{{ $student->contact_id }}</a></td>
                        <td><a href="{{route(app('studentName'),$student->id)}}" target="_blank" style="text-decoration: none;">{{ $student->full_name }}</a></td>
                        </td> --}}
                        <td><a href="{{ route(app('SID'), $student->id) }}" target="_blank"
                                style="text-decoration: none;">{{ $student->contact_id }}</a></td>
                        <td><a href="{{ route(app('studentName'), $student->id) }}" target="_blank"
                                style="text-decoration: none;">{{ $student->full_name }}</a></td>
                        <td>
                            <input type="text" name="roll[{{ $student->id }}]" class="classRoll form-control"
                                data-key="{{ $student->id }}" onchange="rollValidate({{ $student->id }})"
                                id="roll{{ $student->id }}" value="{{ $student->class_roll }}">
                            <p class="error error-roll" id="error_roll{{ $student->id }}"></p>
                        </td>
                        <td>
                            {!! Form::select('shift[' . $student->id . ']', $shiftList, $student->shift_id, ['class' => 'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::select('section[' . $student->id . ']', $sectionList, $student->section_id, [
                                'class' => 'form-control',
                            ]) !!}
                        </td>
                        <td>
                            <div class="profile-images-card">
                                @if (isset($student->photo))
                                    <img src="{{ asset(config('app.asset') . 'backend/images/students/' . $student->photo) }}"
                                        name="student_picture" id="upload-img_{{ $student->id }}"
                                        style="height: 80px;">
                                @else
                                    <img src="{{ asset(config('app.asset') . 'backend/images/students/profile.png') }}"
                                        name="student_picture" id="upload-img_{{ $student->id }}"
                                        style="height: 80px;">
                                @endif
                                <div class="custom-file">
                                    <label for="fileupload_{{ $student->id }}">Upload Profile</label>
                                    <br>
                                    <span class="error"> {!! $errors->first('photo') !!}</span>
                                    <input type="file" accept="image/*" id="fileupload_{{ $student->id }}"
                                        class="fileupload" name="photo[{{ $student->id }}]"
                                        onchange="validateSize(this)">
                                    <input type="hidden" name="old_photo[{{ $student->id }}]"
                                        value="{{ $student->photo }}">
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

<script>
    $(function() {
        $("[id^=fileupload_]").change(function(event) {
            var id = $(this).attr('id').split('_')[1];
            var x = URL.createObjectURL(event.target.files[0]);
            $("#upload-img_" + id).attr("src", x);
            console.log(event);
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
            $('#dt-basic-example tbody tr').each(function() {
                var name = $(this).find('td:nth-child(4)').text()
                    .toLowerCase();
                var sid = $(this).find('td:nth-child(3)').text()
                    .toLowerCase();

                // Check if the name or SID contains the query
                if (name.includes(query) || sid.includes(query)) {
                    $(this).show();
                    visibleRows++;
                } else {
                    $(this).hide();
                }
            });
            $('#resultCount').text(visibleRows);
        }
    });

    function rollValidate(elementId) {
        var values = [];
        $(".classRoll").each(function() {
            values.push($(this).val());
        });
        var rollValue = $('#roll' + elementId).val();
        var rollArray = values.sort();
        var duplicate = 0;
        var nbOcc = $.grep(rollArray, function(elem) {
            return elem == rollValue;
        }).length;
        if (nbOcc > 1) {
            $('#error_roll' + elementId).html('Duplicate Entry!!!');
            $(".classRoll").attr("disabled", true);
            $('#roll' + elementId).attr("disabled", false);
            $('#roll' + elementId).css("background-color", "yellow");
            $('#submitBtn').attr("disabled", true);
        } else {
            $('#error_roll' + elementId).html('');
            $(".classRoll").attr("disabled", false);
            $('#roll' + elementId).css("background-color", "#fff");
            $('#submitBtn').attr("disabled", false);

        }
    };
</script>
