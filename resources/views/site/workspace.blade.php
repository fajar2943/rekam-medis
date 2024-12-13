@extends('site.layouts.master')

@section('content')
    <main class="main">

        <!-- Appointment Section -->
        <section id="appointment" class="appointment section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Workspace</h2>
                <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
            </div><!-- End Section Title -->

            <div class="container">

                @if (session('success'))
                    <div role="alert" class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                {{-- <h3 class="card-title"><button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal">Add User</button></h3> --}}
                                <div class="card-tools">
                                    <form action="" method="get" id="formSearch">
                                      <div class="row">
                                        <div class="col-md-6">                                          
                                          <div class="row">
                                            <div class="col-3">
                                              <div class="input-group input-group-sm" style="width: 150px;">
                                                  <select name="statuses" class="form-select" id="statuses">
                                                      <option value="">All Status</option>
                                                      <option value="waiting_checkup" @if(request('statuses') == 'waiting_checkup') selected @endif>Waiting Checkup</option>
                                                      <option value="checkup" @if(request('statuses') == 'checkup') selected @endif>Checkup</option>
                                                      <option value="waiting_medicine" @if(request('statuses') == 'waiting_medicine') selected @endif>Waiting Medicine</option>
                                                      <option value="done" @if(request('statuses') == 'done') selected @endif>Done</option>
                                                      <option value="canceled" @if(request('statuses') == 'canceled') selected @endif>Canceled</option>
                                                  </select>
                                              </div>
                                            </div>
                                            <div class="col-3">
                                              <div class="input-group input-group-sm" style="width: 150px;">
                                                  <input type="search" name="search" value="{{ request('search') }}"
                                                      id="search" class="form-control" placeholder="Cari">
                                                  <div class="input-group-append">
                                                      <button type="submit" class="btn btn-default">
                                                          <i class="fas fa-search"></i>
                                                      </button>
                                                  </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-md-6">
                                          {{-- kosong --}}
                                        </div>
                                      </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Name</th>
                                            <th>Schedule</th>
                                            <th>Complaint</th>
                                            <th>Status</th>
                                            <th>Checkup Price</th>
                                            <th>Total Price</th>
                                            <th>Note</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($checkups as $checkup)
                                            <tr>
                                                <td>{{$checkup->id}}</td>
                                                <td>{{ $checkup->name }}</td>
                                                <td>{{ $checkup->schedule->day }} {{ $checkup->schedule->start }} - {{ $checkup->schedule->finish }}</td>
                                                <td>{{ $checkup->complaint }}</td>
                                                <td>{{ $checkup->status }}</td>
                                                <td>{{ $checkup->price }}</td>
                                                <td>{{ $checkup->total_price }}</td>
                                                <td>{{ $checkup->note }}</td>
                                                <td>{{ tglwaktu($checkup->created_at) }}</td>
                                                <td>
                                                    <a class="btn btn-sm btn-warning mb-2" data-toggle="modal" data-target="#editModal{{$checkup->id}}"><i class="fa fa-pen"></i></a>
                                                    <a class="btn btn-sm btn-danger mb-2" data-toggle="modal" data-target="#deleteModal{{$checkup->id}}"><i class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>

                                            <!-- Modal Edit-->
                                            {{-- <div class="modal fade" id="editModal{{$checkup->id}}" tabindex="-1" aria-labelledby="editModal{{$checkup->id}}Label" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="editModal{{$checkup->id}}Label">Edit {{ucfirst($checkup->role)}}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                  <form action="/admin/users/{{$checkup->id}}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                      <div class="deadline-form">
                                                        <div class="row g-3 mb-3">
                                                            <div class="col-sm-12">
                                                                <label for="role" class="form-label">Role</label>
                                                                <select name="role" id="role" class="form-control">
                                                                    @php
                                                                        $role = (old('id') == $checkup->id) ? old('role') : $checkup->role;
                                                                    @endphp
                                                                    <option value="admin" @if('admin' == $role) selected @endif>Admin</option>
                                                                    <option value="doctor" @if('doctor' == $role) selected @endif>Doctor</option>
                                                                    <option value="pharmacist" @if('pharmacist' == $role) selected @endif>Pharmacist/Apoteker</option>
                                                                    <option value="patient" @if('patient' == $role) selected @endif>Patient</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row g-3 mb-3">
                                                          <div class="col-sm-12">
                                                            <label for="name{{$checkup->id}}" class="form-label">Name</label>
                                                            <input type="text" name="name" class="form-control @if(old('id') == $checkup->id) @error('name') is-invalid @enderror @endif" id="name{{$checkup->id}}" value="@if(old('id') == $checkup->id){{old('name')}}@else{{$checkup->name}}@endif">
                                                            @if(old('id') == $checkup->id)
                                                                @error('name')
                                                                    <div class="invalid-feedback">{{$message}}</div>
                                                                @enderror
                                                            @endif								
                                                          </div>
                                                        </div>
                                                        <div class="row g-3 mb-3">
                                                          <div class="col-sm-12">
                                                            <label for="email{{$checkup->id}}" class="form-label">Email</label>
                                                            <input type="email" name="email" class="form-control @if(old('id') == $checkup->id) @error('email') is-invalid @enderror @endif" id="email{{$checkup->id}}" value="@if(old('id') == $checkup->id){{old('email')}}@else{{$checkup->email}}@endif">
                                                            @if(old('id') == $checkup->id)
                                                                @error('email')
                                                                    <div class="invalid-feedback">{{$message}}</div>
                                                                @enderror
                                                            @endif								
                                                          </div>
                                                        </div>
                                                        <div class="row g-3 mb-3">
                                                          <div class="col-sm-12">
                                                            <label for="phone{{$checkup->id}}" class="form-label">Phone</label>
                                                            <input type="text" name="phone" class="form-control @if(old('id') == $checkup->id) @error('phone') is-invalid @enderror @endif" id="phone{{$checkup->id}}" value="@if(old('id') == $checkup->id){{old('phone')}}@else{{$checkup->phone}}@endif">
                                                            @if(old('id') == $checkup->id)
                                                                @error('phone')
                                                                    <div class="invalid-feedback">{{$message}}</div>
                                                                @enderror
                                                            @endif								
                                                          </div>
                                                        </div>
                                                        <div class="row g-3 mb-3">
                                                          <div class="col-sm-12">
                                                            <label for="id_number{{$checkup->id}}" class="form-label">ID Number</label>
                                                            <input type="text" name="id_number" class="form-control @if(old('id') == $checkup->id) @error('id_number') is-invalid @enderror @endif" id="id_number{{$checkup->id}}" value="@if(old('id') == $checkup->id){{old('id_number')}}@else{{$checkup->id_number}}@endif">
                                                            @if(old('id') == $checkup->id)
                                                                @error('id_number')
                                                                    <div class="invalid-feedback">{{$message}}</div>
                                                                @enderror
                                                            @endif								
                                                          </div>
                                                        </div>
                                                        <div class="row g-3 mb-3">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="address{{$checkup->id}}">Address</label>
                                                                    <textarea name="address" class="form-control @if(old('id') == $checkup->id) @error('address') is-invalid @enderror @endif" id="address" rows="2">@if(old('id') == $checkup->id){{old('address')}}@else{{$checkup->address}}@endif</textarea>
                                                                    @if(old('id') == $checkup->id)
                                                                        @error('address')
                                                                            <div class="invalid-feedback">{{$message}}</div>
                                                                        @enderror
                                                                    @endif								
                                                                </div>
                                                            </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                      <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                  </form>
                                                </div>
                                              </div>
                                            </div> --}}
                      
                                            <!-- Modal Delete-->
                                            {{-- <div class="modal fade" id="deleteModal{{$checkup->id}}" tabindex="-1" aria-labelledby="deleteModal{{$checkup->id}}Label" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModal{{$checkup->id}}Label">Hapus {{ucfirst($checkup->name)}}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <p>Apakah anda yakin ingin menghapus akun tersebut?</p>
                                                  </div>
                                                  <form action="/admin/users/{{$checkup->id}}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-footer">
                                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                      <button type="submit" class="btn btn-primary">Hapus</button>
                                                    </div>
                                                  </form>                            
                                                </div>
                                              </div>
                                            </div> --}}

                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">
                                {{ $checkups->links('admin.layouts.paginate') }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </section><!-- /Appointment Section -->

    </main>
@endsection

@section('js')
<!-- jQuery -->
{{-- <script src="{{asset('AdminLTE/plugins/jquery/jquery.min.js')}}"></script> --}}
    
<script>
	$(document).ready(function() {
		@if(old('id'))
			$('#editModal' + {!! old('id') !!}).modal('show')
		@endif

        $('#statuses').on('change', function(){
            $('#formSearch').submit();
        })
	});
</script>
@endsection
