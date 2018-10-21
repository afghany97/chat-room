@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">

                    <input type="text" v-model="name" @blur="save">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
