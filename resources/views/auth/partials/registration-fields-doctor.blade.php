<!-- Doctor-specific registration fields -->
<div class="row clearfix">
    <div class="col-sm-12">
        <h4 class="mt-4">Professional Information</h4>
        <hr>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <input type="text" name="license_number" class="form-control" placeholder="Medical License Number" value="{{ old('license_number') }}" required>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <select name="specialization_id" class="form-control show-tick" required>
                <option value="">-- Select Specialization --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('specialization_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <select name="department_id" class="form-control show-tick" required>
                <option value="">-- Select Department --</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <input type="text" name="medical_school" class="form-control" placeholder="Medical School" value="{{ old('medical_school') }}">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <input type="text" name="residency" class="form-control" placeholder="Residency" value="{{ old('residency') }}">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <input type="text" name="fellowship" class="form-control" placeholder="Fellowship" value="{{ old('fellowship') }}">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <input type="number" name="years_of_experience" class="form-control" placeholder="Years of Experience" value="{{ old('years_of_experience') }}">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <textarea name="bio" rows="4" class="form-control no-resize" placeholder="Biography">{{ old('bio') }}</textarea>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-sm-12">
        <h4 class="mt-4">Personal Information</h4>
        <hr>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <input type="date" name="date_of_birth" class="form-control" placeholder="Date of Birth" value="{{ old('date_of_birth') }}" required>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <select name="gender" class="form-control show-tick" required>
                <option value="">-- Gender --</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <input type="text" name="address" class="form-control" placeholder="Address" value="{{ old('address') }}" required>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <input type="text" name="city" class="form-control" placeholder="City" value="{{ old('city') }}" required>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <input type="text" name="state" class="form-control" placeholder="State" value="{{ old('state') }}" required>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <input type="text" name="zip_code" class="form-control" placeholder="Zip Code" value="{{ old('zip_code') }}" required>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <input type="text" name="country" class="form-control" placeholder="Country" value="{{ old('country') }}" required>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-sm-12">
        <h4 class="mt-4">Profile Image & Proof of Identity</h4>
        <hr>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label>Profile Image</label>
            <input type="file" name="photo" class="form-control-file">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label>Proof of Identity</label>
            <input type="file" name="proof_of_identity" class="form-control-file">
        </div>
    </div>
</div>