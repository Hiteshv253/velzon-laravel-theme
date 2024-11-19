
 
    <div class="input-field col s4">
        <input id="first_name" name="name" type="text" class="validate"
               @if(isset($user)) value="{{ $user->name }}" @endif required>
        <label for="first_name">Full Name</label>
    </div>
    <div class="input-field col s4">
        <input id="email" name="email" type="email" class="validate"
               @if(isset($user)) value="{{ $user->email }}" @endif required>
        <label for="email">Email</label>
    </div>
    <div class="input-field col s4">
        <input id="password" type="password" name="password" class="validate" minlength="8" required="" data-error=".errorTxt1" value="Password@1234">
        <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
        <label for="password">Password</label>
        <div class="errorTxt1"></div>
    </div>
    <div class="input-field col s6">
        <select class="select2 browser-default" multiple="multiple" name="roles[]">
            <optgroup label="Roles">
                @foreach($roles as $role)
                @if($role->name !== 'Admin')
                <option value="{{ $role->name }}"
                        @if(isset($user)) @if($user->hasRole($role->name)) selected @endif @endif>
                    {{ $role->name }}
                </option>
                @endif
                @endforeach
            </optgroup>

        </select>
        <label>Roles</label>
    </div>
 
