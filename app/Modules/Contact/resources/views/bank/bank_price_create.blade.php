@extends('Admin::layouts.master')
@section('body')
    @push('css')
        <style>
            .generate-button {
                display: block;
                position: fixed;
                bottom: 30px;
                right: 40px;
            }

            #printMe {
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

            @media print {

                .panel,
                .page-breadcrumb,
                .subheader {
                    display: none !important;
                }

                @page {
                    size: a4 portrait;
                }

                #printMe {
                    display: block;
                }

                td,
                th {
                    line-height: 0px;
                    font-size: {{ $reportFontSize }}px;
                }
            }
        </style>
    @endpush
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('Academic::label.EASCA_EDUCATION')</a></li>
        <li class="breadcrumb-item">@lang('Contact::label.BANK') @lang('Student::label.INFORMATION')</li>
        <li class="breadcrumb-item">@lang('Contact::label.BANK') @lang('Item::label.COUNTRY') @lang('Item::label.WISE') @lang('Item::label.PRICING')</li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-edit'></i>@lang('Contact::label.BANK') @lang('Item::label.COUNTRY') @lang('Item::label.WISE') @lang('Item::label.PRICING')
            {{-- <small>
                Default input elements for forms
            </small> --}}
        </h1>
    </div>
    <div class="row">
        <div class="col-xl-12">
            {!! Form::model($id, [
                'method' => 'PATCH',
                'route' => ['bank.price.store', $id],
                'files' => true,
                'name' => 'bank-price-add',
                'id' => 'bankPriceAdd',
                'class' => 'form-horizontal',
                'autocomplete' => true,
            ]) !!}

            @include('Contact::bank.bank_price_form')
            {!! Form::close() !!}
        @endsection
