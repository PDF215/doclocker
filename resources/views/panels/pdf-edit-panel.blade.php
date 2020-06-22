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
                Edit PDF
            </h2>
            <a href="{{ url('pdf/index') }}" class="text-center btn btn-success mb-3 float-right">Back</a>
        </div>
        {!! Form::model($pdf, ['action' => 'PdfsController@update']) !!}

        <div class="row">
            <div class="form-group col-md-6 d-none">
                {!! Form::label('id', 'ID') !!}
                {!! Form::text('id', $data["pdf"]["id"], ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('title', 'Title') !!}
                {!! Form::text('title', $data["pdf"]["title"], ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('description', 'Description') !!}
                {!! Form::text('description', $data["pdf"]["description"], ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('table_content', 'Transaction') !!}
                {!! Form::text('table_content', $data["pdf"]["table_content"], ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('table_description', 'Transaction Description') !!}
                {!! Form::textarea('table_description', $data["pdf"]["table_description"], ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('invoice_title', 'Invoice Title') !!}
                {!! Form::text('invoice_title', $data["pdf"]["invoice_title"], ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('invoice_number', 'Invoice Number') !!}
                {!! Form::text('invoice_number', $data["pdf"]["invoice_number"], ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('invoice_description', 'Invoice Description') !!}
                {!! Form::textarea('invoice_description', $data["pdf"]["invoice_description"], ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('template', 'PDF Templates') !!}
                {!! Form::select('template', $templates, $data["pdf"]["title"], ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('sign', 'Sign') !!}
                {!! Form::text('sign', $data["pdf"]["title"], ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-12">
                <button class="btn btn-success" type="submit">Save PDF</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
