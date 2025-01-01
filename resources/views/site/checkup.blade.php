@extends('site.layouts.master')

@section('content')
    <main class="main">

        <!-- Appointment Section -->
        <section id="appointment" class="appointment section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Checkup Detail</h2>
                {{-- <p></p> --}}
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                @if (session('success'))
                    <div role="alert" class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('failed'))
                    <div role="alert" class="alert alert-danger">{{ session('failed') }}</div>
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <h3>Detail Pemeriksaan</h3>
                                </div>
                                <div class="row">
                                    <div class="col-6">No.RM</div>
                                    <div class="col-6">: {{$checkup->user->rm}}</div>
                                    <div class="col-6">Nama Pasien</div>
                                    <div class="col-6">: {{$checkup->user->name}}</div>
                                    <div class="col-6">Nama Dokter</div>
                                    <div class="col-6">: {{$checkup->schedule->user->name}}</div>
                                    <div class="col-6">Tanggal</div>
                                    <div class="col-6">: {{tanggal($checkup->created_at)}}</div>
                                    <div class="col-6">Status</div>
                                    <div class="col-6">: {{$checkup->checkup_status()}}</div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-6">Keluhan</div>
                                    <div class="col-6">: {{$checkup->complaint}}</div>
                                </div>
                                @if ($checkup->note)
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">Catatan</div>
                                        <div class="col-6">: {{$checkup->note}}</div>
                                    </div>
                                @endif
                                @if ($checkup->checkup_price)
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">Biaya Periksa</div>
                                        <div class="col-6">: {{rupiah($checkup->checkup_price)}}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if (authAs('doctor') && $checkup->status == 'checkup')
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <h3>Diagnosis</h3>
                                    </div>
                                    <form action="/checkup/{{$checkup->id}}" method="post">
                                        @method('put')
                                        @csrf
                                        <div class="form-group">
                                            <label for="note">Catatan</label>
                                            <textarea name="note" class="form-control @error('note') is-invalid @enderror"></textarea>
                                            @error('note')
                                                <div class="invalid-feedback">{{$message}}</div>								
                                            @enderror
                                        </div>
                                        <div class="form-group mt-3">
                                            <label for="medicine">Obat</label>
                                            <select id="medicine" class="form-select">
                                                <option value="">-- PILIH OBAT --</option>
                                                @foreach ($medicines as $medicine)
                                                    <option value="{{$medicine->name}}" data-id="{{$medicine->id}}">{{$medicine->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div id="medicines" class="mt-3">
                                            
                                        </div>
                                        
                                        <div class="mt-5">
                                            <div class="text-center"><button class="btn btn-primary" type="submit">Submit</button></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>                        
                    @endif
                    @if ($checkup->status != 'checkup')
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <h3>OBAT</h3>
                                    </div>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Nama Obat</th>
                                                <th>Aturan Pakai</th>
                                                <th>Harga</th>
                                                <th>QTY</th>
                                                <th>Subtotal Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($checkup->details as $detail)
                                                <tr>
                                                    <td>{{$detail->medicine->name}}</td>
                                                    <td>{{$detail->application}}</td>
                                                    <td>{{$detail->price}}</td>
                                                    <td>{{$detail->qty}}</td>
                                                    <td>{{rupiah($detail->qty * $detail->price)}}</td>
                                                </tr>                                                
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>                        
                    @endif
                </div>
                @if (authAs('doctor'))
                    <div class="text-center mt-5">
                        <a href="/history/{{$checkup->user->id}}" class="btn btn-secondary">Lihat Riwayat Pemeriksaan</a>
                    </div>
                @endif

                @if ($checkup->total_price && !authAs('patient'))
                <div class="text-center mt-5">                   
                    <h2 class="mb-5">Total Biaya <span class="badge text-bg-secondary">{{rupiah($checkup->total_price)}}</span></h2>
                    @if ($checkup->status == 'waiting_medicine')
                        <form action="/checkup/status/{{$checkup->id}}" method="post">
                            @csrf
                            @method('put')
                            <input type="hidden" name="status" value="done">
                            <button type="submit" class="btn btn-primary">Klik disini untuk Selesai</button>
                        </form>
                    @elseif($checkup->status == 'done') 
                        <div class="text-success">Selesai</div>                       
                    @elseif($checkup->status == 'canceled') 
                        <div class="text-danger">Dibatalkan</div>                       
                    @endif
                </div>
                @endif
                @if ((!authAs('patient') && in_array($checkup->status, ['checkup', 'waiting_medicine'])) or (authAs('patient') && $checkup->status == 'checkup'))  
                    <div class="text-center mt-3">
                        <form action="/checkup/status/{{$checkup->id}}" method="post">
                            @csrf
                            @method('put')
                            <input type="hidden" name="status" value="canceled">
                            <button type="submit" class="btn btn-danger">Klik disini untuk Cancel</button>
                        </form>                        
                    </div>                  
                @endif
            </div>

        </section><!-- /Appointment Section -->

    </main>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('#medicine').on('change', function(){
                addMedicine();
            });
            $('#medicines').on('click','.delete_medicine', function(){
                $(this).parent().parent().remove();
            });
        });
        function addMedicine(){
            const name = $('#medicine').val();
            const id = $('#medicine').find(':selected').data('id');
            if(name != ''){
                $('#medicines').append(`
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>${name}</label>
                                <input type="text" name="application[]" class="form-control" value="" placeholder="Aturan Pakai">
                                <input type="hidden" name="medicine_id[]" value="${id}">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>QTY</label>
                                <input type="number" name="qty[]" class="form-control" value="" placeholder="qty">
                            </div>
                        </div>
                        <div class="col-3 pt-4">
                            <a href="#" class="btn btn-danger delete_medicine">Hapus</a>
                        </div>
                    </div>
                `)
            }
        }
    </script>
@endsection
