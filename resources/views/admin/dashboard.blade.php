@extends('layouts.admin')
@section('title', 'Admin Dashboard') 
@section('content') 
<h2 class="mb-4"> Dashboard </h2> 
<div class="row"> 
      <div class="col-md-4"> 
            <div class="card shadow-sm"> 
                  <div class="card-body"> 
                        <h6 class="text-muted"> Total Students </h6> 
                        <h2 class="mb-0"> {{ $studentCount }} </h2> 
                  </div> 
            </div> 
      </div> 
</div> 
@endsection