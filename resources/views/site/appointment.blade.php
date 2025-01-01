@extends('site.layouts.master')

@section('content')
    <main class="main">

        <!-- Appointment Section -->
        <section id="appointment" class="appointment section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Appointment</h2>
                <p>informasi riwayat pemeriksaan</p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <h3>Medical Checkup</h3>
                                </div>
                                <form action="/appointment" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="rm">No.Rekam Medis</label>
                                        <input type="text" name="rm" class="form-control @error('rm') is-invalid @enderror" id="rm" disabled
                                            placeholder="No.Rekam Medis" required="" value="{{auth()->user()->rm}}">
                                        @error('rm')
                                            <div class="invalid-feedback">{{$message}}</div>								
                                        @enderror
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="complaint">Complaint / Keluhan</label>
                                        <textarea class="form-control @error('complaint') is-invalid @enderror" name="complaint" id="complaint">{{old('complaint')}}</textarea>
                                        @error('complaint')
                                            <div class="invalid-feedback">{{$message}}</div>								
                                        @enderror
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="poly_id">Pilih Poli</label>
                                        <select name="poly_id" id="poly_id" class="form-select">
                                            @foreach ($polies as $poly)
                                                <option value="{{$poly->id}}" {{$poly->id == old('poly_id') ? 'selected' : ''}}>{{$poly->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="schedule">Pilih Jadwal Periksa</label>
                                        <select name="schedule" id="schedule" class="form-select @error('schedule') is-invalid @enderror">
                                            
                                        </select>
                                        @error('schedule')
                                            <div class="invalid-feedback">{{$message}}</div>								
                                        @enderror
                                    </div>
                                    <div class="mt-3">
                                        <div class="text-center"><button class="btn btn-primary" type="submit">Submit</button></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
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
                                                      <option value="checkup" @if(request('statuses') == 'checkup') selected @endif>Pemeriksaan</option>
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
                                            <th>Dokter</th>
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
                                                <td>{{$checkup->schedule->user->name}}</td>
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
<script>
    schedules();
    $('#poly_id').on('change', function(){
        schedules();
    })

    $('#statuses').on('change', function(){
        $('#formSearch').submit();
    })

    function schedules(){
        const poly_id = $('#poly_id').val();
        const schedule_id = `{{old('schedule')}}`
        $('#schedule').empty();
        $('#schedule').append(`<option value="">-- PILIH JADWAL --</option>`);
        $.get("/api/schedules/" + poly_id, function(data, status){
            data.forEach(schedule => {
                $('#schedule').append(`<option value="${schedule.id}" ${schedule.id == schedule_id ? 'selected' : ''}>Dokter ${schedule.name} | ${schedule.day} | ${schedule.start} - ${schedule.finish}</option>`);
            });
            
        });
        
    }
</script>
@endsection
