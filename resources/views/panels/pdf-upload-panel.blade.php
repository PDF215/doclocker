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
                Upload New PDF
            </h2>
            <a href="{{ url('pdf/index') }}" class="text-center btn btn-success mb-3 float-right">Back</a>
        </div>

        <form id="file-upload-form" class="uploader" method="post" action="/pdf/email_send"
              enctype="multipart/form-data">
            @csrf
            <div>
                <label for="file-upload" id="file-drag">
                    <input id="file-upload" type="file" name="pdfUpload"/>
                    <img id="file-image" src="#" alt="Preview" class="hidden">
                    <div id="start">
                        <i class="fa fa-download" aria-hidden="true"></i>
                        <div>Select a file or drag here</div>
                        <div id="notimage" class="hidden">Please select an image</div>
                        <span id="file-upload-btn" class="btn btn-primary">Select a PDF</span>
                    </div>
                    <div id="response" class="hidden">
                        <div id="messages"></div>
                        <progress class="progress" id="file-progress" value="0">
                            <span>0</span>%
                        </progress>
                    </div>
                </label>
            </div>
            <div>
                <input type="email" name="email" id="email_input" placeholder="Insert Email">
            </div>
            <input class="btn button-success" type="submit" value="SEND">
        </form>
    </div>
</div>
