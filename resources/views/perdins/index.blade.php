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
            @if(session()->has('error'))
            <div class="alert alert-danger">
                {{session('error')}}
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
                    <tr class="text-center">
                        <th scope="col">No.</th>
                        @if(Cookie::get('unitKerja')=='SDM')
                        <th scope="col">Nama</th>
                        @endif
                        <th scope="col">Tujuan Perdin</th>
                        <th scope="col">Tanggal Berangkat</th>
                        <th scope="col">Tanggal Pulang</th>
                        <th scope="col">Kota Awal</th>
                        <th scope="col">Kota Tujuan</th>
                        <th scope="col">Durasi (Hari)</th>
                        @if(Cookie::get('unitKerja')=='SDM')
                        <th scope="col">Total Uang Saku</th>
                        @endif
                        <th scope="col">Status</th>
                        @if(Cookie::get('unitKerja')=='SDM')
                        <th scope="col">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    ?>
                    @foreach($perdins as $perdin)
                    <tr @if($perdin->status!=0)
                            class="table-info"
                        @endif
                    >
                        <td>{{$i}}</td>
                        @if(Cookie::get('unitKerja')=='SDM')
                        <td>{{$perdin->nama_pegawai}}</td>
                        @endif
                        <td>{{$perdin->tujuan_perdin}}</td>
                        <td>{{$perdin->tanggal_berangkat}}</td>
                        <td>{{$perdin->tanggal_pulang}}</td>
                        <td>{{$perdin->lokasi_awal}}</td>
                        <td>{{$perdin->lokasi_tujuan}}</td>
                        <td class="text-center">{{$perdin->durasi}}</td>
                        
                        @if(Cookie::get('unitKerja')=='SDM')
                        <td>{{number_format($perdin->durasi*$perdin->uang_saku,2)}}</td>
                        @endif
                        <td class="text-center">
                            @if($perdin->status==1)
                            <span class="badge badge-success">Approved</span>
                            @elseif($perdin->status==2)
                            <span class="badge badge-danger">Rejected</span>
                            @else
                            <span class="badge badge-secondary">Please Wait</span>
                            @endif
                        </td>
                        @if(Cookie::get('unitKerja')=='SDM')
                        <td class="text-center">
                            <div class="btn-group">
                                <form method="POST" action="{{route('perdins.update', $perdin->id)}}" class="mr-2">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-success btn-sm" name='btn' value='approve' @if($perdin->status!=0) disabled @endif>
                                        Approve
                                    </button>
                                </form>
                                <form method="POST" action="{{route('perdins.update', $perdin->id)}}">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-danger btn-sm" name='btn' value='reject' @if($perdin->status!=0) disabled @endif>
                                        Reject
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