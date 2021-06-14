@extends('ticket::layouts.master')
@section('content')
<div class="container">
    <h2>Ticketing Service</h2>
    <p>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Register Ticket</h4>
                </div>
                <div class="modal-body">
                    @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger">{{$error}}</div>
                    @endforeach
                    @endif
                    @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                    @endif
                    <form id="ticket_add" enctype="multipart/form-data"
                        action="{{ route('store_ticket') }}" method="POST">
                        <div class="container-fluid">
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label for="name">Name:</label>
                                    @csrf
                                    <input type="text" name="name" id="name" class="form-control" required
                                          value="{{old('name')}}"/>
                                </div>
                                <div class="col-lg-6">
                                    <label for="email">Email:</label>
                                    <input type="email" name="email" id="email" class="form-control" required
                                         value="{{old('email')}}"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label for="title">Title:</label>
                                    <input type="text" name="title" id="title" class="form-control" required
                                         value="{{old('title')}}"/>
                                </div>
                                <div class="col-lg-6">
                                    <label for="content">Content:</label>
                                    <textarea  name="content" id="content" class="form-control" required
                                        > {{old('content')}}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label for="mobile">Attachment:</label>
                                    <input type="file" name="attachment" id="attachment" class="form-control"
                                         />
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-5">
                                    </div>
                                    <div class="col-lg-5">
                                    </div>
                                    <div class="col-lg-2 ">
                                        <button type="submit" class="btn btn-lg btn-success mr-2 float-right">Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </p>
    <hr>
    <table class="table" id="tickets">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Title</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->title }}</td>
                    <td>
                        <a class="btn btn-small btn-success"
                            href="{{ url('ticket/' . $item->id) }}">Show</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script type="text/javascript" src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/dataTable.min.js') }}"></script>
<script src="{{ asset('assets/js/dataTables.bootstrap.js') }}"></script>
<script>
    $(function () {
        $('#tickets').DataTable();
    });

</script>
</div>
@endsection
