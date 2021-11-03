@extends('layouts.app2')


@section('location')
{{$title}}
@endsection

@section('content')
    <!-- Display All Data -->
    <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <iframe src="/filemanager" style="width: 100%; height: 900px; overflow: hidden; border: none;"></iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- End Display All Data -->
@endsection

@section('js')

@endsection
