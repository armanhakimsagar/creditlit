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
    </style>
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('Announcement::label.ANNOUNCEMENT')</li>
        <li class="breadcrumb-item active">@lang('Announcement::label.WEEKEND')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i> @lang('Announcement::label.WEEKEND') @lang('Academic::label.LIST')
        </h1>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        @lang('Announcement::label.WEEKEND') <span class="fw-300"><i>@lang('Academic::label.LIST')</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        {!! Form::open([
                            'route' => 'weekend.store',
                            'files' => true,
                            'name' => 'weekend',
                            'id' => 'weekend',
                            'class' => 'form-horizontal',
                        ]) !!}

                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="frame-wrap">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped ytable" id="yajraTable">
                                            <thead class="thead-themed">
                                                <tr>
                                                    <th> Name</th>
                                                    <th> Weekend</th>
                                                    <th> Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Saturday</td>
                                                    <td>Yes</td>
                                                    <td>
                                                        <input type="checkbox" hidden="hidden" id="Saturday" name="weekend[]" value="Saturday" <?php echo $selectedDays->contains('day_name', 'Saturday') ? 'checked' : ''; ?>>
                                                        <label class="switch" for="Saturday"></label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Sunday</td>
                                                    <td>Yes</td>
                                                    <td>
                                                        <input type="checkbox" hidden="hidden" id="Sunday" name="weekend[]" value="Sunday" <?php echo $selectedDays->contains('day_name', 'Sunday') ? 'checked' : ''; ?>>
                                                        <label class="switch" for="Sunday"></label>
                                                </tr>
                                                <tr>
                                                    <td>Monday</td>
                                                    <td>Yes</td>
                                                    <td>
                                                        <input type="checkbox" hidden="hidden" id="Monday" name="weekend[]" value="Monday" <?php echo $selectedDays->contains('day_name', 'Monday') ? 'checked' : ''; ?>>
                                                        <label class="switch" for="Monday"></label>
                                                </tr>
                                                <tr>
                                                    <td>Tuesday</td>
                                                    <td>Yes</td>
                                                    <td>
                                                        <input type="checkbox" hidden="hidden" id="Tuesday" name="weekend[]" value="Tuesday" <?php echo $selectedDays->contains('day_name', 'Tuesday') ? 'checked' : ''; ?>>
                                                        <label class="switch" for="Tuesday"></label>
                                                </tr>
                                                <tr>
                                                    <td>Wednesday</td>
                                                    <td>Yes</td>
                                                    <td>
                                                        <input type="checkbox" hidden="hidden" id="Wednesday" name="weekend[]" value="Wednesday" <?php echo $selectedDays->contains('day_name', 'Wednesday') ? 'checked' : ''; ?>>
                                                        <label class="switch" for="Wednesday"></label>
                                                </tr>
                                                <tr>
                                                    <td>Thursday</td>
                                                    <td>Yes</td>
                                                    <td>
                                                        <input type="checkbox" hidden="hidden" id="Thursday" name="weekend[]" value="Thursday" <?php echo $selectedDays->contains('day_name', 'Thursday') ? 'checked' : ''; ?>>
                                                        <label class="switch" for="Thursday"></label>
                                                </tr>
                                                <tr>
                                                    <td>Friday</td>
                                                    <td>Yes</td>
                                                    <td>
                                                        <input type="checkbox" hidden="hidden" id="Friday" name="weekend[]" value="Friday" <?php echo $selectedDays->contains('day_name', 'Friday') ? 'checked' : ''; ?>>
                                                        <label class="switch" for="Friday"></label>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <div class="text-right">
                                            <button class="btn btn-primary btn-sm ml-auto waves-effect waves-themed mt-5" id="btnsm" type="submit">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>




                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
