@extends('layouts.app-admin')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Konfigurasi</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">/member</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4 class="mb-3 header-title mt-0">Tambah Member</h4>
        <form id="addMemberForm">
            @csrf

            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label" for="name">Nama</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" value="{{ old('name') }}" name="name">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="email" class="col-lg-2 col-form-label">Email</label>
                <div class="col-lg-10">
                    <input type="email" class="form-control" value="{{ old('email') }}" name="email">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="whatsapp" class="col-lg-2 col-form-label">No Whatsapp</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" value="{{ old('whatsapp') }}" name="whatsapp">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="username" class="col-lg-2 col-form-label">Username</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" value="{{ old('username') }}" name="username">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="password" class="col-lg-2 col-form-label">Password</label>
                <div class="col-lg-10">
                    <input type="password" class="form-control" value="{{ old('password') }}" name="password">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="pin" class="col-lg-2 col-form-label">PIN</label>
                <div class="col-lg-10">
                    <input type="number" class="form-control" value="{{ old('pin') }}" name="pin">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="balance" class="col-lg-2 col-form-label">Balance</label>
                <div class="col-lg-10">
                    <input type="number" class="form-control" value="{{ old('balance') }}" name="balance">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="role" class="col-lg-2 col-form-label">Role</label>
                <div class="col-lg-10">
                    <select class="form-control" name="role">
                        <option value="Member">Member</option>
                        <option value="Platinum">Platinum</option>
                        <option value="Gold">Gold</option>
                    </select>
                </div>
            </div>

            <button type="button" class="btn btn-danger" id="submitAddMember">Buat Member</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4 class="mb-3 header-title mt-0">Kirim saldo</h4>
        <form action="" method="POST">
            @csrf

            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label" for="example-fileinput">Username</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" name="username">
                    @error('username')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label for="" class="col-lg-2 col-form-label">Jumlah</label>
                <div class="col-lg-10">
                    <input type="number" class="form-control @error('balance') is-invalid @enderror" value="{{ old('balance') }}" name="balance">
                    @error('balance')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-danger">Kirim</button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-1">Semua Pengguna</h4>
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>No Whatsapp</th>
                                <th>Saldo</th>
                                <th>Level</th>
                                <th>Created At</th>
                                <th>Hapus</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        {{-- <tbody>
                            @foreach( $users as $user )
                            <tr>
                                <th scope="row">{{ $user->id }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->no_wa }}</td>
                                <td>Rp. {{ number_format($user->balance, 0, ',', '.') }}</td>
                                <td>{{ $user->role }}</td>
                                <td>{{ $user->created_at }}</td>
                                <td><a class="btn btn-danger" href="">Hapus</a></td>
                                <td><a href="javascript:;" onclick="', '" class="btn btn-info"><i class="fa fa-qrcode"></i>Edit</a></td>
                            </tr>
                            @endforeach
                        </tbody> --}}
                    </table>
                </div>
            </div>
            
        </div>

    </div>
</div>
<script type="text/javascript">
    
</script>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="modal-detail" style="border-radius:7%">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-detail-title"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-detail-body"></div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function () {
        $('#submitAddMember').on('click', function () {
            $.ajax({
                url: 'members/add',
                type: 'POST',
                dataType: 'json',
                data: $('#addMemberForm').serialize(),
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Member berhasil ditambahkan!',
                        text: 'Member baru telah berhasil ditambahkan.',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Lakukan tindakan setelah pengiriman sukses jika diperlukan
                        }
                    });
                },
                error: function (error) {
                    if (error.responseJSON && error.responseJSON.errors) {
                        var errors = error.responseJSON.errors;
                        errorMessage = "";
                        for (var field in errors) {
                            errorMessage += errors[field][0] + "\n";
                            $('#' + field).addClass('is-invalid');
                        }
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan! Member tidak dapat ditambahkan.',
                    });
                }
            });
        });
    });
</script>
@endsection
