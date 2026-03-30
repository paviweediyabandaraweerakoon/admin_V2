<div class="form-group">
    @if (isset($editable) && $editable)
        <input type="hidden" name="id" class="id">
    @endif
    @if (!$reset)
        @if (isset($profile_editable) && $profile_editable)
            <div class="row mb-1">
                <div class="col-md-3 text-right mt-2">
                    <label>Role</label>
                </div>
                <div class="col-md-9">
                    {{ $user->roles[0]->name }}
                </div>
            </div>
        @else
            <div class="row mb-1">
                <div class="col-md-3 text-right mt-2">
                    <label>Role</label>
                </div>
                <div class="col-md-9">
                    <select
                        name="roles[]"
                        multiple
                        class="form-control select2-limit w-100 roles"
                        required
                        onchange="changeLandingPages({{ $id }})"
                    >
                        @foreach($roles as $key => $value)
                            <option value="{{ $key }}"
                                {{ collect(old('roles', []))->contains($key) ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
        <div class="row mb-1">
            <div class="col-md-3 text-right mt-2">
                <label>Name</label>
            </div>
            <div class="col-md-9">
                <input type="text" name='name' class="form-control name" required>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-md-3 text-right mt-2">
                <label>Email</label>
            </div>
            <div class="col-md-9">
                <input type="email" name='email' value="" class="form-control email" required>
            </div>
        </div>
        {{-- @can('landing page assign') --}}
            <div class="row mb-1">
                <div class="col-md-3 text-right mt-2">
                    <label>Landing Page</label>
                </div>
                <div class="col-md-9">
                    <select name="landing_page" class="form-control landing_page" required>
                        <option value="" disabled>Select landing page</option>
                    </select>
                </div>
            </div>
        {{-- @endcan --}}
    @endif
    @if (!$editable)
        <div class="row mb-1">
            <div class="col-md-3 text-right mt-2">
                <label>Password</label>
            </div>
            <div class="col-md-9">
                <input type="password" name='password' id="password" class="form-control password"
                    autocomplete="new-password" required>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-md-3 text-right mt-2">
                <label>Confirm Password</label>
            </div>
            <div class="col-md-9">
                <input type="password" name='password_confirmation' id="password_confirm"
                    class="form-control confirm-password" autocomplete="new-password" required>
            </div>
        </div>
    @endif
</div>
