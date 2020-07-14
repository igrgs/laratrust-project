@extends('layouts.app')

@section('content')
<section class="content-header">
    <h1>
        User
    </h1>
</section>
<div class="content">
    @include('adminlte-templates::common.errors')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                {!! Form::model($user, ['route' => ['administracao.users.update', $user->id], 'method' => 'patch']) !!}

                @include('administracao.users.fields')

                <div class="form-group col-sm-6">
                    {!! Form::label('team', 'Teams') !!}
                    {!! Form::select('team', $teams, ['class' => 'form-control']) !!}
                </div>


                <div class="col-md-6">

                    @component('box', ['color' => 'teal', 'title' =>'User Roles'])
                    <table class="table table-sm table-hover">
                        @foreach($roles as $role)
                        @if($role->name !== 'superadministrador' || ($role->name === 'superadministrador' &&
                        Auth::user()->hasRole('superadministrador')))
                        <tr>
                            <td style="width:25px">
                                <div class="icheck-primary">
                                    @if(Auth::user()->id === $user->id && $role->name === 'superadministrador' &&
                                    Auth::user()->hasRole('superadministrador'))
                                    {{ Form::checkbox('roles['.$role->id.']', 1, old('roles['.$role->id.']', $user->hasRole($role->name)), ['id' => 'role_'.$role->id, 'class' => 'icheck', 'checked', 'disabled']) }}
                                    {!! Form::hidden('roles['.$role->id.']', '1', ['id' => 'role_'.$role->id]) !!}
                                    @else
                                    {{ Form::checkbox('roles['.$role->id.']', 1, old('roles['.$role->id.']', $user->hasRole($role->name)), ['id' => 'role_'.$role->id, 'class' => 'icheck']) }}
                                    @endif
                                    <label for="{{ 'role_'.$role->id }}"></label>
                                </div>
                            </td>
                            <td>
                                {{ Form::label('role_'.$role->id, $role->display_name, ['class' => 'mbn']) }}<br />
                                <span class="small">{{ $role->description }}</span><br />
                                <span
                                    class="small text-muted">{{ $role->permissions->implode('display_name', ', ') }}</span>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </table>
                    @endcomponent
                </div>

                <!-- Submit Field -->
                <div class="form-group col-sm-12">
                    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                    <a href="{!! route('administracao.users.index') !!}" class="btn btn-default">Cancel</a>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
