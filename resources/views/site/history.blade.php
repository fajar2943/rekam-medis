@extends('site.layouts.master')

@section('content')
    <main class="main">

        <!-- Appointment Section -->
        <section id="appointment" class="appointment section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>History</h2>
                <p>Riwayat Pemeriksaan</p>
            </div><!-- End Section Title -->

            <div class="container">

                @if (session('success'))
                    <div role="alert" class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <h3>Profile Pasien</h3>
                                </div>
                                <div class="row">
                                    <div class="col-6">Nama Pasien</div>
                                    <div class="col-6">: {{$user->name}}</div>
                                    <div class="col-6">Email</div>
                                    <div class="col-6">: {{$user->email}}</div>
                                    <div class="col-6">Phone</div>
                                    <div class="col-6">: {{$user->phone}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <h3>Detail Lainya</h3>
                                </div>
                                <div class="row">
                                    <div class="col-6">No.RM</div>
                                    <div class="col-6">: {{$user->rm}}</div>
                                    <div class="col-6">Alamat</div>
                                    <div class="col-6">: {{$user->address}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <div class="card-title">Riwayat Pemeriksaan</div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">ID</th>
                                    <th>Dokter</th>
                                    <th>Keluhan</th>
                                    <th>Catatan</th>
                                    <th>Obat</th>
                                    <th>Status</th>
                                    <th>Tanggal Periksa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($checkups as $checkup)
                                    <tr>
                                        <td>{{$checkup->id}}</td>
                                        <td>{{$checkup->schedule->user->name}}</td>
                                        <td>{{ $checkup->complaint }}</td>
                                        <td>{{ $checkup->note }}</td>
                                        <td>
                                            @foreach ($checkup->details as $detail)
                                                <h6><span class="badge text-bg-secondary">{{$detail->medicine->name}} - {{$detail->qty}} {{$detail->medicine->unit}} - {{$detail->application}}</span></h6>
                                            @endforeach
                                        </td>
                                        <td>{{ $checkup->checkup_status() }}</td>
                                        <td>{{ tglwaktu($checkup->created_at) }}</td>
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

        </section><!-- /Appointment Section -->

    </main>
@endsection