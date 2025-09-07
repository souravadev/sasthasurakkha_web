@extends('admin.components.layout')

@section('title', 'Add New Doctor')

@section('content')

<form id="add_new_doctor">
    <div class="row mt-3">
        <h1>Add a new doctor</h1>
        <div class="col mb-3">
            <label for="user_id" class="form-label">User Id</label>
            <input 
                type="number" 
                class="form-control"
                id="user_id" 
                name="user_id" 
                required
            >
        </div>
        <div class="col mb-3">
            <label for="phone" class="form-label">Work Phone no</label>
            <input 
                type="number"
                class="form-control"
                id="phone"
                name="phone"
                required
            >
        </div>
        <div class="col mb-3">
            <label for="email" class="form-label">Work Email address (Optional)</label>
            <input 
                type="email"
                class="form-control"
                id="email"
                name="email"
                required
            >
        </div>
        <div class="row mt-3">
            <div class="col mb-3">
                <label for="designation" class="form-label">Designation</label>
                <input 
                    type="text"
                    class="form-control"
                    id="designation"
                    name="designation"
                    required
                >
            </div>
            <div class="col mb-3">
                <label for="work_experience" class="form-label">Work Experience</label>
                <input 
                    type="date"
                    class="form-control"
                    id="work_experience"
                    name="work_experience"
                    required
                >
            </div>
        </div>
    </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>


@endsection