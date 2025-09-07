@extends('admin.components.layout')

@section('title', 'Add New User')

@section('content')

<form>
    <div class="row mt-3">
        <h1>Add a new user</h1>
        <div class="col mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input 
                type="text" 
                class="form-control"
                id="full_name" 
                name="full_name" 
                required
            >
        </div>
        <div class="col mb-3">
            <label for="phone" class="form-label">Phone no</label>
            <input 
                type="number"
                class="form-control"
                id="phone"
                name="phone"
                required
            >
        </div>
        <div class="col mb-3">
            <label for="email" class="form-label">Email address (Optional)</label>
            <input 
                type="email"
                class="form-control"
                id="email"
                name="email"
                required
            >
        </div>
    </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
    
@endsection