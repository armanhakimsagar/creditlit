@if (!empty(Auth::guard()->user()) > 0)
    <aside class="page-sidebar">
        <div class="page-logo">
            <a href="#" class="page-logo-link press-scale-down d-flex align-items-center position-relative">
                @if ($companyDetails->image != null && File::exists(public_path("uploads/company/{$companyDetails->image}")))
                    <img style="height: 60px;"
                        src="{{ URL::to(config('app.asset') . '') }}/uploads/company/{{ $companyDetails->image }}"
                        class="example-p-5">
                @else
                    <img style="height: 60px;" src="{{ URL::to(config('app.asset') . '') }}/uploads/company/company.png"
                        class="example-p-5">
                @endif
            </a>
        </div>
        <!-- BEGIN PRIMARY NAVIGATION -->
        <nav id="js-primary-nav" class="primary-nav" role="navigation">
            <div class="nav-filter">
                <div class="position-relative">
                    <input type="text" id="nav_filter_input" placeholder="Filter menu" class="form-control"
                        tabindex="0">
                    <a href="#" onclick="return false;" class="btn-primary btn-search-close js-waves-off"
                        data-action="toggle" data-class="list-filter-active" data-target=".page-sidebar">
                        <i class="fal fa-chevron-up"></i>
                    </a>
                </div>
            </div>

            <ul id="js-nav-menu" class="nav-menu">

                @php
                    $server = $_SERVER['REQUEST_URI'];
                    $urls = explode('/', $server);
                    $url = explode('?', $urls[1]);

                    // dd($url);

                @endphp
                @if ($url[0] == 'admin-dashboard')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="{{ URL::to('admin-dashboard') }}" title="Home" data-filter-tags="home">
                    <i class="fal fa-home"></i>
                    <span class="nav-link-text" data-i18n="nav.usersetup">@lang('Admin::label.DASHBOARD')</span>
                </a>
                </li>
                {{-- @if ($url[0] == 'class-create' ||
    $url[0] == 'class' ||
    $url[0] == 'class-store' ||
    $url[0] == 'class-edit' ||
    $url[0] == 'class-update' ||
    $url[0] == 'class-delete' ||
    $url[0] == 'class-trash' ||
    $url[0] == 'shift' ||
    $url[0] == 'shift-create' ||
    $url[0] == 'shift-edit' ||
    $url[0] == 'shift-trash' ||
    $url[0] == 'student-type' ||
    $url[0] == 'student-type-create' ||
    $url[0] == 'student-type-edit' ||
    $url[0] == 'student-type-trash' ||
    $url[0] == 'version' ||
    $url[0] == 'version-create' ||
    $url[0] == 'version-edit' ||
    $url[0] == 'section' ||
    $url[0] == 'section-create' ||
    $url[0] == 'section-edit' ||
    $url[0] == 'group-index' ||
    $url[0] == 'group-create' ||
    $url[0] == 'group-create' ||
    $url[0] == 'group-store' ||
    $url[0] == 'group-edit' ||
    $url[0] == 'group-update' ||
    $url[0] == 'group-delete' ||
    $url[0] == 'transport-index' ||
    $url[0] == 'transport-create' ||
    $url[0] == 'transport-edit' ||
    $url[0] == 'transport-update' ||
    $url[0] == 'transport-store' ||
    $url[0] == 'version' ||
    $url[0] == 'version-create' ||
    $url[0] == 'version-edit' ||
    $url[0] == 'section' ||
    $url[0] == 'section-create' ||
    $url[0] == 'section-edit' ||
    $url[0] == 'section-asign' ||
    $url[0] == 'section-asign-create' ||
    $url[0] == 'section-asign-edit' ||
    $url[0] == 'academic-year-index' ||
    $url[0] == 'academic-year-create' ||
    $url[0] == 'academic-year-update' ||
    $url[0] == 'academic-year-edit' ||
    $url[0] == 'academic-year-store' ||
    $url[0] == 'subject' ||
    $url[0] == 'subject-create' ||
    $url[0] == 'subject-edit' ||
    $url[0] == 'subject-store' ||
    $url[0] == 'subject-trash' ||
    $url[0] == 'subject-destroy' ||
    $url[0] == 'subject-asign-create' ||
    $url[0] == 'subject-asign-store' ||
    $url[0] == 'academic-year-delete')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="#" title="Product Setup" data-filter-tags="product_setup">
                    <i class="fas fa-graduation-cap"></i>
                    <span class="nav-link-text" data-i18n="nav.product_setup">@lang('Academic::label.ACADEMIC')</span>
                </a>
                <ul>
                    @if ($url[0] == 'class-create' || $url[0] == 'class' || $url[0] == 'class-store' || $url[0] == 'class-edit' || $url[0] == 'class-update' || $url[0] == 'class-delete' || $url[0] == 'class-trash')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('class.index') }}">@lang('Academic::label.CLASS')</a></li>
                    @if ($url[0] == 'shift' || $url[0] == 'shift-create' || $url[0] == 'shift-edit' || $url[0] == 'shift-trash')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('shift.index') }}">@lang('Academic::label.SHIFT')</a></li>

                    @if ($url[0] == 'student-type' || $url[0] == 'student-type-create' || $url[0] == 'student-type-edit' || $url[0] == 'student-type-trash')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('student.type.index') }}">@lang('Academic::label.STUDENT') @lang('Academic::label.TYPE')</a></li>

                    @if ($url[0] == 'version' || $url[0] == 'version-create' || $url[0] == 'version-edit')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('version.index') }}">@lang('Academic::label.VERSION')</a></li>

                    @if ($url[0] == 'section' || $url[0] == 'section-create' || $url[0] == 'section-edit')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('section.index') }}">@lang('Academic::label.SECTION')</a></li>
                    @if ($url[0] == 'section-asign-create')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('section.asign.create') }}">@lang('Academic::label.SECTION_ASIGN_TO_CLASS')</a></li>
                    @if ($url[0] == 'group-index' || $url[0] == 'group-create' || $url[0] == 'group-create' || $url[0] == 'group-store' || $url[0] == 'group-edit' || $url[0] == 'group-update' || $url[0] == 'group-delete')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('group.index') }}">@lang('Academic::label.GROUP')</a></li>
                    @if ($url[0] == 'transport-index' || $url[0] == 'transport-create' || $url[0] == 'transport-edit' || $url[0] == 'transport-update' || $url[0] == 'transport-store')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('transport.index') }}">@lang('Academic::label.TRANSPORT')</a></li>
                    @if ($url[0] == 'academic-year-index' || $url[0] == 'academic-year-create' || $url[0] == 'academic-year-update' || $url[0] == 'academic-year-edit' || $url[0] == 'academic-year-store' || $url[0] == 'academic-year-delete')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('academic.year.index') }}">@lang('Academic::label.ACADEMIC_YEAR')</a></li>

                    @if ($url[0] == 'subject' || $url[0] == 'subject-create' || $url[0] == 'subject-edit' || $url[0] == 'subject-store' || $url[0] == 'subject-trash' || $url[0] == 'subject-destroy')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('subject.index') }}">@lang('Academic::label.SUBJECT')</a></li>

                    @if ($url[0] == 'subject-asign-create')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('subject.assign.create') }}">@lang('Academic::label.SUBJECT_ASIGN_TO_CLASS')</a></li>
                </ul> --}}


                @if (
                    $url[0] == 'student-create' ||
                        $url[0] == 'student' ||
                        $url[0] == 'student-edit' ||
                        $url[0] == 'student-profile' ||
                        $url[0] == 'student-re-admission' ||
                        $url[0] == 'student-wise-payment-price-list' ||
                        $url[0] == 'receive-payment' ||
                        $url[0] == 'payment-list' ||
                        $url[0] == 'bulk-student-edit' ||
                        $url[0] == 'receive-customer-payment' ||
                        $url[0] == 'customer' ||
                        $url[0] == 'customer-create' ||
                        $url[0] == 'customer-edit' ||
                        $url[0] == 'customer-profile' ||
                        $url[0] == 'payment-edit' ||
                        $url[0] == 'due-list' ||
                        $url[0] == 'due-details')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="#" title="Student Setup" data-filter-tags="student_setup">
                    <i class="fas fa-user-graduate"></i>
                    <span class="nav-link-text" data-i18n="nav.student_setup">@lang('Contact::label.CONTACTS')
                        @lang('Student::label.INFORMATION')</span>
                </a>


                <ul>
                    @if ($url[0] == 'keypersonnel-create')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('keypersonnel.create') }}">@lang('Stuff::label.NEW') @lang('Contact::label.KEY_PERSONNEL')</a></li>

                    @if ($url[0] == 'keypersonnel')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('keypersonnel.index') }}">@lang('Contact::label.KEY_PERSONNEL') @lang('Student::label.DETAILS')</a></li>

                    @if ($url[0] == 'bank-create')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('bank.create') }}">@lang('Stuff::label.NEW') @lang('Contact::label.BANK')</a></li>

                    @if ($url[0] == 'bank')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('bank.index') }}">@lang('Contact::label.BANK') @lang('Student::label.DETAILS')</a></li>


                    @if ($url[0] == 'branch-create')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('branch.create') }}">@lang('Stuff::label.NEW') @lang('Contact::label.BRANCH')</a></li>

                    @if ($url[0] == 'branch')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('branch.index') }}">@lang('Contact::label.BRANCH') @lang('Student::label.DETAILS')</a></li>


                    @if ($url[0] == 'company-create')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('company.create') }}">@lang('Stuff::label.NEW') @lang('Contact::label.COMPANY')</a></li>

                    @if ($url[0] == 'company')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('company.index') }}">@lang('Contact::label.COMPANY') @lang('Student::label.DETAILS')</a></li>


                </ul>



                @if (
                    $url[0] == 'student-create' ||
                        $url[0] == 'student' ||
                        $url[0] == 'student-edit' ||
                        $url[0] == 'student-profile' ||
                        $url[0] == 'student-re-admission' ||
                        $url[0] == 'student-wise-payment-price-list' ||
                        $url[0] == 'receive-payment' ||
                        $url[0] == 'payment-list' ||
                        $url[0] == 'bulk-student-edit' ||
                        $url[0] == 'receive-customer-payment' ||
                        $url[0] == 'customer' ||
                        $url[0] == 'customer-create' ||
                        $url[0] == 'customer-edit' ||
                        $url[0] == 'customer-profile' ||
                        $url[0] == 'payment-edit' ||
                        $url[0] == 'due-list' ||
                        $url[0] == 'due-details')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="#" title="Student Setup" data-filter-tags="student_setup">
                    <i class="fas fa-user-graduate"></i>
                    <span class="nav-link-text" data-i18n="nav.student_setup">@lang('Contact::label.SUPPLIER')
                        @lang('Student::label.INFORMATION')</span>
                </a>
                <ul>

                    @if ($url[0] == 'supplier-create')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('supplier.create') }}">@lang('Stuff::label.NEW') @lang('Contact::label.SUPPLIER')</a></li>

                    @if ($url[0] == 'supplier')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('supplier.index') }}">@lang('Contact::label.SUPPLIER') @lang('Student::label.DETAILS')</a></li>


                </ul>




                @if (
                    $url[0] == 'student-create' ||
                        $url[0] == 'student' ||
                        $url[0] == 'student-edit' ||
                        $url[0] == 'student-profile' ||
                        $url[0] == 'student-re-admission' ||
                        $url[0] == 'student-wise-payment-price-list' ||
                        $url[0] == 'receive-payment' ||
                        $url[0] == 'payment-list' ||
                        $url[0] == 'bulk-student-edit' ||
                        $url[0] == 'receive-customer-payment' ||
                        $url[0] == 'customer' ||
                        $url[0] == 'customer-create' ||
                        $url[0] == 'customer-edit' ||
                        $url[0] == 'customer-profile' ||
                        $url[0] == 'payment-edit' ||
                        $url[0] == 'due-list' ||
                        $url[0] == 'due-details')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="#" title="Student Setup" data-filter-tags="student_setup">
                    <i class="fas fa-th"></i>
                    <span class="nav-link-text" data-i18n="nav.student_setup">@lang('Contact::label.ORDER')
                        @lang('Student::label.INFORMATION')</span>
                </a>


                <ul>

                    @if ($url[0] == 'order-create')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('order.create') }}">@lang('Contact::label.CREATE') @lang('Contact::label.ORDER')</a></li>

                    @if ($url[0] == 'all-order')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('all.order') }}">@lang('Contact::label.ALL') @lang('Contact::label.ORDERS')</a></li>


                    @if ($url[0] == 'pending-order')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('pending.order') }}">@lang('Contact::label.PENDING') @lang('Contact::label.ORDERS')</a></li>


                    @if ($url[0] == 'processing-order')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('processing.order') }}">@lang('Contact::label.PROCESSING') @lang('Contact::label.ORDERS')</a></li>


                    @if ($url[0] == 'query-order')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('query.order') }}">@lang('Contact::label.QUERIED') @lang('Contact::label.ORDERS')</a></li>


                    @if ($url[0] == 'completed-order')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('completed.order') }}">@lang('Contact::label.COMPLETED') @lang('Contact::label.ORDERS')</a></li>


                    @if ($url[0] == 'delivered-order')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('delivered.order') }}">@lang('Contact::label.DELIVERED') @lang('Contact::label.ORDERS')</a></li>


                    @if ($url[0] == 'cancel-order')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('cancel.order') }}">@lang('Contact::label.CANCELED') @lang('Contact::label.ORDERS')</a></li>




                </ul>



                {{-- @if ($url[0] == 'student-create' || $url[0] == 'student' || $url[0] == 'student-edit' || $url[0] == 'student-profile' || $url[0] == 'student-re-admission' || $url[0] == 'student-wise-payment-price-list' || $url[0] == 'receive-payment' || $url[0] == 'payment-list' || $url[0] == 'bulk-student-edit' || $url[0] == 'receive-customer-payment' || $url[0] == 'customer' || $url[0] == 'customer-create' || $url[0] == 'customer-edit' || $url[0] == 'customer-profile' || $url[0] == 'payment-edit' || $url[0] == 'due-list' || $url[0] == 'due-details')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="#" title="Student Setup" data-filter-tags="student_setup">
                    <i class="fas fa-user-graduate"></i>
                    <span class="nav-link-text" data-i18n="nav.student_setup">@lang('Student::label.STUDENT')
                        @lang('Student::label.INFORMATION')</span>
                </a>
                <ul>
                    @if ($url[0] == 'student-create')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('student.create') }}"> @lang('Student::label.ADMISSION')</a></li>

                    @if ($url[0] == 'student' || $url[0] == 'student-edit' || $url[0] == 'student-profile' || $url[0] == 'student-wise-payment-price-list')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('student.index') }}">@lang('Student::label.STUDENT') @lang('Student::label.DETAILS')</a></li>

                    @if ($url[0] == 'student-re-admission')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('readmission.index') }}">@lang('Student::label.READMISSION')</a></li>
                    @if ($url[0] == 'bulk-student-edit')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('bulk.student.edit') }}">@lang('Student::label.BULK_PROFILE_UPDATE')</a></li>

                    @if ($url[0] == 'receive-payment')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('receive.payment', 0) }}">@lang('Payment::label.RECEIVEPAYMENT')</a></li>

                    @if ($url[0] == 'receive-customer-payment')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('receive.customer.payment', 0) }}"> @lang('Payment::label.CUSTOMER') @lang('Payment::label.PAYMENT')</a>
                    </li>

                    @if ($url[0] == 'customer' || $url[0] == 'customer-create' || $url[0] == 'customer-edit' || $url[0] == 'customer-profile')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('customer.index') }}">@lang('Item::label.CUSTOMER')</a></li>

                    @if ($url[0] == 'payment-list' || $url[0] == 'payment-edit')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('payment.list') }}">@lang('Payment::label.PAYMENTLIST')</a></li>

                    @if ($url[0] == 'due-list' || $url[0] == 'due-details')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('due.list') }}">@lang('Payment::label.DUELIST')</a></li>
                </ul> --}}


                {{-- @if ($url[0] == 'transfer-certificate-index' || $url[0] == 'testimonial-index' || $url[0] == 'studentship-index')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="#" title="Certificate Setup" data-filter-tags="certificate_setup">
                    <i class="fas fa-certificate"></i>
                    <span class="nav-link-text" data-i18n="nav.certificate_setup">@lang('Certificate::label.CERTIFICATE')</span>
                </a>
                <ul>
                    @if ($url[0] == 'transfer-certificate-index')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('transfer.certificate.index') }}">@lang('Certificate::label.TRANSFER_CERTIFICATE')</a></li>
                    @if ($url[0] == 'testimonial-index')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('testimonial.index') }}">@lang('Certificate::label.TESTIMONIAL')</a></li>
                    @if ($url[0] == 'studentship-index')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('studentship.index') }}">@lang('Certificate::label.STUDENTSHIP')</a></li>
                </ul>
                </li> --}}


                {{-- @if ($url[0] == 'exam-type' || $url[0] == 'exam-type-create' || $url[0] == 'exam-type-edit' || $url[0] == 'exam-type-trash' || $url[0] == 'exam' || $url[0] == 'exam-create' || $url[0] == 'exam-edit' || $url[0] == 'exam-seat-index' || $url[0] == 'admit-card-index')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="#" title="Exam and Result" data-filter-tags="exam_type">
                    <i class="fas fa-book-open"></i>
                    <span class="nav-link-text" data-i18n="nav.exam_setup">@lang('Examination::label.EXAM_RESULT')</span>
                </a> --}}
                {{-- <ul>
                    @if ($url[0] == 'exam-type' || $url[0] == 'exam-type-create' || $url[0] == 'exam-type-edit' || $url[0] == 'exam-type-trash')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('exam_type.index') }}">
                        @lang('Examination::label.EXAM_TYPE')</a></li>

                    @if ($url[0] == 'exam' || $url[0] == 'exam-create' || $url[0] == 'exam-edit')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('exam.index') }}">
                        @lang('Examination::label.EXAM')</a></li>
                    @if ($url[0] == 'exam-seat-index')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('exam.seat.index') }}">
                        @lang('Certificate::label.EXAM_SEAT')</a></li>

                    @if ($url[0] == 'admit-card-index')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('admit.card.index') }}">@lang('Certificate::label.ADMIT_CARD')</a></li>

                </ul> --}}
                {{-- @if ($url[0] == 'mark-attribute-index' || $url[0] == 'mark-config-index' || $url[0] == 'mark-config-create' || $url[0] == 'mark-attribute-create' || $url[0] == 'mark-attribute-edit' || $url[0] == 'student-mark-input-index' || $url[0] == 'mark-grade-index' || $url[0] == 'mark-grade-create' || $url[0] == 'student-marksheet-index' || $url[0] == 'mark-config-index')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="#" title="Exam and Result" data-filter-tags="exam_type">
                    <i class="fas fa-book-open"></i>
                    <span class="nav-link-text" data-i18n="nav.exam_setup">@lang('Mark::label.MARKS')</span>
                </a>
                <ul>
                    @if ($url[0] == 'mark-attribute-index' || $url[0] == 'mark-attribute-create' || $url[0] == 'mark-attribute-edit')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('mark.attribute.index') }}">
                        @lang('Mark::label.MARK') @lang('Mark::label.ATTRIBUTE')</a></li>

                    @if ($url[0] == 'mark-config-index' || $url[0] == 'mark-config-create')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('mark.config.index') }}">
                        @lang('Mark::label.MARK') @lang('Mark::label.CONFIG')</a></li>
                    @if ($url[0] == 'student-mark-input-index')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('student.mark.input.index') }}">
                        @lang('Examination::label.STUDENTS_MARK_INPUT')</a></li>
                    @if ($url[0] == 'mark-grade-index' || $url[0] == 'mark-grade-create')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('mark.grade.index') }}">
                        @lang('Mark::label.MARK') @lang('Mark::label.GRADE')</a></li>

                    @if ($url[0] == 'student-marksheet-index')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('student.marksheet.index') }}">
                        @lang('Mark::label.MARK_SHEET')</a></li>
                </ul> --}}

                @if (
                    $url[0] == 'category' ||
                        $url[0] == 'category-create' ||
                        $url[0] == 'category-edit' ||
                        $url[0] == 'country-index' ||
                        $url[0] == 'create-country' ||
                        $url[0] == 'edit-country' ||
                        $url[0] == 'monthly-item-index' ||
                        $url[0] == 'monthly-item-create' ||
                        $url[0] == 'pricing-index' ||
                        $url[0] == 'add-pricing' ||
                        $url[0] == 'generate-late-fine' ||
                        $url[0] == 'monthly-item-setup-index')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="#" title="Category Item" data-filter-tags="category">
                    <i class="fas fa-list-alt"></i>
                    <span class="nav-link-text" data-i18n="nav.category">@lang('Item::label.COUNTRY') @lang('Item::label.DETAILS')
                        @lang('Item::label.SETUP')</span>
                </a>
                <ul>


                    @if ($url[0] == 'category' || $url[0] == 'category-create' || $url[0] == 'category-edit')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('category.index') }}">@lang('Item::label.CATEGORY')</a></li>
                    @if ($url[0] == 'country-index' || $url[0] == 'create-country' || $url[0] == 'edit-country')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('item.index') }}">@lang('Item::label.COUNTRY')</a></li>

                    {{-- @if ($url[0] == 'monthly-item-index' || $url[0] == 'monthly-item-create')
                        <li class="active">
                        @else
                        <li>
                    @endif --}}
                    {{-- <a href="{{ route('monthly.item.index') }}">@lang('Item::label.GENERATE') @lang('Item::label.STUDENT')
                        @lang('Item::label.DUE')</a> --}}
                    </li>
                    @if ($url[0] == 'add-pricing')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('add.price') }}">@lang('Item::label.COUNTRY') @lang('Item::label.WISE') @lang('Item::label.PRICING')</a>
                    </li>

                    {{-- @if ($url[0] == 'monthly-item-setup-index')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('monthly.item.setup.index') }}">@lang('Item::label.MONTHLY') @lang('Item::label.ITEM')
                        @lang('Item::label.SETUP')</a></li>

                    @if ($url[0] == 'generate-late-fine')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('generate.late.fine') }}">@lang('Item::label.GENERATE') @lang('Item::label.LATE')
                        @lang('Item::label.FINE')</a></li> --}}

                </ul>



                @if (
                    $url[0] == 'category' ||
                        $url[0] == 'category-create' ||
                        $url[0] == 'category-edit' ||
                        $url[0] == 'country-index' ||
                        $url[0] == 'create-country' ||
                        $url[0] == 'edit-country' ||
                        $url[0] == 'monthly-item-index' ||
                        $url[0] == 'monthly-item-create' ||
                        $url[0] == 'pricing-index' ||
                        $url[0] == 'add-pricing' ||
                        $url[0] == 'generate-late-fine' ||
                        $url[0] == 'monthly-item-setup-index')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="#" title="Category Item" data-filter-tags="category">
                    <i class="fas fa-list-alt"></i>
                    <span class="nav-link-text" data-i18n="nav.category">@lang('Invoice::label.INVOICE') @lang('Item::label.DETAILS')</span>
                </a>
                <ul>


                    @if ($url[0] == 'category' || $url[0] == 'category-create' || $url[0] == 'category-edit')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('create.invoice') }}">@lang('Invoice::label.CREATE') @lang('Invoice::label.INVOICE')</a></li>


                    @if ($url[0] == 'category' || $url[0] == 'category-create' || $url[0] == 'category-edit')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('index.invoice') }}">@lang('Contact::label.ALL') @lang('Invoice::label.INVOICE')</a></li>



                </ul>


                @if (
                    $url[0] == 'category' ||
                        $url[0] == 'category-create' ||
                        $url[0] == 'category-edit' ||
                        $url[0] == 'country-index' ||
                        $url[0] == 'create-country' ||
                        $url[0] == 'edit-country' ||
                        $url[0] == 'monthly-item-index' ||
                        $url[0] == 'monthly-item-create' ||
                        $url[0] == 'pricing-index' ||
                        $url[0] == 'add-pricing' ||
                        $url[0] == 'generate-late-fine' ||
                        $url[0] == 'monthly-item-setup-index')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="#" title="Category Item" data-filter-tags="category">
                    <i class="fas fa-list-alt"></i>
                    <span class="nav-link-text" data-i18n="nav.category">@lang('Contact::label.GIFT') @lang('Contact::label.DETAILS')</span>
                </a>
                <ul>

                    @if ($url[0] == 'category' || $url[0] == 'category-create' || $url[0] == 'category-edit')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('gift.type.create') }}">@lang('Contact::label.ADD') @lang('Contact::label.GIFT')
                        @lang('Contact::label.TYPE')</a></li>

                    @if ($url[0] == 'category' || $url[0] == 'category-create' || $url[0] == 'category-edit')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('gift.type.index') }}"> @lang('Contact::label.GIFT') @lang('Contact::label.TYPE')
                        @lang('Contact::label.DETAILS')</a></li>

                    @if ($url[0] == 'category' || $url[0] == 'category-create' || $url[0] == 'category-edit')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('gift.create') }}">@lang('Contact::label.ADD') @lang('Contact::label.GIFT')</a></li>

                    @if ($url[0] == 'category' || $url[0] == 'category-create' || $url[0] == 'category-edit')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('gift.index') }}">@lang('Contact::label.GIFT') @lang('Contact::label.DETAILS')</a></li>

                </ul>

                {{-- @if ($url[0] == 'attendance-data-import-index' || $url[0] == 'student-daily-attendance' || $url[0] == 'attendance-data-insert-show')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="#" title="Data Import Item" data-filter-tags="data-import">
                    <i class="fas fa-book-reader"></i>
                    <span class="nav-link-text" data-i18n="nav.data-import">@lang('DataImport::label.Attendance')
                        @lang('DataImport::label.Details')</span>
                </a>
                <ul>
                    @if ($url[0] == 'attendance-data-import-index' || $url[0] == 'attendance-data-insert-show')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('attendance.data.import.index') }}">@lang('DataImport::label.Attendance') @lang('DataImport::label.Import')</a>
                    </li>

                    @if ($url[0] == 'student-daily-attendance')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('student.daily.attendance') }}">@lang('DataImport::label.Students') @lang('DataImport::label.DAILY')
                        @lang('DataImport::label.Attendance')</a>
                    </li>
                </ul> --}}


                {{-- </li> --}}
                {{-- @if ($url[0] == 'dynamic-sms' || $url[0] == 'guardian-sms' || $url[0] == 'teacher-sms' || $url[0] == 'due-sms')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="#" title="Sms Setup" data-filter-tags="sms_setup">
                    <i class="fas fa-sms"></i>
                    <span class="nav-link-text" data-i18n="nav.sms_setup">@lang('Sms::label.SMS')</span>
                </a>
                <ul>
                    @if ($url[0] == 'dynamic-sms')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('dynamic.sms.index') }}">@lang('Sms::label.DYNAMIC') @lang('Sms::label.SMS')</a></li>
                    @if ($url[0] == 'guardian-sms')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('guardian.sms.index') }}">@lang('Sms::label.GUARDIAN') @lang('Sms::label.SMS')</a></li>
                    @if ($url[0] == 'teacher-sms')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('teacher.sms') }}">@lang('Sms::label.TEACHER') @lang('Sms::label.SMS')</a></li>
                    @if ($url[0] == 'due-sms')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('due.sms.index') }}">@lang('Sms::label.DUE') @lang('Sms::label.SMS')</a></li>
                    @if ($url[0] == 'owner-sms-index')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('owner.sms.index') }}">@lang('Sms::label.OWNER') @lang('Sms::label.SMS')</a></li>
                </ul>
                </li> --}}

                @if (
                    $url[0] == 'employee-index' ||
                        $url[0] == 'employee-create' ||
                        $url[0] == 'employee-edit' ||
                        $url[0] == 'employee-salary-index' ||
                        $url[0] == 'employee-salary-edit' ||
                        $url[0] == 'employee-salary-create' ||
                        $url[0] == 'department' ||
                        $url[0] == 'department-edit' ||
                        $url[0] == 'department-create' ||
                        $url[0] == 'designation' ||
                        $url[0] == 'designation-edit' ||
                        $url[0] == 'designation-create' ||
                        $url[0] == 'designation-trash' ||
                        $url[0] == 'salary-item-index' ||
                        $url[0] == 'salary-item-create' ||
                        $url[0] == 'salary-item-edit' ||
                        $url[0] == 'employee-payroll-index' ||
                        $url[0] == 'employee-payroll-create' ||
                        $url[0] == 'pay-employee-salary' ||
                        $url[0] == 'employee-payroll-report' ||
                        $url[0] == 'employee-payroll-view')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="#" title="Employee Setup" data-filter-tags="employee_setup">
                    <i class="fas fa-users"></i>
                    <span class="nav-link-text" data-i18n="nav.employee_setup">@lang('Stuff::label.EMPLOYEE')</span>
                </a>
                <ul>
                    @if ($url[0] == 'department' || $url[0] == 'department-edit' || $url[0] == 'department-create')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('department.index') }}">@lang('Stuff::label.DEPARTMENT')</a></li>
                    @if (
                        $url[0] == 'designation' ||
                            $url[0] == 'designation-edit' ||
                            $url[0] == 'designation-create' ||
                            $url[0] == 'designation-trash')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('designation.index') }}">@lang('Stuff::label.DESIGNATION')</a></li>

                    @if ($url[0] == 'employee-index' || $url[0] == 'employee-edit')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('employee.index') }}">@lang('Stuff::label.EMPLOYEE') @lang('Student::label.DETAILS')</a></li>

                    @if ($url[0] == 'employee-create')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('employee.create') }}">@lang('Stuff::label.NEW') @lang('Stuff::label.EMPLOYEE')</a></li>
                    @if ($url[0] == 'salary-item-index' || $url[0] == 'salary-item-create' || $url[0] == 'salary-item-edit')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('salary.item.index') }}">@lang('Stuff::label.SALARY') @lang('Item::label.ITEM')</a></li>
                    @if ($url[0] == 'employee-salary-index' || $url[0] == 'employee-salary-create')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('employee.salary.index') }}">@lang('Stuff::label.EMPLOYEE') @lang('Stuff::label.SALARY')</a></li>
                    @if (
                        $url[0] == 'employee-payroll-index' ||
                            $url[0] == 'employee-payroll-create' ||
                            $url[0] == 'employee-payroll-create' ||
                            $url[0] == 'pay-employee-salary' ||
                            $url[0] == 'employee-payroll-report' ||
                            $url[0] == 'employee-payroll-view')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('employee.payroll.index') }}">@lang('Stuff::label.EMPLOYEE') @lang('Stuff::label.PAYROLL')</a></li>
                </ul>
                </li>

                @if (
                    $url[0] == 'expense-chart' ||
                        $url[0] == 'create-expense-chart' ||
                        $url[0] == 'edit-expense-chart' ||
                        $url[0] == 'expense-index' ||
                        $url[0] == 'edit-expense' ||
                        $url[0] == 'create-expense')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="#" title="Payment" data-filter-tags="payment">
                    <i class="fas fa-money-bill"></i>
                    <span class="nav-link-text" data-i18n="nav.exam_setup">@lang('Payment::label.PAYMENT') @lang('Payment::label.AND')
                        @lang('Payment::label.EXPENSE')</span>
                </a>
                <ul>
                    @if ($url[0] == 'expense-chart' || $url[0] == 'create-expense-chart' || $url[0] == 'edit-expense-chart')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('expense.chart') }}">
                        @lang('Payment::label.EXPENSE') @lang('Payment::label.CHART')</a></li>

                    @if ($url[0] == 'expense-index' || $url[0] == 'create-expense' || $url[0] == 'edit-expense')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('expense.index') }}">
                        @lang('Payment::label.EXPENSE')</a></li>
                </ul>
                </li>
                {{-- For Account Setting --}}
                {{-- @if ($url[0] == 'account-category-index' || $url[0] == 'account-category-create' || $url[0] == 'account-category-edit' || $url[0] == 'account-index' || $url[0] == 'account-create' || $url[0] == 'account-category-trash' || $url[0] == 'account-edit')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="#" title="Account Setting" data-filter-tags="account">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span class="nav-link-text" data-i18n="nav.exam_setup">@lang('Payment::label.ACCOUNT_SETTING')</span>
                </a>
                <ul>
                    @if ($url[0] == 'account-category-index' || $url[0] == 'account-category-create' || $url[0] == 'account-category-edit' || $url[0] == 'account-category-trash')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('account.category.index') }}">@lang('Payment::label.ACCOUNT') @lang('Payment::label.CATEGORY')</a></li>

                    @if ($url[0] == 'account-index' || $url[0] == 'account-create' || $url[0] == 'account-edit')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('account.index') }}">@lang('Payment::label.ACCOUNT')</a></li>
                </ul>
                </li> --}}

                @if (
                    $url[0] == 'permission' ||
                        $url[0] == 'permission-create' ||
                        $url[0] == 'permission-edit' ||
                        $url[0] == 'permission-update' ||
                        $url[0] == 'role' ||
                        $url[0] == 'role-trash' ||
                        $url[0] == 'role-create' ||
                        $url[0] == 'role-edit' ||
                        $url[0] == 'user' ||
                        $url[0] == 'user-create' ||
                        $url[0] == 'user-edit' ||
                        $url[0] == 'exam-create' ||
                        $url[0] == 'exam-edit' ||
                        $url[0] == 'exam-seat-index')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="#" title="Exam and Result" data-filter-tags="exam_type">
                    <i class="fas fa-book-open"></i>
                    <span class="nav-link-text" data-i18n="nav.exam_setup">@lang('User::label.USER')
                        @lang('User::label.SETUP')</span>
                </a>

                <ul>
                    @if (
                        $url[0] == 'permission' ||
                            $url[0] == 'permission-create' ||
                            $url[0] == 'permission-edit' ||
                            $url[0] == 'permission-update')
                        ;
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('permission.index') }}">
                        @lang('User::label.PERMISSION') @lang('User::label.LIST')</a></li>
                    @if ($url[0] == 'role-create' || $url[0] == 'role' || $url[0] == 'role-edit' || $url[0] == 'role-trash')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('role.index') }}">
                        @lang('User::label.ROLES') @lang('User::label.LIST')</a></li>

                    @if ($url[0] == 'user' || $url[0] == 'user-create' || $url[0] == 'user-edit')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('user.index') }}">
                        @lang('User::label.USER') @lang('User::label.LIST')</a></li>
                    @if ($url[0] == 'exam-seat-index')
                        <li class="active">
                        @else
                        <li>
                    @endif

                </ul>

                {{-- @if ($url[0] == 'holiday-create' || $url[0] == 'holiday' || $url[0] == 'holiday-edit' || $url[0] == 'holiday-trash' || $url[0] == 'weekend-create')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="#" title="holiday" data-filter-tags="holiday">
                    <i class="fas fa-megaphone"></i>
                    <span class="nav-link-text" data-i18n="nav.holiday">@lang('Announcement::label.ANNOUNCEMENT')
                </a>
                <ul>
                    @if ($url[0] == 'holiday-create' || $url[0] == 'holiday' || $url[0] == 'holiday-edit' || $url[0] == 'holiday-trash')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('holiday.index') }}">@lang('Announcement::label.HOLIDAY')</a></li>

                    @if ($url[0] == 'weekend-create')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('weekend.create') }}">@lang('Announcement::label.WEEKEND')</a></li>

                </ul> --}}


                @if (
                    $url[0] == 'admission-report-index' ||
                        $url[0] == 'admission-collection-report' ||
                        $url[0] == 'student-collection-report' ||
                        $url[0] == 'expense-report-index' ||
                        $url[0] == 'cash-book-report-index' ||
                        $url[0] == 'student-collection-report' ||
                        $url[0] == 'admission-collection-report' ||
                        $url[0] == 'sms-report' ||
                        $url[0] == 'salary-item-report' ||
                        $url[0] == 'bank-book-report-index' ||
                        $url[0] == 'account-clearence-index' ||
                        $url[0] == 'income-report-index')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="#" title="reports" data-filter-tags="reports">
                    <i class="fas fa-file-alt"></i>
                    <span class="nav-link-text" data-i18n="nav.reports">@lang('Student::label.REPORTS')
                </a>
                <ul>

                    @if ($url[0] == 'order-report')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('order.report') }}">@lang('Contact::label.ORDER') @lang('Student::label.REPORTS')</a></li>


                    {{-- @if ($url[0] == 'admission-report-index')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('admission.report.index') }}">
                        @lang('Student::label.ADMISSION'),@lang('Student::label.SESSION')
                        @lang('Student::label.COUNTING') @lang('Student::label.REPORTS')</a></li>
                    @if ($url[0] == 'admission-collection-report')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('admission.collection.report') }}">
                        @lang('Student::label.ADMISSION') @lang('Student::label.COLLECTION') @lang('Student::label.REPORTS')</a></li>
                    @if ($url[0] == 'student-collection-report')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('student.collection.report') }}">
                        @lang('Student::label.STUDENT') @lang('Student::label.COLLECTION') @lang('Student::label.REPORTS')</a></li>
                    @if ($url[0] == 'salary-item-report')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('salary.item.report') }}">
                        @lang('Stuff::label.SALARY') @lang('Stuff::label.ITEM') @lang('Student::label.REPORTS')</a></li>
                    @if ($url[0] == 'sms-report')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('sms.report') }}">
                        @lang('Sms::label.SMS') @lang('Student::label.REPORTS')</a></li>
                    @if ($url[0] == 'expense-report-index')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('expense.report.index') }}">
                        @lang('Payment::label.EXPENSE') @lang('Student::label.REPORTS')</a></li>
                    @if ($url[0] == 'income-report-index')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('income.report.index') }}">
                        @lang('Report::label.INCOME') @lang('Student::label.REPORTS')</a></li>
                    @if ($url[0] == 'cash-book-report-index')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('cash.book.report.index') }}">
                        @lang('Report::label.CASH') @lang('Report::label.BOOK') @lang('Student::label.REPORTS')</a></li>
                    @if ($url[0] == 'bank-book-report-index')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('bank.book.report.index') }}">
                        @lang('Report::label.BANK') @lang('Report::label.BOOK') @lang('Student::label.REPORTS')</a></li>

                    @if ($url[0] == 'account-clearence-index')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('account.clearence.index') }}">
                        @lang('Report::label.ACCOUNT') @lang('Report::label.CLEARENCE') @lang('Report::label.REPORTS')</a></li> --}}

                </ul>
                @if (
                    $url[0] == 'institution-create' ||
                        $url[0] == 'institution-edit' ||
                        $url[0] == 'institution-index' ||
                        $url[0] == 'institution-settings-edit' ||
                        $url[0] == 'sid-settings-edit' ||
                        $url[0] == 'fine-settings' ||
                        $url[0] == 'sms-settings')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="#" title="Settings" data-filter-tags="settings">
                    <i class="fal fa-cog"></i>
                    <span class="nav-link-text" data-i18n="nav.settings">@lang('Company::label.INSTITUTION')
                        @lang('Company::label.SETTINGS')</span>
                </a>
                <ul>
                    @if ($url[0] == 'institution-create' || $url[0] == 'institution-edit' || $url[0] == 'institution-index')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('institution.index') }}">
                        @lang('Company::label.INSTITUTION')</a></li>

                    @if ($url[0] == 'institution-settings-edit')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('institution.settings', 1) }}">
                        @lang('Company::label.SETTINGS')</a></li>

                    {{-- @if ($url[0] == 'sms-settings')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('sms.setting') }}">
                        @lang('Company::label.SMSSETTING')</a></li>

                    @if ($url[0] == 'sid-settings-edit')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('sid.setting', 1) }}">
                        @lang('Company::label.SIDSETTING')</a></li>

                    @if ($url[0] == 'fine-settings')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('fine.settings') }}">
                        @lang('Company::label.FINESETTING')</a></li> --}}
                </ul>








                {{-- @if ($url[0] == 'all-student-id-card-index' || $url[0] == 'all-guardian-id-card-index' || $url[0] == 'all-employee-id-card-index')
                    <li class="active">
                    @else
                    <li>
                @endif
                <a href="#" title="Settings" data-filter-tags="settings">
                    <i class="fas fa-id-badge"></i>
                    <span class="nav-link-text" data-i18n="nav.settings">@lang('Admin::label.ID_CARD_GENERATE')</span>
                </a>
                <ul>
                    @if ($url[0] == 'all-student-id-card-index')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('all.student.id.card.index') }}">@lang('Admin::label.STUDENTS') @lang('Admin::label.ID_CARD_GENERATE')</a>
                    </li>

                    @if ($url[0] == 'all-guardian-id-card-index')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('all.guardian.id.card.index') }}">
                        @lang('Admin::label.GUARDIAN') @lang('Admin::label.ID_CARD_GENERATE')</a></li>

                    @if ($url[0] == 'all-employee-id-card-index')
                        <li class="active">
                        @else
                        <li>
                    @endif
                    <a href="{{ route('all.employee.id.card.index') }}">
                        @lang('Admin::label.EMPLOYEE') @lang('Admin::label.ID_CARD_GENERATE')</a></li>
                </ul> --}}


                <li>
                    <a href="#" title="Settings" data-filter-tags="settings">
                        <i class="fal fa-cog"></i>
                        <span class="nav-link-text" data-i18n="nav.settings">Company Report</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('search.company') }}">
                                Search Company</a>
                        </li>

                        <li>
                            <a href="{{ route('generate.company') }}">
                                Download Report</a>
                        </li>


                    </ul>

                </li>

        </nav>
        <div class="nav-footer shadow-top">

        </div> <!-- END NAV FOOTER -->
    </aside>
    <script>
        $(document).ready(function() {
            $('#tags li').on('click', function() {
                $('#js-nav-menu li').addClass('open');
            });
        });
    </script>
@endif



{{-- @#ParentsILoveU=% --}}
