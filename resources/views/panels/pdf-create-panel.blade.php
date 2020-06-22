@php
    $levelAmount = 'level';

    if (Auth::User()->level() >= 2) {
    $levelAmount = 'levels';
    }
@endphp

<div class="card">
    <div class="card-header @role('admin', true) bg-secondary text-white @endrole">

        Welcome {{ Auth::user()->name }}

        @role('admin', true)
        <span class="pull-right badge badge-primary" style="margin-top:4px">
                Admin Access
            </span>
        @else
            <span class="pull-right badge badge-warning" style="margin-top:4px">
                User Access
            </span>
            @endrole

    </div>

    <div class="card-body">
        <div class="mb-4">
            <h2 class="lead d-inline">
                Create New PDF
            </h2>
            <a href="{{ url('pdf/create') }}" class="text-center btn btn-success mb-3 float-right">Create PDF</a>
        </div>

        {!! Form::model($pdf, ['action' => 'PdfsController@store', 'files' => true]) !!}
        <div class="row">
            <div class="form-group col-md-6">
                {!! Form::label('title', 'Title') !!}
                {!! Form::text('title', '', ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('description', 'Description') !!}
                {!! Form::text('description', '', ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('table_content', 'Transaction') !!}
                {!! Form::text('table_content', '', ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('table_description', 'Transaction Description') !!}
                {!! Form::textarea('table_description', '', ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('invoice_title', 'Invoice Title') !!}
                {!! Form::text('invoice_title', '', ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('invoice_number', 'Invoice Number') !!}
                {!! Form::text('invoice_number', '', ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('invoice_image', 'Upload Image') !!}<br>
                {!! Form::file('invoice_image') !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('invoice_description', 'Invoice Description') !!}
                {!! Form::textarea('invoice_description', '', ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('template', 'PDF Templates') !!}
                {!! Form::select('template', $templates, null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('sign', 'Sign') !!}
                {!! Form::text('sign', '', ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-12">
                <button class="btn btn-success" type="submit">Create PDF</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
