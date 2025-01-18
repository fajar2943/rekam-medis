@extends('site.layouts.master')

@section('content')
    <main class="main">

        <!-- Appointment Section -->
        <section id="appointment" class="appointment section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Workspace</h2>
                <p>Ruang Kerja</p>
            </div><!-- End Section Title -->

            <div class="container">

                @if (session('success'))
                    <div role="alert" class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('failed'))
                    <div role="alert" class="alert alert-danger">{{ session('failed') }}</div>
                @endif

                <div class="row">
                  <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <h3>Input Jadwal Baru</h3>
                            </div>
                            <form action="/schedule" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="poly_id">Poly</label>
                                    <select name="poly_id" id="poly_id" class="form-select">
                                      @foreach ($polies as $poly)
                                        <option value="{{$poly->id}}">{{$poly->name}}</option>                                          
                                      @endforeach
                                    </select>
                                    @error('poly_id')
                                        <div class="invalid-feedback">{{$message}}</div>								
                                    @enderror
                                </div>
                                <div class="form-group mt-3">
                                    <label for="day">Hari</label>
                                    <select name="day" id="day" class="form-select">
                                      <option value="monday">senin</option>
                                      <option value="tuesday">selasa</option>
                                      <option value="wednesday">rabu</option>
                                      <option value="thursday">kamis</option>
                                      <option value="friday">jumat</option>
                                      <option value="saturday">sabtu</option>
                                      <option value="sunday">minggu</option>
                                    </select>
                                    @error('day')
                                        <div class="invalid-feedback">{{$message}}</div>								
                                    @enderror
                                </div>
                                <div class="form-group mt-3">
                                    <label for="start">Start / Jam Mulai</label>
                                    <input type="time" class="form-control @error('start') is-invalid @enderror" name="start" id="start" required=""
                                     value="{{old('start') ?? auth()->user()->start}}">
                                    @error('start')
                                        <div class="invalid-feedback">{{$message}}</div>								
                                    @enderror
                                </div>
                                <div class="form-group mt-3">
                                    <label for="finish">Finish / Jam Selesai</label>
                                    <input type="time" class="form-control @error('finish') is-invalid @enderror" name="finish" id="finish" required=""
                                     value="{{old('finish') ?? auth()->user()->finish}}">
                                    @error('finish')
                                        <div class="invalid-feedback">{{$message}}</div>								
                                    @enderror
                                </div>
                                <div class="mt-3">
                                    <div class="text-start"><button class="btn btn-primary" type="submit">Submit</button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <div class="card">
                      <div class="card-header">Jadwal Praktek Anda</div>
                      <div class="card-body table-responsive p-0">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th>Poly</th>
                              <th>Day</th>
                              <th>Start</th>
                              <th>Finish</th>
                              <th>Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($schedules as $schedule)
                              <tr>
                                <td>{{$schedule->poly->name}}</td>
                                <td>{{$schedule->hari()}}</td>
                                <td>{{waktu($schedule->start)}}</td>
                                <td>{{waktu($schedule->finish)}}</td>
                                <td>
                                  <form action="/schedule/switch-status" method="get" id="formStatus{{$schedule->id}}">
                                    <input type="hidden" name="id" value="{{$schedule->id}}">
                                    <div class="form-check form-switch">
                                      <input class="form-check-input switchStatus" type="checkbox" name="status" data-id="{{$schedule->id}}" role="switch" {{$schedule->status ? 'checked' : ''}}>
                                    </div>
                                  </form>
                                </td>
                              </tr>                                
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <div class="card-title">Daftar Pemeriksaan</div>
                            <div class="card-tools">
                                <form action="" method="get" id="formSearch">
                                  <div class="row">
                                    <div class="col-md-6">                                          
                                      <div class="row">
                                        <div class="col-3">
                                          <div class="input-group input-group-sm" style="width: 150px;">
                                              <select name="statuses" class="form-select" id="statuses">
                                                  <option value="all">All Status</option>
                                                  <option value="checkup" @if(request('statuses') == 'checkup' or !request('statuses')) selected @endif>Pemeriksaan</option>
                                                  <option value="waiting_medicine" @if(request('statuses') == 'waiting_medicine') selected @endif>Menunggu Obat</option>
                                                  <option value="done" @if(request('statuses') == 'done') selected @endif>Selesai</option>
                                                  <option value="canceled" @if(request('statuses') == 'canceled') selected @endif>Dibatalkan</option>
                                              </select>
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
                                        <th style="width: 10px">ID</th>
                                        <th>Pasien</th>
                                        <th>Schedule</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($checkups as $checkup)
                                        <tr>
                                            <td>{{$checkup->id}}</td>
                                            <td>{{$checkup->user->name}}</td>
                                            <td>{{ $checkup->schedule->hari() }} {{ waktu($checkup->schedule->start) }} - {{ waktu($checkup->schedule->finish) }}</td>
                                            <td>{{ $checkup->checkup_status() }}</td>
                                            <td>{{ tglwaktu($checkup->created_at) }}</td>
                                            <td>
                                                <a href="/checkup/{{$checkup->id}}" class="btn btn-sm btn-warning mb-2">Detail</a>
                                            </td>
                                        </tr>

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

    $('.switchStatus').on('change', function(){
      $('#formStatus' + $(this).data('id')).submit();
    })
	});
</script>
@endsection
