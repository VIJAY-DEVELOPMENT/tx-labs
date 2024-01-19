<fieldset>
    <form action="{{ (!empty($user)) ? route('admin.users.update',['user' => $user->id]) : route('admin.users.store') }}" method="POST" id="user-form">
        @csrf
        @if (!empty($user))
            @method('PUT')
        @endif
        <div class="row mb-3">
            <div class="col-md-12 form-group form-group">
                <label for="name" class="col-form-label">{{ __('Name') }}</label>
                <input id="name" type="text" class="form-control" name="name" value="{{ (!empty($user->name)) ? $user->name : '' }}" autocomplete="name" autofocus>
                <span class="text-danger errors" id="nameerror"></span>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6 form-group">
                <label for="email" class="col-form-label">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="form-control" value="{{ (!empty($user->email)) ? $user->email : '' }}" name="email"  autocomplete="email">
                <span class="text-danger errors" id="emailerror"></span>
            </div>
            <div class="col-md-6 form-group">
                <label for="phone_no" class="col-form-label">{{ __('Phone Number') }}</label>
                <input id="phone_no" type="text" class="form-control numericonly"  value="{{ (!empty($user->phone_no)) ? $user->phone_no : '' }}" name="phone_no">
                <span class="text-danger errors" id="phone_noerror"></span>
            </div>
        </div>

        @if(empty($user))
            <div class="row mb-3">
                <div class="col-md-6 form-group">
                    <label for="password" class="col-form-label">{{ __('Password') }}</label>
                    <input id="password" type="password" class="form-control" name="password" autocomplete="new-password">
                    <span class="text-danger errors" id="passworderror"></span>
                </div>
                <div class="col-md-6 form-group">
                    <label for="password-confirm" class="col-form-label">{{ __('Confirm Password') }}</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                </div>
            </div>
        @endif

        <div class="row text-end">
            <div class="col-md-12 form-group">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</fieldset>