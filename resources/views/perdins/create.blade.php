@extends('layouts.main')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Perjalanan Dinas</h1>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Perdin') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('perdins.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="alasan_perdin" class="col-md-4 col-form-label text-md-left">{{ __('Alasan Perdin') }}</label>

                            <div class="col-md-6">
                                <input id="alasan_perdin" type="text" class="form-control @error('alasan_perdin') is-invalid @enderror" name="alasan_perdin" value="{{ old('alasan_perdin') }}" required autocomplete="alasan_perdin" autofocus>

                                @error('alasan_perdin')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="tanggal_berangkat" class="col-md-4 col-form-label text-md-left">{{ __('Tanggal Berangkat') }}</label>

                            <div class="col-md-6">
                                <input id="tanggal_berangkat" type="date" class="form-control @error('tanggal_berangkat') is-invalid @enderror" name="tanggal_berangkat" value="{{ old('tanggal_berangkat') }}" required>

                                @error('tanggal_berangkat')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="tanggal_pulang" class="col-md-4 col-form-label text-md-left">{{ __('Tanggal Pulang') }}</label>

                            <div class="col-md-6">
                                <input id="tanggal_pulang" type="date" class="form-control @error('tanggal_pulang') is-invalid @enderror" name="tanggal_pulang" value="{{ old('tanggal_pulang') }}" required>

                                @error('tanggal_pulang')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="id_lokasi_tujuan" class="col-md-4 col-form-label text-md-left">{{ __('Kota Tujuan') }}</label>

                            <div class="col-md-6">
                                <!-- <input id="country_code" type="text" class="form-control @error('country_code') is-invalid @enderror" name="country_code" value="{{ old('country_code') }}" required autocomplete="country_code" autofocus> -->
                                <select name="id_lokasi_tujuan" id="id_lokasi_tujuan" class="form-control">
                                    @foreach($response as $kota)
                                    <option value="{{$kota['lokasiid']}}">{{$kota['nama']}}</option>
                                    @endforeach
                                </select>
                                @error('id_lokasi_tujuan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection