@extends('layouts.auth')

@section('title', 'Edit Ticket')

@section('content')
<div class="container-fluid py-3" style="padding-left: 100px;">
    <div class="row">
        <div class="col-12 col-lg-8 col-xl-6 mx-auto">
            <h3 class="mb-3"><i class="fas fa-edit me-2 text-primary"></i>Edit Ticket</h3>
            <a href="{{ route('ceo.tickets.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
                <i class="fas fa-arrow-left me-1"></i>Back
            </a>
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('ceo.tickets.update', $ticket) }}" method="POST" class="border rounded p-4 bg-white shadow-sm">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $ticket->full_name) }}" maxlength="255">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $ticket->email) }}" maxlength="255">
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $ticket->phone) }}" maxlength="20">
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="valid" @if(old('status', $ticket->status)==='valid') selected @endif>Valid</option>
                        <option value="used" @if(old('status', $ticket->status)==='used') selected @endif>Used</option>
                        <option value="cancelled" @if(old('status', $ticket->status)==='cancelled') selected @endif>Cancelled</option>
                    </select>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save</button>
                    <a href="{{ route('ceo.tickets.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 