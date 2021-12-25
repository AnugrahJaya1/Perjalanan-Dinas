@extends('layouts.main')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Perjalanan Dinas</h1>
</div>

<div class="row">
    <div class="card mx-auto">
        <div>
            @if(session()->has('message'))
            <div class="alert alert-success">
                {{session('message')}}
            </div>
            @endif
        </div>
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <form method="GET" action="{{route('perdins.index')}}">
                        <div class="form-row align-items-center">
                            <div class="col">
                                <input type="search" name="search" class="form-control mb-2" id="inlineFormInput">
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary mb-2">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div>
                    <a href="{{route('perdins.create')}}" class="float-right btn btn-primary mb-2">Create</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Tujuan Perdin</th>
                        <th scope="col">Tanggal Berangkat</th>
                        <th scope="col">Tanggal Pulang</th>
                        <th scope="col">Kota Awal</th>
                        <th scope="col">Kota Tujuan</th>
                        <th scope="col">Durasi (Hari)</th>
                        <th scope="col">Status</th>
                        @if($pegawai['unitkerja']=='SDM')
                        <th scope="col">Total Uang Saku</th>
                        <th scope="col">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    ?>
                    @foreach($perdins as $perdin)
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$perdin->alasan_perdin}}</td>
                        <td>{{$perdin->tanggal_berangkat}}</td>
                        <td>{{$perdin->tanggal_pulang}}</td>
                        <td>{{$perdin->lokasi_awal}}</td>
                        <td>{{$perdin->lokasi_tujuan}}</td>
                        <td>{{$perdin->durasi}}</td>
                        <td>
                            @if($perdin->status)
                            <span class="badge badge-success">Approve</span>
                            @else
                            <span class="badge badge-secondary">Wait</span>
                            @endif
                        </td>
                        @if($pegawai['unitkerja']=='SDM')
                        <td>{{number_format($perdin->durasi*$perdin->uang_saku,2)}}</td>
                        <td>
                            <div class="btn-group">
                                <form method="POST" action="{{route('perdins.update', $perdin->id)}}">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-success" @if($perdin->status) disabled @endif>
                                        Approve
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    <?php
                    $i++;
                    ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection