<!-- Default registration fields for roles without specific requirements -->
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