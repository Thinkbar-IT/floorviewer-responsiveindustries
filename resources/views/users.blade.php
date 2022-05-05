@extends('layouts.app')

@section('content')

@if (count($users) > 0)

<script type="text/javascript" charset="utf-8" async defer>
/*jslint browser: true */

function editUser(id) {
    'use strict';
    window.$('#form-update-user-id').val('');
    window.$('#form-update-user-name').val('');
    window.$('#form-update-user-email').val('');
    window.$('#form-update-user-role').val('');
    window.$('#form-update-user-enabled').attr('checked', false);
    window.$('#form-update-user-avatar-img').attr('src', '');

    window.$.ajax({
        url: '/get/user/' + id,
        success: function (user) {
            window.$('#updateUserForm').show('fast');
            window.$('#form-update-user-id').val(user.id);
            window.$('#form-update-user-name').val(user.name);
            window.$('#form-update-user-email').val(user.email);
            window.$('#form-update-user-role').val(user.role);

            if (Number(user.enabled)) { window.$('#form-update-user-enabled').attr('checked', true); }
            window.$('#form-update-user-avatar-img').attr('src', user.avatar);
        }
    });
}

var changedUsers = {};

function addCheckedUser(value, checked) {
    'use strict';
    changedUsers[value] = checked;
    window.$('#warningAlertBox').fadeOut();
}

function checkSelectedUsers() {
    'use strict';
    var id;
    for (id in changedUsers) {
        if (changedUsers.hasOwnProperty(id) && changedUsers[id]) {
            return true;
        }
    }
}

function getCheckedUsersArray() {
    'use strict';
    var ids = [],
        id;
    if (checkSelectedUsers()) {
        for (id in changedUsers) {
            if (changedUsers.hasOwnProperty(id) && changedUsers[id]) {
                ids.push(id);
            }
        }
    }
    return ids;
}

