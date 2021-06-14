@extends('ticket::layouts.master')
@section('content')
<div class="container">
  <h2>Ticketing Service</h2>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Show Ticket {{$data->id}}</h4>
                    </div>
                    <div class="modal-body">
                            <div class="container-fluid">
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label for="name">Name:</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{$data->name}}" disabled/>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="email">Email:</label>
                                        <input type="email" name="email" id="email" class="form-control" value="{{$data->email}}" disabled/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label for="title">Title:</label>
                                        <input type="text" name="title" id="title" class="form-control"  value="{{$data->title}}" disabled/>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="content">Content:</label>
                                        <textarea type="text" name="content" id="content" class="form-control"  disabled >{{$data->content}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label for="mobile">Attachment:</label>
                                        <a target="_blank" href="{{'storage/uploads/'.$data->attachment}}" class="btn btn-primary" >Download</a>
                                    </div>
                               
                                </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-5">
                                    </div>
                                    <div class="col-lg-5">
                                    </div>
                                    <div class="col-lg-2 ">
                                        <a href="{{route('home')}}"  >Back </button>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
  </div>
@endsection

