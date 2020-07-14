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
                {!! Form::open(['route' => 'administracao.users.store']) !!}

                @include('administracao.users.fields')


                <div class="form-group col-sm-6">
                    {!! Form::label('team', 'Teams') !!}
                    {!! Form::select('team', $teams, ['class' => 'form-control']) !!}
                </div>


                <div class="col-md-6">
                    @component('box', ['color' => 'teal', 'title' =>'User Roles'])

                    <table class="table table-sm table-hover">
                        @foreach($roles as $role)
                        <tr>
                            <td style="width:25px">
                                <div class="icheck-primary">
                                    {{ Form::checkbox('roles['.$role->id.']', null, null, ['id' => 'role_'.$role->id]) }}
                                    <label for="{{ 'role_'.$role->id }}"></label>
                                </div>
                            </td>
                            <td>
                                {{ Form::label('role_'.$role->id, $role->display_name, ['class' => 'mb-0 pb-0']) }}<br />
                                <span class="small">{{ $role->description }}</span><br />
                                <span
                                    class="small text-muted">{{ $role->permissions->implode('display_name', ', ') }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                    @endcomponent
                </div>
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