function enableSelectedUsers() {
    'use strict';
    if (checkSelectedUsers()) {
        var checkedUsers = getCheckedUsersArray();
        window.$('#usersForm').attr('action', '/users/enable');
        window.$('#usersFormInput').val(JSON.stringify(checkedUsers));

        window.$('#confirmDialogHeader').text('Confirm enabling users');
        window.$('#confirmDialogText').text('Please confirm enabling selected ' + checkedUsers.length + ' users.');
        window.$('#confirmDialogSubmit').text('Enable Users');
        window.$('#confirmDialog').modal('show');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}

function disableSelectedUsers() {
    'use strict';
    if (checkSelectedUsers()) {
        var checkedUsers = getCheckedUsersArray();
        window.$('#usersForm').attr('action', '/users/disable');
        window.$('#usersFormInput').val(JSON.stringify(checkedUsers));

        window.$('#confirmDialogHeader').text('Confirm disabling users');
        window.$('#confirmDialogText').text('Please confirm disabling selected ' + checkedUsers.length + ' users.');
        window.$('#confirmDialogSubmit').text('Disable Users');
        window.$('#confirmDialog').modal('show');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}

function deleteSelectedUsers() {
    'use strict';
    if (checkSelectedUsers()) {
        var checkedUsers = getCheckedUsersArray();
        window.$('#usersForm').attr('action', '/users/delete');
        window.$('#usersFormInput').val(JSON.stringify(checkedUsers));

        window.$('#confirmDialogHeader').text('Confirm removing users');
        window.$('#confirmDialogText').text('Please confirm removing selected ' + checkedUsers.length + ' users.');
        window.$('#confirmDialogSubmit').text('Remove Users');
        window.$('#confirmDialog').modal('show');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}

function deleteUser(id) {
    'use strict';
    window.$('#usersForm').attr('action', '/users/delete');
    window.$('#usersFormInput').val('[' + id + ']');

    window.$('#confirmDialogHeader').text('Confirm removing user');
    window.$('#confirmDialogText').text('Please confirm removing user.');
    window.$('#confirmDialogSubmit').text('Remove User');
    window.$('#confirmDialog').modal('show');
}

function showResetPasswordModal(id, name) {
    'use strict';
    if (id) {
        window.$('#form-reset-user-id').val(id);
        window.$('#form-reset-user-password').val('');
        window.$('#form-reset-user-password-confirm').val('');

        window.$('#resetPasswordModalHeader').text('User: ' + name);
        window.$('#resetPasswordModal').modal('show');
    }
}

function showBigAvatarModal(name, image) {
    'use strict';
    if (name && image) {
        window.$('#bigAvatarModalHeader').text('Tile: ' + name);
        window.$('#bigAvatarModalImg').attr('src', image);
        window.$('#bigAvatarModal').modal('show');
    }
}
</script>

  @include('common.alerts')
  @include('common.errors')

  <div id="updateUserForm" class="panel-body" style="display: none;">

    <form action="/user/update" method="POST" enctype="multipart/form-data" class="form-horizontal">
      {{ csrf_field() }}

      <div class="form-group required">
        <label for="form-update-user-id" class="col-sm-3 control-label">Id</label>
        <div class="col-sm-3">
          <input type="text" name="id" id="form-update-user-id" class="form-control" readonly="readonly" required>
        </div>
        <div class="col-sm-3">
          <label><input type="checkbox" name="enabled" id="form-update-user-enabled" value="1"> Enabled</label>
        </div>
      </div>

      <div class="form-group required">
        <label for="form-update-user-name" class="col-sm-3 control-label">Name</label>
        <div class="col-sm-6">
          <input type="text" name="name" id="form-update-user-name" class="form-control" placeholder="Name" maxlength="255" required>
        </div>
      </div>

      <div class="form-group required">
        <label for="form-update-user-email" class="col-sm-3 control-label">Email</label>
        <div class="col-sm-6">
          <input type="text" name="email" id="form-update-user-email" class="form-control" placeholder="Email" maxlength="100" required>
        </div>
      </div>

      <div class="form-group">
        <label for="form-update-user-avatar" class="col-sm-3 control-label">Avatar</label>
        <div class="col-sm-2">
          <img id="form-update-user-avatar-img" src="" alt="" class="img-thumbnail" style="max-width: 128px; max-height: 128px;cursor: pointer;" onclick="showBigAvatarModal(window.$('#form-update-user-name').val(), this.src)">
        </div>
        <div class="col-sm-4">
          <input type="file" name="avatar" id="form-update-user-avatar" accept="image/*" class="form-control">
        </div>
        <span class="col-sm-3 help-block">Image must be less than 1 MB and resolution less than 1024x1024 pixels.</span>
      </div>

      <div class="form-group">
        <label for="form-update-user-role" class="col-sm-3 control-label">Group</label>
        <div class="col-sm-6">
          <select name="role" id="form-update-user-role" class="form-control">
            <option value="guest">Guest</option>
            <option value="registered">Registered</option>
            <option value="editor">Editor</option>
            <option value="administrator">Administrator</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
          <button type="button" class="btn btn-default" onclick="deleteUser(window.$('#form-update-user-id').val());" title="Remove User"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
          <button type="button" class="btn btn-default" onclick="showResetPasswordModal(window.$('#form-update-user-id').val(), window.$('#form-update-user-name').val())">Reset password</button>
          <div class="pull-right">
            <button type="submit" class="btn btn-primary">Update user</button>
            <button type="button" class="btn btn-default" onclick="$('#updateUserForm').hide('fast');">Cancel</button>
          </div>
        </div>
      </div>
    </form>
  </div>


  <form id="usersForm" action="" method="POST">
    {{ csrf_field() }}
    <input id="usersFormInput" type="hidden" name="selectedUsers" value="">
  </form>

  <div id="confirmDialog" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 id="confirmDialogHeader" class="modal-title">Confirm </h4>
        </div>
        <div class="modal-body">
          <p id="confirmDialogText">Please confirm.</p>
        </div>
        <div class="modal-footer">
          <button id="confirmDialogSubmit" type="submit" class="btn btn-primary" onclick="$('#usersForm').submit();">Confirm</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <div id="bigAvatarModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 id="bigAvatarModalHeader" class="modal-title">User: </h4>
        </div>
        <div class="modal-body" style="text-align: center;">
          <img id="bigAvatarModalImg" src="" alt="" style="max-width: 512px; max-height: 512px;">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div id="resetPasswordModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 id="resetPasswordModalHeader" class="modal-title">Reset password to user: </h4>
        </div>

        <form action="/user/reset/password" method="POST" class="form-horizontal">
          {{ csrf_field() }}
          <input type="hidden" name="id" id="form-reset-user-id" value="" required>
          <div class="modal-body" style="text-align: center;">
            <div class="form-group required">
              <label for="form-reset-user-password" class="col-sm-3 control-label">New password</label>
              <div class="col-sm-6">
                <input type="password" name="password" id="form-reset-user-password" class="form-control" placeholder="New password" maxlength="255" required>
              </div>
            </div>
            <div class="form-group required">
              <label for="form-reset-user-password-confirm" class="col-sm-3 control-label">Confirm password</label>
              <div class="col-sm-6">
                <input type="password" name="password_confirmation" id="form-reset-user-password-confirm" class="form-control" placeholder="Confirm password" maxlength="255" required>
              </div>
              <div class="help-block">New password must contain 8 or more characters.</div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Reset Password</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="panel panel-default">

    <div class="dropdown pull-right">
      <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
        With selected
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu">
        <li><a href="#" onclick="enableSelectedUsers();">Enable</a></li>
        <li><a href="#" onclick="disableSelectedUsers();">Disable</a></li>
        <li class="divider"></li>
        <li><a href="#" onclick="deleteSelectedUsers();">Remove</a></li>
      </ul>
    </div>

    <h3 class="panel-heading">Users list</h3>

    <div class="panel-body">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>Name</th>
            <th>Email</th>
            <th>Group</th>
            <th>Enabled</th>
            <th>&nbsp;</th>
          </tr>
        </thead>

        <tbody>
          @foreach ($users as $user)
            <tr>
              <td class="table-text">
                <input type="checkbox" name="" value="{{ $user->id }}" onchange="addCheckedUser(this.value, this.checked);">
              </td>
              <td class="table-text">
                <img src="{{ $user->avatar }}" alt="" class="img-thumbnail" role="button" style="max-width: 64px; max-height: 64px;" onclick="showBigAvatarModal('{{ $user->name }}', this.src)">
              </td>
              <td class="table-text bold"><a href="#" title="Edit" onclick="editUser( {{ $user->id }} )" title="Edit">{{ $user->name }}</a></td>
              <td class="table-text">{{ $user->email }}</td>
              <td class="table-text">{{ $user->role }}</td>
              <td class="table-text">@if ($user->enabled) Yes @else No @endif</td>
              <td>
                <button type="button" class="close" onclick="deleteUser({{ $user->id }})" title="Remove User">&times;</button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <div class="page-links" style="text-align: center;">{{ $users->links() }}</div>
    </div>
  </div>
@endif
@endsection
